<?php
/**
 * Simple PHP script untuk testing Geocoding API
 * Run this script via command line: php test_geocoding.php
 */

// Base URL aplikasi Anda
$baseUrl = 'http://localhost:8080'; // Sesuaikan dengan URL aplikasi Anda

// Function untuk melakukan HTTP request
function makeRequest($url, $method = 'GET', $data = null) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HEADER, false);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'X-Requested-With: XMLHttpRequest'
            ]);
        }
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'http_code' => $httpCode,
        'response' => $response,
        'data' => json_decode($response, true)
    ];
}

echo "=== Testing Geocoding API ===\n\n";

// Test 1: Test geocoding alamat
echo "1. Testing geocoding untuk alamat...\n";
$testAddress = 'Jl. Sudirman No. 1, Jakarta Pusat, DKI Jakarta';
$url = $baseUrl . '/geocoding/test?address=' . urlencode($testAddress);

$result = makeRequest($url);
echo "URL: $url\n";
echo "HTTP Code: " . $result['http_code'] . "\n";
echo "Response: " . $result['response'] . "\n\n";

if ($result['data'] && $result['data']['status'] === 'success') {
    echo "✓ Geocoding berhasil!\n";
    echo "  Latitude: " . $result['data']['coordinates']['lat'] . "\n";
    echo "  Longitude: " . $result['data']['coordinates']['lon'] . "\n\n";
} else {
    echo "✗ Geocoding gagal atau error\n\n";
}

// Test 2: Update single coordinate (contoh dengan ID 1)
echo "2. Testing update single coordinate (ID: 1)...\n";
$url = $baseUrl . '/geocoding/update-single/1';

$result = makeRequest($url, 'POST');
echo "URL: $url\n";
echo "HTTP Code: " . $result['http_code'] . "\n";
echo "Response: " . $result['response'] . "\n\n";

// Test 3: Menampilkan informasi untuk update all (tidak dijalankan untuk testing)
echo "3. Informasi untuk update all coordinates:\n";
echo "URL: $baseUrl/geocoding/update-coordinates\n";
echo "Method: POST\n";
echo "⚠️ Tidak dijalankan dalam test ini karena akan memproses semua data\n\n";

// Test 4: Akses halaman geocoding manager
echo "4. Testing akses halaman geocoding manager...\n";
$url = $baseUrl . '/geocoding';

$result = makeRequest($url);
echo "URL: $url\n";
echo "HTTP Code: " . $result['http_code'] . "\n";

if ($result['http_code'] === 200) {
    echo "✓ Halaman geocoding manager dapat diakses\n";
} else {
    echo "✗ Halaman geocoding manager tidak dapat diakses\n";
}

echo "\n=== Testing Selesai ===\n";
echo "Catatan:\n";
echo "- Pastikan aplikasi berjalan di: $baseUrl\n";
echo "- Untuk testing lengkap, akses: $baseUrl/geocoding\n";
echo "- Untuk update all data, gunakan: POST $baseUrl/geocoding/update-coordinates\n";
?>