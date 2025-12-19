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

<!-- content page title -->
<div class="container-fluid bg-light-opac mb-4 main-container">
    <div class="row">
        <div class="container-fluid my-3 main-container">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="content-color-primary page-title">Sistem Pendukung Keputusan</h2>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- content page title ends -->



<div class="container-fluid main-container">
  <div class="dashboard-card">
    <div class="top-area">
      <div class="metrics-grid">
        <div class="metric-box">
          <div class="metric-label">Total Cabang</div>
          <div class="metric-value" id="total_cabang">50</div>
        </div>
        <div class="metric-box">
          <div class="metric-label">Total Anggota</div>
          <div class="metric-value" id="total_anggota">5,000</div>
        </div>
        <div class="metric-box">
          <div class="metric-label">Total Konsultan/Fellowship</div>
          <div class="metric-value" id="total_konsultan">500</div>
        </div>
        <div class="metric-box">
          <div class="metric-label">Total RS THT</div>
          <div class="metric-value" id="total_rs">200</div>
        </div>
      </div>

      <div class="pie-box">
        <div class="dashboard-subtitle">Perbandingan Konsultan vs Anggota</div>
        <div id="konsultan_vs_anggota_chart" style="width:100%; height:250px;"></div>
      </div>
    </div>

    <div class="bar-box">
      <div class="dashboard-subtitle">Jumlah anggota per provinsi</div>
      <div id="anggota_per_provinsi_chart" style="width:100%; height:450px;"></div>
    </div>
  </div>
</div>

<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

<script type="text/javascript" src="<?=$base_url?>assets/helper/tanggal.js"></script>

<script>

    $(document).ready(function () {

        $.ajax({
            url: "<?=$base_url?>dashboard/data",
            type: "GET",
            dataType: "JSON",
            async: false,
            success: function (data) {
                console.log(data);

                document.getElementById("total_cabang").innerHTML = addFormat(data.cabang);
                document.getElementById("total_anggota").innerHTML = addFormat(data.anggota);
                document.getElementById("total_konsultan").innerHTML = addFormat(data.konsultan);
                document.getElementById("total_rs").innerHTML = addFormat(data.rs);

                barChart(data.bar);
                pieChart(data.pie);

            },
        });
    });


    function barChart(anggotaProvinsiData){
        // Bar chart anggota per provinsi
        am4core.useTheme(am4themes_animated);
        var chart = am4core.create("anggota_per_provinsi_chart", am4charts.XYChart);
        chart.data = anggotaProvinsiData;
        var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "provinsi";
        categoryAxis.renderer.grid.template.location = 0;
        categoryAxis.renderer.minGridDistance = 30;

        // Konfigurasi label provinsi agar semua terlihat jelas
        categoryAxis.renderer.labels.template.rotation = -45;
        categoryAxis.renderer.labels.template.horizontalCenter = "right";
        categoryAxis.renderer.labels.template.verticalCenter = "middle";
        categoryAxis.renderer.labels.template.fontSize = 10;
        categoryAxis.renderer.labels.template.maxWidth = 200;
        categoryAxis.renderer.labels.template.wrap = true;
        categoryAxis.renderer.labels.template.textAlign = "end";
        categoryAxis.renderer.labels.template.disabled = false;
        categoryAxis.renderer.labels.template.truncate = false;
        categoryAxis.renderer.labels.template.hideOversized = false;
        categoryAxis.renderer.labels.template.inside = false;
        categoryAxis.renderer.minGridDistance = 20; // kurangi jarak minimum antar grid
        categoryAxis.renderer.labels.template.paddingBottom = 10;
        categoryAxis.renderer.labels.template.paddingTop = 5;

        // Tambahkan margin untuk ruang label
        chart.paddingBottom = 80;

        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        var series = chart.series.push(new am4charts.ColumnSeries());
        series.dataFields.valueY = "jumlah";
        series.dataFields.categoryX = "provinsi";
        series.name = "Jumlah";
        series.columns.template.tooltipText = "{categoryX}: [bold]{valueY}[/]";
        series.columns.template.fillOpacity = .8;
    }

    function pieChart(pieChartData){

        // Pie chart konsultan vs anggota
        var pieChart = am4core.create("konsultan_vs_anggota_chart", am4charts.PieChart);
        pieChart.data = pieChartData;
        var pieSeries = pieChart.series.push(new am4charts.PieSeries());
        pieSeries.dataFields.value = "value";
        pieSeries.dataFields.category = "category";
        pieSeries.slices.template.tooltipText = "{category}: {value}";
        pieChart.legend = new am4charts.Legend();

    }

</script>

<?=view('foother_view'); ?>