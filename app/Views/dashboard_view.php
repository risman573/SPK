<?=view('header_view'); ?>

<style>
.outer-wrapper{
  margin: 5px 15px;
  padding: 25px 15px;
  background: #eee;
  min-width: 50px;
}

.column-wrapper{
  height: 110px;
  width: 30px;
  background: #CFD8DC;
  transform: rotate(180deg);
  margin: 0 auto;
}


.percentage, .value{
  margin-top: 10px;
  padding: 5px 1px;
  color: #FFF;
  background: #263238;
  position: relative;
  border-radius: 4px;
  text-align: center;
}

.value{
  background: #7986CB;
}

.dashboard-card {
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(44,62,80,0.08);
  padding: 24px 18px;
  margin-bottom: 18px;
  text-align: center;
}
.dashboard-title {
  font-size: 22px;
  font-weight: 600;
  margin-bottom: 10px;
}
.dashboard-subtitle {
  font-size: 15px;
  color: #888;
  margin-bottom: 18px;
}
.dashboard-metrics {
  display: flex;
  gap: 18px;
  justify-content: center;
  margin-bottom: 24px;
}
.metric-box {
  flex: 1;
  background: #f5f7fa;
  border-radius: 6px;
  padding: 18px 8px;
  min-width: 110px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}
