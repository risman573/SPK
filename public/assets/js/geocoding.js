// Script untuk update coordinates menggunakan geocoding
class GeocodingHelper {
    constructor(baseUrl = '') {
        this.baseUrl = baseUrl;
    }

    // Update semua coordinates
    async updateAllCoordinates() {
        try {
            const response = await fetch(`${this.baseUrl}/geocoding/update-coordinates`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            // Check if response is ok
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                console.error('Non-JSON response received:', text);
                throw new Error('Server returned non-JSON response. Check server logs.');
            }

            const result = await response.json();
            return result;
        } catch (error) {
            console.error('Error updating coordinates:', error);
            throw error;
        }
    }

    // Update coordinate untuk data specific
    async updateSingleCoordinate(id) {
        try {
            const response = await fetch(`${this.baseUrl}/geocoding/update-single/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            // Check if response is ok
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                console.error('Non-JSON response received:', text);
                throw new Error('Server returned non-JSON response. Check server logs.');
            }

            const result = await response.json();
            return result;
        } catch (error) {
            console.error('Error updating single coordinate:', error);
            throw error;
        }
    }

    // Test geocoding untuk alamat tertentu
    async testGeocode(address) {
        try {
            const response = await fetch(`${this.baseUrl}/geocoding/test?address=${encodeURIComponent(address)}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();
            return result;
        } catch (error) {
            console.error('Error testing geocode:', error);
            throw error;
        }
    }

    // Check database connection and structure
    async checkDatabase() {
        try {
            const response = await fetch(`${this.baseUrl}/geocoding/check-db`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();
            return result;
        } catch (error) {
            console.error('Error checking database:', error);
            throw error;
        }
    }

    // Batch process (automatic processing in small batches)
    async batchProcess() {
        try {
            const response = await fetch(`${this.baseUrl}/geocoding/batch-process`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            // Check if response is ok
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                console.error('Non-JSON response received:', text);
                throw new Error('Server returned non-JSON response. Check server logs.');
            }

            const result = await response.json();
            return result;
        } catch (error) {
            console.error('Error in batch process:', error);
            throw error;
        }
    }

    // Process all data in multiple batches automatically
    async processAllInBatches(progressCallback) {
        let totalUpdated = 0;
        let totalFailed = 0;
        let batchCount = 0;
        
        try {
            while (true) {
                batchCount++;
                
                if (progressCallback) {
                    progressCallback({
                        status: 'processing',
                        batch: batchCount,
                        total_updated: totalUpdated,
                        total_failed: totalFailed
                    });
                }
                
                const result = await this.batchProcess();
                
                if (result.status !== 'success') {
                    throw new Error(result.message || 'Batch processing failed');
                }
                
                totalUpdated += result.data.total_updated;
                totalFailed += result.data.total_failed;
                
                if (progressCallback) {
                    progressCallback({
                        status: 'batch_completed',
                        batch: batchCount,
                        batch_result: result.data,
                        total_updated: totalUpdated,
                        total_failed: totalFailed
                    });
                }
                
                // Check if there are more records to process
                if (!result.data.continue_url || result.data.remaining_records === 0) {
                    break;
                }
                
                // Small delay between batches
                await new Promise(resolve => setTimeout(resolve, 1000));
            }
            
            return {
                status: 'completed',
                total_updated: totalUpdated,
                total_failed: totalFailed,
                batches_processed: batchCount
            };
            
        } catch (error) {
            if (progressCallback) {
                progressCallback({
                    status: 'error',
                    error: error.message,
                    total_updated: totalUpdated,
                    total_failed: totalFailed
                });
            }
            throw error;
        }
    }
}

// Usage example:
// const geocoding = new GeocodingHelper();
// 
// // Update semua coordinates
// geocoding.updateAllCoordinates().then(result => {
//     console.log('Update result:', result);
// });
//
// // Update single coordinate
// geocoding.updateSingleCoordinate(123).then(result => {
//     console.log('Single update result:', result);
// });
//
// // Test geocoding
// geocoding.testGeocode('Jl. Sudirman No. 1, Jakarta').then(result => {
//     console.log('Test result:', result);
// });

// jQuery example untuk button handler
$(document).ready(function() {
    const geocoding = new GeocodingHelper();

    // Button untuk update semua coordinates
    $('#btn-update-all-coordinates').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true).text('Processing...');
        
        geocoding.updateAllCoordinates()
            .then(result => {
                alert(`Update completed!\nUpdated: ${result.data.updated}\nFailed: ${result.data.failed}`);
                console.log('Full result:', result);
            })
            .catch(error => {
                alert('Error updating coordinates: ' + error.message);
            })
            .finally(() => {
                btn.prop('disabled', false).text('Update All Coordinates');
            });
    });

    // Button untuk test geocoding
    $('#btn-test-geocode').on('click', function() {
        const address = $('#test-address').val();
        if (!address) {
            alert('Please enter an address to test');
            return;
        }

        geocoding.testGeocode(address)
            .then(result => {
                if (result.status === 'success') {
                    alert(`Geocoding successful!\nLat: ${result.coordinates.lat}\nLon: ${result.coordinates.lon}`);
                } else {
                    alert('Geocoding failed for the given address');
                }
                console.log('Test result:', result);
            })
            .catch(error => {
                alert('Error testing geocode: ' + error.message);
            });
    });

    // Handler untuk update single coordinate (misalnya dari table)
    $('.btn-update-single').on('click', function() {
        const id = $(this).data('id');
        const btn = $(this);
        
        btn.prop('disabled', true).text('Updating...');
        
        geocoding.updateSingleCoordinate(id)
            .then(result => {
                if (result.status === 'success') {
                    alert('Coordinate updated successfully!');
                    // Refresh table atau update UI
                    location.reload();
                } else {
                    alert('Failed to update coordinate');
                }
            })
            .catch(error => {
                alert('Error updating coordinate: ' + error.message);
            })
            .finally(() => {
                btn.prop('disabled', false).text('Update');
            });
    });
});