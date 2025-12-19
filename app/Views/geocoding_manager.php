<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Geocoding Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4">Geocoding Manager</h1>
                
                <!-- Test Geocoding Section -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Test Geocoding</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="test-address" 
                                       placeholder="Masukkan alamat untuk test geocoding..."
                                       value="Jl. Pandeansari Blok II No.1, RT009/ RW063, Gandok, Condongcatur, Depok, Sleman, Yogyakarta">
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-primary" id="btn-test-geocode">
                                    Test Geocoding
                                </button>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <button type="button" class="btn btn-info btn-sm" id="btn-check-db">
                                    Check Database Connection
                                </button>
                            </div>
                        </div>
                        <div id="test-result" class="mt-3"></div>
                    </div>
                </div>

                <!-- Update Coordinates Section -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Update All Coordinates</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">
                            Pilih salah satu metode untuk mengupdate koordinat:
                        </p>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <button type="button" class="btn btn-success btn-lg w-100" id="btn-batch-process">
                                    🚀 Smart Batch Process
                                </button>
                                <small class="text-muted">Proses otomatis dalam batch kecil (Recommended)</small>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-warning btn-lg w-100" id="btn-update-all-coordinates">
                                    ⚡ Manual Batch
                                </button>
                                <small class="text-muted">Proses manual dengan kontrol batch size</small>
                            </div>
                        </div>
                        
                        <div id="update-progress" class="mt-3"></div>
                    </div>
                </div>

                <!-- Manual Update Section -->
                <div class="card">
                    <div class="card-header">
                        <h5>Update Single Record</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="number" class="form-control" id="single-id" 
                                       placeholder="Masukkan ID record yang ingin diupdate...">
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-warning" id="btn-update-single">
                                    Update Single
                                </button>
                            </div>
                        </div>
                        <div id="single-result" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/js/geocoding.js') ?>"></script>
    
    <script>
        $(document).ready(function() {
            const geocoding = new GeocodingHelper();

            // Check database
            $('#btn-check-db').on('click', function() {
                const btn = $(this);
                const resultDiv = $('#test-result');
                
                btn.prop('disabled', true).text('Checking...');
                resultDiv.html('<div class="alert alert-info">Checking database connection...</div>');

                geocoding.checkDatabase()
                    .then(result => {
                        if (result.status === 'success') {
                            resultDiv.html(`
                                <div class="alert alert-success">
                                    <strong>Database Check Results:</strong><br>
                                    <strong>Connection:</strong> ${result.database_connection}<br>
                                    <strong>Table Exists:</strong> ${result.table_exists ? 'Yes' : 'No'}<br>
                                    <strong>Total Records:</strong> ${result.statistics.total_records}<br>
                                    <strong>Records with Address:</strong> ${result.statistics.records_with_address}<br>
                                    <strong>Fields:</strong> ${result.table_fields.join(', ')}<br>
                                    <details class="mt-2">
                                        <summary>Sample Data</summary>
                                        <pre>${JSON.stringify(result.sample_data, null, 2)}</pre>
                                    </details>
                                </div>
                            `);
                        } else {
                            resultDiv.html(`
                                <div class="alert alert-danger">
                                    <strong>Database Error:</strong> ${result.message}
                                </div>
                            `);
                        }
                    })
                    .catch(error => {
                        resultDiv.html(`
                            <div class="alert alert-danger">
                                <strong>Error:</strong> ${error.message}
                            </div>
                        `);
                    })
                    .finally(() => {
                        btn.prop('disabled', false).text('Check Database Connection');
                    });
            });

            // Test geocoding
            $('#btn-test-geocode').on('click', function() {
                const address = $('#test-address').val().trim();
                if (!address) {
                    alert('Silakan masukkan alamat untuk ditest');
                    return;
                }

                const btn = $(this);
                const resultDiv = $('#test-result');
                
                btn.prop('disabled', true).text('Testing...');
                resultDiv.html('<div class="alert alert-info">Sedang melakukan geocoding...</div>');

                geocoding.testGeocode(address)
                    .then(result => {
                        if (result.status === 'success') {
                            resultDiv.html(`
                                <div class="alert alert-success">
                                    <strong>Berhasil!</strong><br>
                                    <strong>Alamat:</strong> ${result.address}<br>
                                    <strong>Latitude:</strong> ${result.coordinates.lat}<br>
                                    <strong>Longitude:</strong> ${result.coordinates.lon}
                                </div>
                            `);
                        } else {
                            resultDiv.html(`
                                <div class="alert alert-danger">
                                    <strong>Gagal!</strong> Tidak dapat menemukan koordinat untuk alamat: ${result.address}
                                </div>
                            `);
                        }
                    })
                    .catch(error => {
                        resultDiv.html(`
                            <div class="alert alert-danger">
                                <strong>Error:</strong> ${error.message}
                            </div>
                        `);
                    })
                    .finally(() => {
                        btn.prop('disabled', false).text('Test Geocoding');
                    });
            });

            // Smart Batch Processing
            $('#btn-batch-process').on('click', function() {
                if (!confirm('Mulai proses batch otomatis? Sistem akan memproses data dalam batch kecil untuk menghindari timeout.')) {
                    return;
                }

                const btn = $(this);
                const progressDiv = $('#update-progress');
                const manualBtn = $('#btn-update-all-coordinates');
                
                btn.prop('disabled', true).text('🔄 Processing...');
                manualBtn.prop('disabled', true);
                progressDiv.html('<div class="alert alert-info">🚀 Memulai smart batch processing...</div>');

                let totalUpdated = 0;
                let totalFailed = 0;
                let batchCount = 0;

                geocoding.processAllInBatches((progress) => {
                    if (progress.status === 'processing') {
                        progressDiv.html(`
                            <div class="alert alert-info">
                                🔄 <strong>Processing Batch ${progress.batch}</strong><br>
                                Total Updated: ${progress.total_updated} | Total Failed: ${progress.total_failed}
                            </div>
                        `);
                    } else if (progress.status === 'batch_completed') {
                        const batch = progress.batch_result;
                        progressDiv.html(`
                            <div class="alert alert-info">
                                ✅ <strong>Batch ${progress.batch} Completed</strong><br>
                                <strong>Batch Results:</strong> ${batch.total_updated} updated, ${batch.total_failed} failed<br>
                                <strong>Overall Total:</strong> ${progress.total_updated} updated, ${progress.total_failed} failed<br>
                                <strong>Remaining:</strong> ${batch.remaining_records} records<br>
                                <div class="progress mt-2">
                                    <div class="progress-bar" role="progressbar" style="width: ${100 - (batch.remaining_records / batch.total_records * 100)}%"></div>
                                </div>
                            </div>
                        `);
                    } else if (progress.status === 'error') {
                        progressDiv.html(`
                            <div class="alert alert-danger">
                                ❌ <strong>Error in Batch ${progress.batch}:</strong> ${progress.error}<br>
                                <strong>Progress so far:</strong> ${progress.total_updated} updated, ${progress.total_failed} failed
                            </div>
                        `);
                    }
                })
                .then(result => {
                    progressDiv.html(`
                        <div class="alert alert-success">
                            🎉 <strong>All Batches Completed!</strong><br>
                            <strong>Final Results:</strong><br>
                            ✅ Successfully Updated: ${result.total_updated}<br>
                            ❌ Failed: ${result.total_failed}<br>
                            📦 Total Batches Processed: ${result.batches_processed}
                        </div>
                    `);
                })
                .catch(error => {
                    console.error('Batch processing error:', error);
                    progressDiv.html(`
                        <div class="alert alert-danger">
                            ❌ <strong>Batch Processing Failed:</strong> ${error.message}<br>
                            <small>Check browser console for more details.</small>
                        </div>
                    `);
                })
                .finally(() => {
                    btn.prop('disabled', false).text('🚀 Smart Batch Process');
                    manualBtn.prop('disabled', false);
                });
            });

            // Update all coordinates dengan error handling yang lebih baik
            $('#btn-update-all-coordinates').on('click', function() {
                if (!confirm('Apakah Anda yakin ingin mengupdate semua koordinat? Proses ini mungkin memakan waktu lama.')) {
                    return;
                }

                const btn = $(this);
                const progressDiv = $('#update-progress');
                
                btn.prop('disabled', true).text('Processing...');
                progressDiv.html('<div class="alert alert-info">Sedang mengupdate koordinat semua data...</div>');

                geocoding.updateAllCoordinates()
                    .then(result => {
                        console.log('Full response:', result);
                        
                        if (result.status === 'success') {
                            let errorHtml = '';
                            if (result.data.errors && result.data.errors.length > 0) {
                                errorHtml = '<br><strong>Errors:</strong><br>' + result.data.errors.slice(0, 5).join('<br>');
                                if (result.data.errors.length > 5) {
                                    errorHtml += '<br>... and ' + (result.data.errors.length - 5) + ' more errors';
                                }
                            }

                            let detailsHtml = '';
                            if (result.data.processed_details && result.data.processed_details.length > 0) {
                                detailsHtml = '<br><details class="mt-2"><summary>Processing Details</summary><pre>' + 
                                            JSON.stringify(result.data.processed_details, null, 2) + '</pre></details>';
                            }
                            
                            progressDiv.html(`
                                <div class="alert alert-success">
                                    <strong>Update Selesai!</strong><br>
                                    <strong>Total Processed:</strong> ${result.data.total_processed}<br>
                                    <strong>Berhasil diupdate:</strong> ${result.data.updated}<br>
                                    <strong>Gagal:</strong> ${result.data.failed}
                                    ${errorHtml}
                                    ${detailsHtml}
                                </div>
                            `);
                        } else {
                            progressDiv.html(`
                                <div class="alert alert-danger">
                                    <strong>Error:</strong> ${result.message}<br>
                                    <details class="mt-2">
                                        <summary>Debug Info</summary>
                                        <pre>${JSON.stringify(result, null, 2)}</pre>
                                    </details>
                                </div>
                            `);
                        }
                    })
                    .catch(error => {
                        console.error('Update error:', error);
                        progressDiv.html(`
                            <div class="alert alert-danger">
                                <strong>Network/Server Error:</strong> ${error.message}<br>
                                <small>Check browser console for more details.</small>
                            </div>
                        `);
                    })
                    .finally(() => {
                        btn.prop('disabled', false).text('Update All Coordinates');
                    });
            });

            // Update single coordinate
            $('#btn-update-single').on('click', function() {
                const id = $('#single-id').val().trim();
                if (!id || isNaN(id)) {
                    alert('Silakan masukkan ID yang valid');
                    return;
                }

                const btn = $(this);
                const resultDiv = $('#single-result');
                
                btn.prop('disabled', true).text('Updating...');
                resultDiv.html('<div class="alert alert-info">Sedang mengupdate koordinat...</div>');

                geocoding.updateSingleCoordinate(id)
                    .then(result => {
                        if (result.status === 'success') {
                            resultDiv.html(`
                                <div class="alert alert-success">
                                    <strong>Berhasil!</strong> Koordinat untuk ID ${id} telah diupdate.
                                </div>
                            `);
                        } else if (result.status === 'error' && result.message.includes('not found')) {
                            resultDiv.html(`
                                <div class="alert alert-warning">
                                    <strong>Data tidak ditemukan!</strong> ID ${id} tidak ada dalam database.
                                </div>
                            `);
                        } else {
                            resultDiv.html(`
                                <div class="alert alert-danger">
                                    <strong>Gagal!</strong> ${result.message}
                                </div>
                            `);
                        }
                    })
                    .catch(error => {
                        resultDiv.html(`
                            <div class="alert alert-danger">
                                <strong>Error:</strong> ${error.message}
                            </div>
                        `);
                    })
                    .finally(() => {
                        btn.prop('disabled', false).text('Update Single');
                        $('#single-id').val('');
                    });
            });
        });
    </script>
</body>
</html>