.metric-label {
  font-size: 14px;
  color: #666;
  margin-bottom: 6px;
  text-align: center;
}
.metric-value {
  font-size: 34px;
  font-weight: bold;
  color: #263238;
  text-align: center;
  /* center horizontally and vertically */
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100%;
}
.dashboard-charts {
  display: flex;
  gap: 18px;
  justify-content: center;
}
.chart-box {
  background: #f5f7fa;
  border-radius: 6px;
  padding: 18px 8px;
  min-width: 220px;
  min-height: 220px;
}
.chart-box:first-child {
  flex-basis: 70%;
}
.chart-box:last-child {
  flex-basis: 30%;
}
/* layout for top metrics + pie and full-width bar below */
.top-area{
  display: grid;
  grid-template-columns: 2fr 1fr; /* left: metrics 2x2 area, right: pie chart */
  gap: 18px;
  align-items: stretch;
}
.metrics-grid{
  display: grid;
  grid-template-columns: 1fr 1fr; /* 2 columns */
  grid-auto-rows: 1fr;
  gap: 12px;
}
.pie-box{
  background: #f5f7fa;
  border-radius: 6px;
  padding: 18px 8px;
  display: flex;
  flex-direction: column;
  justify-content: center;
}
.bar-box{
  margin-top: 18px;
  background: #f5f7fa;
  border-radius: 6px;
  padding: 18px 8px;
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
  <!-- Kriteria Section -->
  <div class="dashboard-card">
    <h3 class="dashboard-title">📋 Kriteria Penilaian</h3>
    <div class="dashboard-metrics" style="flex-wrap: wrap;">
      <div class="metric-box" style="flex: 0 1 calc(50% - 9px);">
        <div class="metric-label">💻 Processor</div>
        <div class="metric-value" style="font-size: 24px;">30%</div>
        <div style="font-size: 12px; color: #999; margin-top: 5px;">Benefit (↑)</div>
      </div>
      <div class="metric-box" style="flex: 0 1 calc(50% - 9px);">
        <div class="metric-label">🧠 RAM</div>
        <div class="metric-value" style="font-size: 24px;">25%</div>
        <div style="font-size: 12px; color: #999; margin-top: 5px;">Benefit (↑)</div>
      </div>
      <div class="metric-box" style="flex: 0 1 calc(50% - 9px);">
        <div class="metric-label">💾 Storage</div>
        <div class="metric-value" style="font-size: 24px;">20%</div>
        <div style="font-size: 12px; color: #999; margin-top: 5px;">Benefit (↑)</div>
      </div>
      <div class="metric-box" style="flex: 0 1 calc(50% - 9px);">
        <div class="metric-label">🔋 Battery</div>
        <div class="metric-value" style="font-size: 24px;">15%</div>
        <div style="font-size: 12px; color: #999; margin-top: 5px;">Benefit (↑)</div>
      </div>
      <div class="metric-box" style="flex: 0 1 calc(50% - 9px);">
        <div class="metric-label">💰 Harga</div>
        <div class="metric-value" style="font-size: 24px;">10%</div>
        <div style="font-size: 12px; color: #999; margin-top: 5px;">Cost (↓)</div>
      </div>
    </div>
  </div>

  <!-- Alternatives Section -->
  <div class="dashboard-card">
    <h3 class="dashboard-title">🎯 Data Laptop Alternatif</h3>
    <div class="bar-box" style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
        <thead>
          <tr style="background: #263238; color: white;">
            <th style="padding: 12px; text-align: left;">Laptop</th>
            <th style="padding: 12px; text-align: left;">Processor</th>
            <th style="padding: 12px; text-align: center;">RAM (GB)</th>
            <th style="padding: 12px; text-align: center;">Storage (GB)</th>
            <th style="padding: 12px; text-align: center;">Battery (Jam)</th>
            <th style="padding: 12px; text-align: center;">Harga (Juta)</th>
          </tr>
        </thead>
        <tbody>
          <tr style="border-bottom: 1px solid #ddd;">
            <td style="padding: 12px; font-weight: 600;">MacBook Pro 14"</td>
            <td style="padding: 12px;">M3 Pro</td>
            <td style="padding: 12px; text-align: center;">18</td>
            <td style="padding: 12px; text-align: center;">512</td>
            <td style="padding: 12px; text-align: center;">16</td>
            <td style="padding: 12px; text-align: center;">25</td>
          </tr>
          <tr style="border-bottom: 1px solid #ddd; background: #f9f9f9;">
            <td style="padding: 12px; font-weight: 600;">Dell XPS 13</td>
            <td style="padding: 12px;">Intel i7-1360P</td>
            <td style="padding: 12px; text-align: center;">16</td>
            <td style="padding: 12px; text-align: center;">512</td>
            <td style="padding: 12px; text-align: center;">13</td>
            <td style="padding: 12px; text-align: center;">18</td>
          </tr>
          <tr style="border-bottom: 1px solid #ddd;">
            <td style="padding: 12px; font-weight: 600;">ASUS ROG Gaming</td>
            <td style="padding: 12px;">Intel i9-13900H</td>
            <td style="padding: 12px; text-align: center;">32</td>
            <td style="padding: 12px; text-align: center;">1024</td>
            <td style="padding: 12px; text-align: center;">8</td>
            <td style="padding: 12px; text-align: center;">30</td>
          </tr>
          <tr style="border-bottom: 1px solid #ddd; background: #f9f9f9;">
            <td style="padding: 12px; font-weight: 600;">HP Pavilion 15</td>
            <td style="padding: 12px;">AMD Ryzen 5</td>
            <td style="padding: 12px; text-align: center;">8</td>
            <td style="padding: 12px; text-align: center;">256</td>
            <td style="padding: 12px; text-align: center;">10</td>
            <td style="padding: 12px; text-align: center;">10</td>
          </tr>
          <tr style="border-bottom: 1px solid #ddd;">
            <td style="padding: 12px; font-weight: 600;">Lenovo ThinkPad</td>
            <td style="padding: 12px;">Intel i5-1340P</td>
            <td style="padding: 12px; text-align: center;">16</td>
            <td style="padding: 12px; text-align: center;">512</td>
            <td style="padding: 12px; text-align: center;">14</td>
            <td style="padding: 12px; text-align: center;">15</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Result Section -->
  <div class="dashboard-card">
    <h3 class="dashboard-title">🏆 Hasil Penilaian SAW</h3>

    <!-- Top 3 Ranking Cards -->
    <div class="dashboard-metrics" style="gap: 24px; margin-bottom: 30px;">
      <!-- Rank 1 -->
      <div class="pie-box" style="background: linear-gradient(135deg, #ffd89b, #19547b); color: white; flex: 1; min-width: 150px; padding: 24px;">
        <div style="font-size: 48px; font-weight: bold; opacity: 0.3; margin-bottom: 10px;">1</div>
        <div style="font-size: 18px; font-weight: 700; margin-bottom: 8px;">MacBook Pro 14"</div>
        <div style="font-size: 32px; font-weight: bold; margin-bottom: 5px;">0.8245</div>
        <div style="font-size: 12px; opacity: 0.9; text-transform: uppercase; letter-spacing: 1px;">Terbaik</div>
        <div style="height: 4px; background: rgba(255,255,255,0.3); border-radius: 2px; margin-top: 12px; overflow: hidden;">
          <div style="height: 100%; background: rgba(255,255,255,0.8); width: 100%; border-radius: 2px;"></div>
        </div>
      </div>

      <!-- Rank 2 -->
      <div class="pie-box" style="background: linear-gradient(135deg, #c9d6ff, #e2e2e2); color: #333; flex: 1; min-width: 150px; padding: 24px;">
        <div style="font-size: 48px; font-weight: bold; opacity: 0.3; margin-bottom: 10px;">2</div>
        <div style="font-size: 18px; font-weight: 700; margin-bottom: 8px;">Dell XPS 13</div>
        <div style="font-size: 32px; font-weight: bold; margin-bottom: 5px; color: #7986CB;">0.7562</div>
        <div style="font-size: 12px; color: #666; text-transform: uppercase; letter-spacing: 1px;">Rekomendasi</div>
        <div style="height: 4px; background: #ddd; border-radius: 2px; margin-top: 12px; overflow: hidden;">
          <div style="height: 100%; background: #7986CB; width: 91%; border-radius: 2px;"></div>
        </div>
      </div>

      <!-- Rank 3 -->
      <div class="pie-box" style="background: linear-gradient(135deg, #e0c3fc, #8ec5fc); color: #333; flex: 1; min-width: 150px; padding: 24px;">
        <div style="font-size: 48px; font-weight: bold; opacity: 0.3; margin-bottom: 10px;">3</div>
        <div style="font-size: 18px; font-weight: 700; margin-bottom: 8px;">Lenovo ThinkPad</div>
        <div style="font-size: 32px; font-weight: bold; margin-bottom: 5px; color: #7986CB;">0.6834</div>
        <div style="font-size: 12px; color: #666; text-transform: uppercase; letter-spacing: 1px;">Alternatif</div>
        <div style="height: 4px; background: #ddd; border-radius: 2px; margin-top: 12px; overflow: hidden;">
          <div style="height: 100%; background: #7986CB; width: 83%; border-radius: 2px;"></div>
        </div>
      </div>
    </div>

    <!-- Full Ranking Table -->
    <div class="bar-box">
      <h4 style="font-size: 16px; font-weight: 600; margin-bottom: 15px;">📊 Semua Hasil Penilaian</h4>
      <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
        <thead>
          <tr style="background: #263238; color: white;">
            <th style="padding: 12px; text-align: center;">Ranking</th>
            <th style="padding: 12px; text-align: left;">Laptop</th>
            <th style="padding: 12px; text-align: center;">Skor SAW</th>
            <th style="padding: 12px; text-align: center;">Visualisasi</th>
          </tr>
        </thead>
        <tbody>
          <tr style="border-bottom: 1px solid #ddd;">
            <td style="padding: 12px; text-align: center; font-weight: 700; font-size: 18px;">🥇 1</td>
            <td style="padding: 12px; font-weight: 600;">MacBook Pro 14"</td>
            <td style="padding: 12px; text-align: center; font-weight: 600; color: #7986CB;">0.8245</td>
            <td style="padding: 12px;">
              <div style="height: 6px; background: #e0e0e0; border-radius: 3px; overflow: hidden;">
                <div style="height: 100%; background: #7986CB; width: 100%; border-radius: 3px;"></div>
              </div>
            </td>
          </tr>
          <tr style="border-bottom: 1px solid #ddd; background: #f9f9f9;">
            <td style="padding: 12px; text-align: center; font-weight: 700; font-size: 18px;">🥈 2</td>
            <td style="padding: 12px; font-weight: 600;">Dell XPS 13</td>
            <td style="padding: 12px; text-align: center; font-weight: 600; color: #7986CB;">0.7562</td>
            <td style="padding: 12px;">
              <div style="height: 6px; background: #e0e0e0; border-radius: 3px; overflow: hidden;">
                <div style="height: 100%; background: #7986CB; width: 91%; border-radius: 3px;"></div>
              </div>
            </td>
          </tr>
          <tr style="border-bottom: 1px solid #ddd;">
            <td style="padding: 12px; text-align: center; font-weight: 700; font-size: 18px;">🥉 3</td>
            <td style="padding: 12px; font-weight: 600;">Lenovo ThinkPad</td>
            <td style="padding: 12px; text-align: center; font-weight: 600; color: #7986CB;">0.6834</td>
            <td style="padding: 12px;">
              <div style="height: 6px; background: #e0e0e0; border-radius: 3px; overflow: hidden;">
                <div style="height: 100%; background: #7986CB; width: 83%; border-radius: 3px;"></div>
              </div>
            </td>
          </tr>
          <tr style="border-bottom: 1px solid #ddd; background: #f9f9f9;">
            <td style="padding: 12px; text-align: center; font-weight: 700;">4</td>
            <td style="padding: 12px; font-weight: 600;">ASUS ROG Gaming</td>
            <td style="padding: 12px; text-align: center; font-weight: 600; color: #7986CB;">0.6102</td>
            <td style="padding: 12px;">
              <div style="height: 6px; background: #e0e0e0; border-radius: 3px; overflow: hidden;">
                <div style="height: 100%; background: #7986CB; width: 74%; border-radius: 3px;"></div>
              </div>
            </td>
          </tr>
          <tr style="border-bottom: 1px solid #ddd;">
            <td style="padding: 12px; text-align: center; font-weight: 700;">5</td>
            <td style="padding: 12px; font-weight: 600;">HP Pavilion 15</td>
            <td style="padding: 12px; text-align: center; font-weight: 600; color: #7986CB;">0.4521</td>
            <td style="padding: 12px;">
              <div style="height: 6px; background: #e0e0e0; border-radius: 3px; overflow: hidden;">
                <div style="height: 100%; background: #7986CB; width: 55%; border-radius: 3px;"></div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?=view('footer_view'); ?>