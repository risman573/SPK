<?php

namespace App\Controllers;

use App\Models\Data_full_model;

class Geocoding extends BaseController
{
    protected $dataFullModel;

    public function __construct()
    {
        $this->dataFullModel = new Data_full_model();
    }

    /**
     * Tampilkan halaman geocoding manager
     */
    public function index()
    {
        return view('geocoding_manager');
    }

    /**
     * Update latitude dan longitude berdasarkan alamat
     * Endpoint: POST /geocoding/update-coordinates
     */
    public function updateCoordinates()
    {
        // Set response header untuk JSON
        $this->response->setContentType('application/json');
        
        // Increase execution time untuk proses batch
        ini_set('max_execution_time', 300); // 5 menit
        
        try {
            // Get parameters
            $batch_size = $this->request->getPost('batch_size') ?? 500; // Default 10 per batch
            $offset = $this->request->getPost('offset') ?? 0; // Start from 0
            
            // Gunakan query builder langsung untuk menghindari masalah dengan allowedFields
            $db = \Config\Database::connect();
            $builder = $db->table('data_full');
            
            // Count total records yang perlu diupdate
            $totalBuilder = $db->table('data_full');
            $totalRecords = $totalBuilder->where('alamat !=', '')
                                       ->where('alamat IS NOT NULL')
                                       ->where('(lat IS NULL OR lon IS NULL OR lat = 0 OR lon = 0)')
                                       ->countAllResults();
            
            // Ambil batch data
            $data = $builder->where('alamat !=', '')
                           ->where('alamat !=', 'Tidak Ada')
                           ->where('alamat IS NOT NULL')
                           ->where('(lat IS NULL OR lon IS NULL OR lat = 0 OR lon = 0)')
                           ->limit($batch_size, $offset)
                           ->get()
                           ->getResultArray();
            
            if (empty($data)) {
                return $this->response->setJSON([
                    'status' => 'completed',
                    'message' => 'No more data to process',
                    'data' => [
                        'total_records' => $totalRecords,
                        'offset' => $offset,
                        'batch_size' => $batch_size,
                        'remaining' => 0
                    ]
                ]);
            }
            
            $updated = 0;
            $failed = 0;
            $errors = [];
            $processed = [];

            foreach ($data as $row) {
                try {
                    // Update alamat utama
                    $coordinates = $this->getCoordinatesFromAddress($row['alamat']);
                    if ($coordinates && isset($coordinates['lat']) && isset($coordinates['lon'])) {
                        $updateData = [
                            'lat' => $coordinates['lat'],
                            'lon' => $coordinates['lon']
                        ];
                        
                        // Reset builder untuk setiap update
                        $updateBuilder = $db->table('data_full');
                        $result = $updateBuilder->where('id', $row['id'])->update($updateData);
                        
                        if ($result) {
                            $updated++;
                            $processed[] = [
                                'id' => $row['id'],
                                'alamat' => substr($row['alamat'], 0, 50) . '...',
                                'coordinates' => $coordinates,
                                'status' => 'success'
                            ];
                        } else {
                            $failed++;
                            $errors[] = "Failed to update database for ID: " . $row['id'];
                            $processed[] = [
                                'id' => $row['id'],
                                'alamat' => substr($row['alamat'], 0, 50) . '...',
                                'status' => 'db_error'
                            ];
                        }
                    } else {
                        $failed++;
                        $errors[] = "Failed to geocode address for ID: " . $row['id'];
                        $processed[] = [
                            'id' => $row['id'],
                            'alamat' => substr($row['alamat'], 0, 50) . '...',
                            'status' => 'geocode_failed'
                        ];
                    }

                    // Delay yang lebih pendek untuk rate limiting
                    usleep(500000); // 0.5 detik
                    
                } catch (\Exception $e) {
                    $failed++;
                    $errors[] = "Error processing ID " . $row['id'] . ": " . $e->getMessage();
                    $processed[] = [
                        'id' => $row['id'],
                        'status' => 'error',
                        'error' => $e->getMessage()
                    ];
                }
            }

            $nextOffset = $offset + $batch_size;
            $remaining = max(0, $totalRecords - $nextOffset);
            $hasMore = $remaining > 0;

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Batch processing completed',
                'data' => [
                    'updated' => $updated,
                    'failed' => $failed,
                    'total_processed' => count($data),
                    'errors' => $errors,
                    'processed_details' => $processed,
                    'pagination' => [
                        'current_offset' => $offset,
                        'next_offset' => $nextOffset,
                        'batch_size' => $batch_size,
                        'total_records' => $totalRecords,
                        'remaining' => $remaining,
                        'has_more' => $hasMore,
                        'progress_percentage' => round((($offset + count($data)) / $totalRecords) * 100, 2)
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            // Log error untuk debugging
            log_message('error', 'UpdateCoordinates error: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Failed to update coordinates: ' . $e->getMessage(),
                'debug_info' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ]);
        }
    }

    /**
     * Process batch geocoding secara otomatis
     * Endpoint: POST /geocoding/batch-process
     */
    public function batchProcess()
    {
        // Set response header untuk JSON
        $this->response->setContentType('application/json');
        
        try {
            $batchSize = 50; // Proses 5 record per batch
            $offset = 0;
            $totalUpdated = 0;
            $totalFailed = 0;
            $allErrors = [];
            $batchResults = [];
            
            // Get total count first
            $db = \Config\Database::connect();
            $totalBuilder = $db->table('data_full');
            $totalRecords = $totalBuilder->where('alamat !=', '')
                                       ->where('alamat IS NOT NULL')
                                       ->where('(lat IS NULL OR lon IS NULL OR lat = 0 OR lon = 0)')
                                       ->countAllResults();
            
            if ($totalRecords == 0) {
                return $this->response->setJSON([
                    'status' => 'completed',
                    'message' => 'No records need updating',
                    'data' => [
                        'total_updated' => 0,
                        'total_failed' => 0,
                        'total_records' => 0
                    ]
                ]);
            }
            
            // Limit total batches untuk mencegah timeout
            $maxBatches = 10; // Max 50 records per request
            $batchCount = 0;
            
            while ($batchCount < $maxBatches) {
                $builder = $db->table('data_full');
                $data = $builder->where('alamat !=', '')
                               ->where('alamat IS NOT NULL')
                               ->where('(lat IS NULL OR lon IS NULL OR lat = 0 OR lon = 0)')
                               ->limit($batchSize, $offset)
                               ->get()
                               ->getResultArray();
                
                if (empty($data)) {
                    break; // No more data
                }
                
                $batchUpdated = 0;
                $batchFailed = 0;
                $batchErrors = [];
                
                foreach ($data as $row) {
                    try {
                        $coordinates = $this->getCoordinatesFromAddress($row['alamat']);
                        if ($coordinates && isset($coordinates['lat']) && isset($coordinates['lon'])) {
                            $updateData = [
                                'lat' => $coordinates['lat'],
                                'lon' => $coordinates['lon']
                            ];
                            
                            $updateBuilder = $db->table('data_full');
                            $result = $updateBuilder->where('id', $row['id'])->update($updateData);
                            
                            if ($result) {
                                $batchUpdated++;
                                $totalUpdated++;
                            } else {
                                $batchFailed++;
                                $totalFailed++;
                                $batchErrors[] = "Failed to update DB for ID: " . $row['id'];
                            }
                        } else {
                            $batchFailed++;
                            $totalFailed++;
                            $batchErrors[] = "Failed to geocode ID: " . $row['id'];
                        }
                        
                        // Short delay
                        usleep(300000); // 0.3 seconds
                        
                    } catch (\Exception $e) {
                        $batchFailed++;
                        $totalFailed++;
                        $batchErrors[] = "Error ID " . $row['id'] . ": " . $e->getMessage();
                    }
                }
                
                $batchResults[] = [
                    'batch' => $batchCount + 1,
                    'offset' => $offset,
                    'processed' => count($data),
                    'updated' => $batchUpdated,
                    'failed' => $batchFailed,
                    'errors' => $batchErrors
                ];
                
                $allErrors = array_merge($allErrors, $batchErrors);
                $offset += $batchSize;
                $batchCount++;
            }
            
            $remaining = max(0, $totalRecords - $offset);
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Batch processing completed',
                'data' => [
                    'total_updated' => $totalUpdated,
                    'total_failed' => $totalFailed,
                    'batches_processed' => $batchCount,
                    'total_records' => $totalRecords,
                    'records_processed' => $offset,
                    'remaining_records' => $remaining,
                    'batch_results' => $batchResults,
                    'errors' => array_slice($allErrors, 0, 10), // Limit errors shown
                    'continue_url' => $remaining > 0 ? '/geocoding/batch-process' : null
                ]
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'BatchProcess error: ' . $e->getMessage());
            
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Batch processing failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update coordinates untuk data specific berdasarkan ID
     * Endpoint: POST /geocoding/update-single/{id}
     */
    public function updateSingle($id)
    {
        // Set response header untuk JSON
        $this->response->setContentType('application/json');
        
        try {
            // Validasi ID
            if (!is_numeric($id) || $id <= 0) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => 'error',
                    'message' => 'Invalid ID provided'
                ]);
            }
            
            // Gunakan query builder langsung
            $db = \Config\Database::connect();
            $builder = $db->table('data_full');
            
            $data = $builder->where('id', $id)->get()->getRowArray();
            
            if (!$data) {
                return $this->response->setStatusCode(404)->setJSON([
                    'status' => 'error',
                    'message' => 'Data not found'
                ]);
            }

            $updated = false;
            $coordinatesData = [];
            $errorMessage = '';

            // Update alamat utama jika ada
            if (!empty($data['alamat'])) {
                try {
                    $coordinates = $this->getCoordinatesFromAddress($data['alamat']);
                    if ($coordinates && isset($coordinates['lat']) && isset($coordinates['lon'])) {
                        $updateData = [
                            'lat' => $coordinates['lat'],
                            'lon' => $coordinates['lon']
                        ];
                        
                        $updateBuilder = $db->table('data_full');
                        $result = $updateBuilder->where('id', $id)->update($updateData);
                        
                        if ($result) {
                            $updated = true;
                            $coordinatesData = $coordinates;
                        } else {
                            $errorMessage = 'Failed to update database';
                        }
                    } else {
                        $errorMessage = 'Failed to geocode address';
                    }
                } catch (\Exception $e) {
                    $errorMessage = 'Geocoding error: ' . $e->getMessage();
                }
            } else {
                $errorMessage = 'Address is empty';
            }

            return $this->response->setJSON([
                'status' => $updated ? 'success' : 'failed',
                'message' => $updated ? 'Coordinates updated successfully' : $errorMessage,
                'data' => [
                    'id' => $id,
                    'alamat' => $data['alamat'] ?? '',
                    'coordinates' => $coordinatesData,
                    'old_lat' => $data['lat'] ?? null,
                    'old_lon' => $data['lon'] ?? null
                ]
            ]);

        } catch (\Exception $e) {
            // Log error untuk debugging
            log_message('error', 'UpdateSingle error: ' . $e->getMessage());
            
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Failed to update coordinates: ' . $e->getMessage(),
                'debug_info' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ]);
        }
    }

    /**
     * Mendapatkan koordinat dari alamat menggunakan Nominatim API (OpenStreetMap)
     * Dengan multiple strategy untuk meningkatkan success rate
     */
    private function getCoordinatesFromAddress($address)
    {
        try {
            // Bersihkan dan format alamat
            $address = trim($address);
            if (empty($address)) {
                return false;
            }

            // Strategy 1: Coba alamat lengkap dengan Indonesia
            $coordinates = $this->tryGeocode($address . ', Indonesia');
            if ($coordinates) {
                return $coordinates;
            }

            // Strategy 2: Coba hanya dengan nama kota/provinsi di akhir
            $addressParts = explode(',', $address);
            if (count($addressParts) > 1) {
                // Ambil 2 bagian terakhir (biasanya kota, provinsi)
                $lastParts = array_slice($addressParts, -2);
                $shortAddress = implode(', ', $lastParts) . ', Indonesia';
                $coordinates = $this->tryGeocode($shortAddress);
                if ($coordinates) {
                    return $coordinates;
                }
            }

            // Strategy 3: Coba hanya dengan bagian kota/kabupaten
            if (preg_match('/([A-Za-z\s]+(?:Kabupaten|Kota|Kab\.)\s*[A-Za-z\s]+)/i', $address, $matches)) {
                $cityAddress = $matches[1] . ', Indonesia';
                $coordinates = $this->tryGeocode($cityAddress);
                if ($coordinates) {
                    return $coordinates;
                }
            }

            // Strategy 4: Ekstrak nama kota umum
            $cities = ['Jakarta', 'Surabaya', 'Bandung', 'Medan', 'Bekasi', 'Tangerang', 'Depok', 'Semarang', 'Palembang', 'Makassar', 'Yogyakarta', 'Bogor', 'Malang', 'Solo', 'Batam'];
            foreach ($cities as $city) {
                if (stripos($address, $city) !== false) {
                    $coordinates = $this->tryGeocode($city . ', Indonesia');
                    if ($coordinates) {
                        return $coordinates;
                    }
                }
            }

            // Strategy 5: Coba dengan Google sebagai fallback (jika diaktifkan)
            // Uncomment jika ingin menggunakan Google Maps API
            // return $this->getCoordinatesFromAddressGoogle($address);

            return false;
            
        } catch (\Exception $e) {
            log_message('error', 'Geocoding error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Helper method untuk mencoba geocoding dengan address tertentu
     */
    private function tryGeocode($address)
    {
        try {
            // Encode address untuk URL
            $encodedAddress = urlencode($address);
            
            // Nominatim API endpoint (gratis)
            $url = "https://nominatim.openstreetmap.org/search?q={$encodedAddress}&format=json&limit=1&addressdetails=1&countrycodes=id";
            
            // Setup cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_USERAGENT, 'DokterApp/1.0 (Contact: admin@dokterapp.com)'); // Required by Nominatim
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            
            if ($error) {
                log_message('error', 'cURL error: ' . $error);
                return false;
            }
            
            if ($httpCode !== 200 || !$response) {
                log_message('error', 'HTTP error: ' . $httpCode . ' for address: ' . $address);
                return false;
            }
            
            $data = json_decode($response, true);
            
            if (!empty($data) && isset($data[0]['lat']) && isset($data[0]['lon'])) {
                return [
                    'lat' => (float) $data[0]['lat'],
                    'lon' => (float) $data[0]['lon']
                ];
            }
            
            return false;
            
        } catch (\Exception $e) {
            log_message('error', 'Geocoding tryGeocode error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Alternative menggunakan Google Maps Geocoding API (berbayar)
     * Uncomment dan gunakan method ini jika ingin menggunakan Google Maps
     */
    private function getCoordinatesFromAddressGoogle($address)
    {
        try {
            // Ganti dengan API Key Google Maps Anda
            $apiKey = 'YOUR_GOOGLE_MAPS_API_KEY';
            
            $address = trim($address);
            if (empty($address)) {
                return false;
            }

            $encodedAddress = urlencode($address);
            $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$encodedAddress}&key={$apiKey}";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode !== 200 || !$response) {
                return false;
            }
            
            $data = json_decode($response, true);
            
            if ($data['status'] === 'OK' && !empty($data['results'])) {
                $location = $data['results'][0]['geometry']['location'];
                return [
                    'lat' => (float) $location['lat'],
                    'lon' => (float) $location['lng']
                ];
            }
            
            return false;
            
        } catch (\Exception $e) {
            log_message('error', 'Google Geocoding error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Test geocoding untuk alamat tertentu dengan detail debug
     * Endpoint: GET /geocoding/test?address=alamat
     */
    public function test()
    {
        // Set response header untuk JSON
        $this->response->setContentType('application/json');
        
        $address = $this->request->getGet('address');
        
        if (empty($address)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Address parameter is required',
                'example' => '/geocoding/test?address=Jl. Sudirman Jakarta'
            ]);
        }

        try {
            $startTime = microtime(true);
            $coordinates = $this->getCoordinatesFromAddress($address);
            $endTime = microtime(true);
            $processingTime = round(($endTime - $startTime) * 1000, 2); // in milliseconds
            
            // Juga coba strategy individual untuk debugging
            $debugInfo = $this->getDebugGeocodingInfo($address);
            
            return $this->response->setJSON([
                'status' => $coordinates ? 'success' : 'failed',
                'address' => $address,
                'coordinates' => $coordinates,
                'processing_time_ms' => $processingTime,
                'debug_info' => $debugInfo
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Test geocoding error: ' . $e->getMessage());
            
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Test failed: ' . $e->getMessage(),
                'address' => $address
            ]);
        }
    }

    /**
     * Method khusus untuk debug geocoding
     */
    private function getDebugGeocodingInfo($address)
    {
        $debugInfo = [];
        
        // Test Strategy 1: Full address
        $debugInfo['strategy_1_full_address'] = [
            'query' => $address . ', Indonesia',
            'result' => $this->tryGeocode($address . ', Indonesia') ? 'success' : 'failed'
        ];
        
        // Test Strategy 2: Last parts
        $addressParts = explode(',', $address);
        if (count($addressParts) > 1) {
            $lastParts = array_slice($addressParts, -2);
            $shortAddress = implode(', ', $lastParts) . ', Indonesia';
            $debugInfo['strategy_2_short_address'] = [
                'query' => $shortAddress,
                'result' => $this->tryGeocode($shortAddress) ? 'success' : 'failed'
            ];
        }
        
        // Test Strategy 3: City detection
        if (preg_match('/([A-Za-z\s]+(?:Kabupaten|Kota|Kab\.)\s*[A-Za-z\s]+)/i', $address, $matches)) {
            $cityAddress = $matches[1] . ', Indonesia';
            $debugInfo['strategy_3_city_detection'] = [
                'query' => $cityAddress,
                'result' => $this->tryGeocode($cityAddress) ? 'success' : 'failed'
            ];
        }
        
        return $debugInfo;
    }

    /**
     * Method khusus untuk debugging dengan alamat sample
     * Endpoint: GET /geocoding/debug
     */
    public function debugTest()
    {
        $sampleAddresses = [
            'Jl. Pandeansari Blok II No.1, RT009/ RW063, Gandok, Condongcatur, Depok, Sleman, Yogyakarta',
            'Jl. Sudirman No. 1, Jakarta',
            'Jl. Malioboro, Yogyakarta',
            'Depok, Sleman, Yogyakarta',
            'Yogyakarta'
        ];

        $results = [];
        
        foreach ($sampleAddresses as $address) {
            $startTime = microtime(true);
            $coordinates = $this->getCoordinatesFromAddress($address);
            $endTime = microtime(true);
            $processingTime = round(($endTime - $startTime) * 1000, 2);
            
            $results[] = [
                'address' => $address,
                'status' => $coordinates ? 'success' : 'failed',
                'coordinates' => $coordinates,
                'processing_time_ms' => $processingTime,
                'debug_info' => $this->getDebugGeocodingInfo($address)
            ];
            
            // Delay untuk rate limiting
            sleep(1);
        }

        return $this->response->setJSON([
            'status' => 'completed',
            'message' => 'Debug test completed for sample addresses',
            'results' => $results
        ]);
    }

    /**
     * Check database connection and table structure
     * Endpoint: GET /geocoding/check-db
     */
    public function checkDatabase()
    {
        try {
            $db = \Config\Database::connect();
            
            // Test connection
            $dbResult = $db->query("SELECT 1 as test")->getRow();
            
            // Check table exists
            $tableExists = $db->tableExists('data_full');
            
            // Get table structure
            $fields = [];
            if ($tableExists) {
                $fields = $db->getFieldNames('data_full');
                
                // Count records
                $totalRecords = $db->table('data_full')->countAll();
                $recordsWithAddress = $db->table('data_full')
                    ->where('alamat !=', '')
                    ->where('alamat IS NOT NULL')
                    ->countAllResults();
                    
                // Sample data
                $sampleData = $db->table('data_full')
                    ->select('id, alamat, lat, lon')
                    ->where('alamat !=', '')
                    ->where('alamat IS NOT NULL')
                    ->limit(3)
                    ->get()
                    ->getResultArray();
            }
            
            return $this->response->setJSON([
                'status' => 'success',
                'database_connection' => $dbResult ? 'OK' : 'Failed',
                'table_exists' => $tableExists,
                'table_fields' => $fields,
                'statistics' => [
                    'total_records' => $totalRecords ?? 0,
                    'records_with_address' => $recordsWithAddress ?? 0
                ],
                'sample_data' => $sampleData ?? []
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Database check failed: ' . $e->getMessage(),
                'debug_info' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ]);
        }
    }
}