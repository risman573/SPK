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

.ranking-section {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 2px 12px rgba(44,62,80,0.08);
  padding: 24px;
  margin-top: 20px;
}

.ranking-title {
  font-size: 18px;
  font-weight: 600;
  color: #263238;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.ranking-list {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.ranking-item {
  display: flex;
  align-items: center;
  padding: 16px;
  background: #f8f9fa;
  border-radius: 8px;
  transition: all 0.2s;
}

.ranking-item:hover {
  background: #e9ecef;
  transform: translateX(5px);
}

.rank-number {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 18px;
  margin-right: 16px;
  flex-shrink: 0;
}

.rank-1 {
  background: linear-gradient(135deg, #FFD700, #FFA500);
  color: #fff;
  box-shadow: 0 4px 12px rgba(255, 215, 0, 0.3);
}

.rank-2 {
  background: linear-gradient(135deg, #C0C0C0, #A0A0A0);
  color: #fff;
  box-shadow: 0 4px 12px rgba(192, 192, 192, 0.3);
}

.rank-3 {
  background: linear-gradient(135deg, #CD7F32, #B8733E);
  color: #fff;
  box-shadow: 0 4px 12px rgba(205, 127, 50, 0.3);
}

.rank-other {
  background: #7986CB;
  color: #fff;
}

.laptop-info {
  flex: 1;
}

.laptop-name {
  font-size: 16px;
  font-weight: 600;
  color: #263238;
  margin-bottom: 4px;
}

.laptop-score {
  font-size: 14px;
  color: #666;
}

.score-value {
  font-weight: 700;
  color: #7986CB;
}

.empty-ranking {
  text-align: center;
  padding: 40px 20px;
  color: #999;
}

.empty-ranking-icon {
  font-size: 48px;
  margin-bottom: 12px;
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

  .rank-number {
    width: 36px;
    height: 36px;
    font-size: 16px;
    margin-right: 12px;
  }

  .laptop-name {
    font-size: 14px;
  }

  .laptop-score {
    font-size: 13px;
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

    <!-- Ranking Section -->
    <div class="ranking-section">
        <div class="ranking-title">
            <span>🏆</span>
            <span>Top 10 Laptop Terbaik</span>
        </div>
        <div class="ranking-list" id="ranking-list">
            <!-- Rankings will be loaded here -->
            <div class="empty-ranking">
                <div class="empty-ranking-icon">⏳</div>
                <div>Memuat data ranking...</div>
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

            // Update rankings
            updateRankings(data.rankings || []);

            console.log('Dashboard updated successfully');
        })
        .catch(error => {
            console.error('Error loading dashboard data:', error);
            document.getElementById('ranking-list').innerHTML = `
                <div class="empty-ranking">
                    <div class="empty-ranking-icon">❌</div>
                    <div>Gagal memuat data: ${error.message}</div>
                </div>
            `;
        });
}

function updateRankings(rankings) {
    const rankingList = document.getElementById('ranking-list');

    if (!rankings || rankings.length === 0) {
        rankingList.innerHTML = `
            <div class="empty-ranking">
                <div class="empty-ranking-icon">📊</div>
                <div>Belum ada data ranking. Silakan lakukan perhitungan SAW terlebih dahulu.</div>
            </div>
        `;
        return;
    }

    let html = '';
    rankings.forEach((item, index) => {
        const rankClass = index === 0 ? 'rank-1' :
                         index === 1 ? 'rank-2' :
                         index === 2 ? 'rank-3' : 'rank-other';

        const rankNumber = item.ranking || (index + 1);
        const score = parseFloat(item.nilai_preferensi || 0).toFixed(2);

        html += `
            <div class="ranking-item">
                <div class="rank-number ${rankClass}">${rankNumber}</div>
                <div class="laptop-info">
                    <div class="laptop-name">${item.nama_alternatif || 'Unknown'}</div>
                    <div class="laptop-score">
                        Skor Preferensi: <span class="score-value">${score}</span>
                    </div>
                </div>
            </div>
        `;
    });

    rankingList.innerHTML = html;
}

// Load data when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, starting data load...');
    loadDashboardData();
});
</script>

<?=view('footer_view'); ?>