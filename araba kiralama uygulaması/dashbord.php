<?php
include("baglanti.php");
session_start();

if (!isset($_SESSION['musteri_id'])) {
    header("Location: ana_sayfa.php");
    exit;
}

$musteri_id = $_SESSION['musteri_id'];

$userQ = $baglan->query("SELECT ad_soyad FROM musteriler WHERE id = $musteri_id");
$user  = $userQ->fetch_assoc()['ad_soyad'];

$yrsRes = $baglan->query("
  SELECT DISTINCT YEAR(r.teslim_alinacak_tarih) AS yr
  FROM rezervasyon r
  WHERE r.musteri_id = $musteri_id
  ORDER BY yr DESC
");
$years = [];
while ($r = $yrsRes->fetch_assoc()) {
    $years[] = $r['yr'];
}
if (empty($years)) {
    $years[] = date('Y');
}
$currentYear = date('Y');

$allData = [];
foreach ($years as $yr) {
    $q1 = $baglan->query("
      SELECT CONCAT(a.marka, ' ', a.model) AS label, COUNT(*) AS value
      FROM rezervasyon r
      JOIN arac_detay ad ON r.arac_detay_id = ad.id
      JOIN araclar a     ON ad.arac_id = a.id
      WHERE r.musteri_id = $musteri_id
        AND YEAR(r.teslim_alinacak_tarih) = $yr
      GROUP BY a.id
    ");
    $l1 = []; $d1 = [];
    while ($row = $q1->fetch_assoc()) {
        $l1[] = $row['label'];
        $d1[] = (int) $row['value'];
    }

    $tmp2 = array_fill(1, 12, 0);
    $q2 = $baglan->query("
      SELECT MONTH(r.teslim_alinacak_tarih) AS ay, COUNT(*) AS adet
      FROM rezervasyon r
      WHERE r.musteri_id = $musteri_id
        AND YEAR(r.teslim_alinacak_tarih) = $yr
      GROUP BY MONTH(r.teslim_alinacak_tarih)
    ");
    while ($row = $q2->fetch_assoc()) {
        $tmp2[(int)$row['ay']] = (int)$row['adet'];
    }
    $l2 = array_keys($tmp2);
    $d2 = array_values($tmp2);

    $tmp3 = array_fill(1, 12, 0.0);
    $q3 = $baglan->query("
      SELECT MONTH(o.odeme_tarihi) AS ay, SUM(o.toplam_fiyat) AS tutar
      FROM odeme o
      JOIN rezervasyon r ON o.id = r.odeme_id
      WHERE r.musteri_id = $musteri_id
        AND YEAR(o.odeme_tarihi) = $yr
      GROUP BY MONTH(o.odeme_tarihi)
    ");
    while ($row = $q3->fetch_assoc()) {
        $tmp3[(int)$row['ay']] = (float)$row['tutar'];
    }
    $l3 = array_keys($tmp3);
    $d3 = array_values($tmp3);

    $q4 = $baglan->query("
      SELECT CONCAT(a.marka, ' ', a.model) AS label, SUM(o.toplam_fiyat) AS value
      FROM odeme o
      JOIN rezervasyon r   ON o.id = r.odeme_id
      JOIN arac_detay ad   ON r.arac_detay_id = ad.id
      JOIN araclar a       ON ad.arac_id = a.id
      WHERE r.musteri_id = $musteri_id
        AND YEAR(o.odeme_tarihi) = $yr
      GROUP BY a.id
    ");
    $l4 = []; $d4 = [];
    while ($row = $q4->fetch_assoc()) {
        $l4[] = $row['label'];
        $d4[] = (float) $row['value'];
    }

    $allData[$yr] = [
      'g1' => ['labels' => $l1, 'data' => $d1],
      'g2' => ['labels' => $l2, 'data' => $d2],
      'g3' => ['labels' => $l3, 'data' => $d3],
      'g4' => ['labels' => $l4, 'data' => $d4],
    ];
}

$upQ   = $baglan->query("
    SELECT r.arac_detay_id,
           a.marka, a.model,
           r.teslim_alinacak_tarih,
           r.teslim_edilecek_tarih
    FROM rezervasyon r
    JOIN arac_detay ad ON r.arac_detay_id = ad.id
    JOIN araclar a     ON ad.arac_id = a.id
    WHERE r.musteri_id = $musteri_id
      AND r.teslim_alinacak_tarih > CURDATE()
    ORDER BY r.teslim_alinacak_tarih
");

$actQ  = $baglan->query("
    SELECT r.arac_detay_id,
           a.marka, a.model,
           r.teslim_alinacak_tarih,
           r.teslim_edilecek_tarih
    FROM rezervasyon r
    JOIN arac_detay ad ON r.arac_detay_id = ad.id
    JOIN araclar a     ON ad.arac_id = a.id
    WHERE r.musteri_id = $musteri_id
      AND r.teslim_alinacak_tarih <= CURDATE()
      AND r.teslim_edilecek_tarih >= CURDATE()
    ORDER BY r.teslim_alinacak_tarih
");

$histQ = $baglan->query("
    SELECT r.arac_detay_id,
           a.marka, a.model,
           r.teslim_alinacak_tarih,
           r.teslim_edilecek_tarih
    FROM rezervasyon r
    JOIN arac_detay ad ON r.arac_detay_id = ad.id
    JOIN araclar a     ON ad.arac_id = a.id
    WHERE r.musteri_id = $musteri_id
      AND r.teslim_edilecek_tarih < CURDATE()
    ORDER BY r.teslim_edilecek_tarih DESC
");
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Dashboard</title>
    
     <link rel="stylesheet" href="css/basics.css">
    
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
  <style>
    .container {
      flex: 1;
      max-width: 1200px;
      margin: 20px auto;
      padding: 0 15px;
    }
      
    .quick-actions {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
      margin-bottom: 30px;
    }
      
    .quick-card {
      background: #fff;
      flex: 1;
      min-width: 240px;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      display: flex;
      flex-direction: column;
    }
      
    .quick-card.upcoming {
      border-top: 6px solid #e74c3c;
    }
      
    .quick-card.active {
      border-top: 6px solid #2ecc71;
    }
      
    .quick-card.past {
      border-top: 6px solid #3498db;
    }
      
    .quick-card h3 {
      margin: 0;
      padding: 12px;
      font-size: 1.2em;
      color: #333;
      text-align: center;
      background: #fafafa;
      border-bottom: 1px solid #eee;
    }
      
    .table-wrapper {
      flex: 1;
      overflow-y: auto;
      max-height: 200px;
    }
      
    .mini-table {
      width: 100%;
      border-collapse: collapse;
      table-layout: fixed;
    }
      
    .mini-table th,   .mini-table td {
      padding: 8px;
      text-align: center;
      border-bottom: 1px solid #eee;
      font-size: 0.95em;
      color: #555;
    }
      
    .mini-table th {
      background: #f0f0f0;
      font-weight: 600;
    }
      
    .mini-table tr:nth-child(even) {
      background: #fafafa;
    }
      
    .mini-table tr:hover {
      background: #f2f2f2;
      cursor: pointer;
    }
      
    .chart-section {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      margin-bottom: 30px;
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }
      
    .chart-controls {
      display: flex;
      flex-direction: column;
      gap: 10px;
      min-width: 160px;
    }
      
    .chart-btn {
      background: #3183e0;
      color: #fff;
      border: none;
      padding: 10px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 0.95em;
      transition: background .2s;
    }
      
    .chart-btn:hover {
      background: #2a6ba5;
    }
      
    .chart-area {
      flex: 1;
      min-width: 300px;
    }
      
    .year-select {
      margin-top: 10px;
      text-align: center;
    }
      
    @media(max-width: 768px) {
      .quick-actions,
      .chart-section {
        flex-direction: column;
      }
    }
      
  </style>
</head>
<body>
  <header>
    <a href="ana_sayfa_uye.php"><h1>Araba Kiralama Hizmeti</h1></a>
    <?php
    if (isset($_SESSION['musteri_id'])) {
        $musteri_id = $_SESSION['musteri_id'];
        $sql = "SELECT ad_soyad FROM musteriler WHERE id = $musteri_id";
        $result = mysqli_query($baglan, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $ad_soyad = $row['ad_soyad'];
            echo '<a href="dashbord.php" class="login-button">' . htmlspecialchars($ad_soyad) . '</a>';
            echo '</div>';
        }
    }
    ?>
    <a href="ana_sayfa.php" class="login-button">Çıkış Yap</a>
  </header>

  <nav>
    <a href="filomuz_uye.php">Filomuz</a>
    <a href="şubelerimiz_uye.php">Şubelerimiz</a>
    <a href="#">Hakkımızda</a>
    <a href="#">İletişim</a>
  </nav>

<div class="container">
  <div class="quick-actions">
    <?php foreach ([
      ['upcoming', 'Gelecek Rezervasyon',    $upQ],
      ['active',   'Aktif Kiralamalar',      $actQ],
      ['past',     'Geçmiş Kiralamalar',      $histQ]
    ] as list($cls, $title, $q)): ?>
      <div class="quick-card <?= htmlspecialchars($cls) ?>">
        <h3><?= htmlspecialchars($title) ?></h3>
        <div class="table-wrapper">
          <table class="mini-table">
            <thead>
              <tr><th>Araç</th><th>Tarihler</th></tr>
            </thead>
            <tbody>
              <?php if ($q && $q->num_rows): ?>
                <?php while ($r = $q->fetch_assoc()): ?>
                  <tr onclick="location.href='arac_detay.php?arac_detay_id=<?= intval($r['arac_detay_id']) ?>'">
                    <td><?= htmlspecialchars($r['marka'].' '.$r['model']) ?></td>
                    <td><?= htmlspecialchars($r['teslim_alinacak_tarih'].' – '.$r['teslim_edilecek_tarih']) ?></td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr><td colspan="2">Yok</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>



    <div class="chart-section">
      <div class="chart-controls">
        <button class="chart-btn" data-type="g1">Rezervasyon Bazlı</button>
        <button class="chart-btn" data-type="g4">Model Harcama</button>
        <button class="chart-btn" data-type="g3">Aylık Harcama</button>
        <button class="chart-btn" data-type="g2">Aylık Rezervasyon</button>
        <div class="year-select">
          <label>Yıl:
            <select id="yearSelect">
              <?php foreach ($years as $y): ?>
                <option value="<?= $y ?>" <?= $y == $currentYear ? 'selected' : '' ?>><?= $y ?></option>
              <?php endforeach; ?>
            </select>
          </label>
        </div>
      </div>
      <div class="chart-area">
        <canvas id="mainChart"></canvas>
      </div>
    </div>
  </div>

  <footer>&copy; 2024 Rent a Car / Tüm hakları saklıdır.</footer>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const allData = <?= json_encode($allData) ?>;
    const chartType = { g1: 'bar', g4: 'bar', g3: 'bar', g2: 'line' };
    let currentMetric = 'g1';
    let currentYear = '<?= $currentYear ?>';

    const ctx = document.getElementById('mainChart').getContext('2d');
    let mainChart = new Chart(ctx, {
      type: chartType[currentMetric],
      data: {
        labels: allData[currentYear][currentMetric].labels,
        datasets: [{
          label: document.querySelector(`[data-type="${currentMetric}"]`).innerText,
          data: allData[currentYear][currentMetric].data,
          backgroundColor: 'rgba(49,131,224,0.6)',
          borderColor: 'rgba(49,131,224,1)',
          borderWidth: 1,
          fill: currentMetric === 'g2' ? false : true
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            ticks: { stepSize: 1 }
          }
        }
      }
    });

    function updateChart() {
      const ds = allData[currentYear][currentMetric];
      mainChart.config.type = chartType[currentMetric];
      mainChart.config.data.labels = ds.labels;
      mainChart.config.data.datasets[0].label = document.querySelector(`[data-type="${currentMetric}"]`).innerText;
      mainChart.config.data.datasets[0].data = ds.data;
      mainChart.config.data.datasets[0].fill = (currentMetric === 'g2');
      mainChart.update();
    }

    document.querySelectorAll('.chart-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        currentMetric = btn.getAttribute('data-type');
        updateChart();
      });
    });
    document.getElementById('yearSelect').addEventListener('change', e => {
      currentYear = e.target.value;
      updateChart();
    });
  </script>
</body>
</html>
