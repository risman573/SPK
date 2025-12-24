<?=view('header_view'); ?>

<style>
.dashboard-card {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 2px 12px rgba(44,62,80,0.08);
  padding: 32px 24px;
  margin-bottom: 20px;
  transition: transform 0.2s, box-shadow 0.2s;
}

.dashboard-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 16px rgba(44,62,80,0.12);
}

.card-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
}

.card-icon {
  font-size: 48px;
  margin-bottom: 16px;
}

.metric-label {
  font-size: 15px;
  color: #666;
  margin-bottom: 10px;
  font-weight: 500;
}

.metric-value {
  font-size: 42px;
  font-weight: bold;
  color: #263238;
  display: flex;
  align-items: center;
  justify-content: center;
}

.metrics-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
  margin-top: 20px;
}

@media (max-width: 992px) {
  .metrics-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 576px) {
  .metrics-grid {
    grid-template-columns: 1fr;
  }

  .card-icon {
    font-size: 40px;
  }

  .metric-value {
    font-size: 36px;
  }
}
</style>

<!-- SPK SAW Header -->
<div class="container-fluid bg-light-opac mb-4 main-container">
    <div class="row">
        <div class="container-fluid my-3 main-container">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="content-color-primary page-title">🖥️ SPK Pemilihan Laptop Terbaik</h2>
                    <p style="color: #666; margin-top: 5px; font-size: 14px;">Sistem Pendukung Keputusan menggunakan metode SAW (Simple Additive Weighting)</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid main-container">
    <!-- Metrics Cards -->
    <div class="metrics-grid">
        <div class="dashboard-card">
            <div class="card-content">
                <div class="card-icon">💻</div>
                <div class="metric-label">Total Laptop</div>
                <div class="metric-value" id="stat_alternatif">-</div>
            </div>
        </div>
        <div class="dashboard-card">
            <div class="card-content">
                <div class="card-icon">📋</div>
                <div class="metric-label">Kriteria</div>
                <div class="metric-value" id="stat_kriteria">-</div>
            </div>
        </div>
        <div class="dashboard-card">
            <div class="card-content">
                <div class="card-icon">📊</div>
                <div class="metric-label">Data Penilaian</div>
                <div class="metric-value" id="stat_nilai">-</div>
            </div>
        </div>
        <div class="dashboard-card">
            <div class="card-content">
                <div class="card-icon">✅</div>
                <div class="metric-label">Normalisasi</div>
                <div class="metric-value" id="stat_normalisasi">-</div>
            </div>
        </div>
    </div>
</div>

<script>
function loadDashboardData() {
    console.log('Loading dashboard data...');
    console.log('URL:', '<?=base_url()?>dashboard/data');

    fetch('<?=base_url()?>dashboard/data')
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Data received:', data);

            // Update statistics
            document.getElementById('stat_alternatif').textContent = data.stat_alternatif || 0;
            document.getElementById('stat_kriteria').textContent = data.stat_kriteria || 0;
            document.getElementById('stat_nilai').textContent = data.stat_nilai || 0;
            document.getElementById('stat_normalisasi').textContent = data.stat_normalisasi || 0;

            console.log('Dashboard updated successfully');
        })
        .catch(error => {
            console.error('Error loading dashboard data:', error);
            alert('Error loading data: ' + error.message + '\nCheck console for details');
        });
}

// Load data when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, starting data load...');
    loadDashboardData();
});
</script>

<?=view('footer_view'); ?>