<?php 
session_start();
include("baglanti.php");

if (isset($_GET['action']) && $_GET['action'] === 'get_car' && isset($_GET['arac_id'])) {
    $arac_id = intval($_GET['arac_id']);
    $stmt = $baglan->prepare("
        SELECT vites_tipi, kisi_kapasitesi, bagaj_hacmi, aciklama, fotograf,
               motor_tipi, motor_hacmi, yas_sarti, ehliyet_yasi, fiyat
        FROM araclar
        WHERE id = ?
    ");
    $stmt->bind_param('i', $arac_id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    header('Content-Type: application/json');
    echo json_encode($res);
    exit;
}


if (isset($_GET['action']) && $_GET['action'] === 'get_models' && isset($_GET['marka'])) {
    $marka = $_GET['marka'];
    $stmt = $baglan->prepare("
         SELECT DISTINCT model
         FROM araclar
        WHERE marka = ?
        ORDER BY model ASC
    ");
     $stmt->bind_param('s', $marka);
    $stmt->execute();
    $list = [];
     $res = $stmt->get_result();
    while($row = $res->fetch_assoc()) {
         $list[] = $row['model'];
    }
     header('Content-Type: application/json');
echo json_encode($list);
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'get_specific_id' && isset($_GET['brand_model'])) {
    $bm = trim($_GET['brand_model']);
    $pos = strrpos($bm, ' ');
    if ($pos !== false) {
        $marka = substr($bm, 0, $pos);         
        $model = substr($bm, $pos + 1);       
        $stmt = $baglan->prepare("
            SELECT id 
            FROM araclar
            WHERE marka = ? AND model = ?
            LIMIT 1
        ");
        $stmt->bind_param('ss', $marka, $model);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows > 0) {
            $row = $res->fetch_assoc();
            echo json_encode(['arac_id' => intval($row['id'])]);
        } else {
            echo json_encode(['arac_id' => 0]);
        }
    } else {
        echo json_encode(['arac_id' => 0]);
    }
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'get_delete_info' && isset($_GET['plaka'])) {
    $plaka = trim($_GET['plaka']);
    $q = $baglan->prepare("
        SELECT 
          d.plaka,
          d.on_foto,
          a.marka,
          a.model,
          a.yil,
          d.kilometre,
          d.renk
        FROM arac_detay d
        JOIN araclar a ON d.arac_id = a.id
        WHERE d.plaka = ?
        LIMIT 1
    ");
    $q->bind_param('s', $plaka);
    $q->execute();
    $res = $q->get_result();
    if ($res->num_rows === 0) {
        header('Content-Type: application/json');
        echo json_encode(['found' => false]);
    } else {
        $row = $res->fetch_assoc();
        header('Content-Type: application/json');
        echo json_encode([
            'found'     => true,
            'marka'     => $row['marka'],
            'model'     => $row['model'],
            'yil'       => intval($row['yil']),
            'kilometre' => intval($row['kilometre']),
            'renk'      => $row['renk'],
            'on_foto'   => $row['on_foto']
        ]);
    }
    exit;
}

if (isset($_POST['action']) && $_POST['action'] === 'delete_car' && isset($_POST['plaka'])) {
    $plaka = trim($_POST['plaka']);
    
    $chk = $baglan->prepare("SELECT id FROM arac_detay WHERE plaka = ?");
    $chk->bind_param('s', $plaka);
    $chk->execute();
    $chk->bind_result($detay_id);
    if (! $chk->fetch()) {
        $chk->close();
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Kayıt bulunamadı.']);
        exit;
    }
    $chk->close();

    $delSubs = $baglan->prepare("DELETE FROM araclar_subeler WHERE arac_detay_id = ?");
    $delSubs->bind_param('i', $detay_id);
    $delSubs->execute();
    $delSubs->close();

    $del = $baglan->prepare("DELETE FROM arac_detay WHERE id = ?");
    $del->bind_param('i', $detay_id);
    $del->execute();
    $ok = $del->affected_rows > 0;
    $del->close();

    header('Content-Type: application/json');
    if ($ok) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Silinirken hata oluştu.']);
    }
    exit;
}


$view   = $_GET['view']   ?? 'dashboard';  
$sub    = $_GET['sub']    ?? 'liste';      
$filter = $_GET['filter'] ?? null;        
$plaka  = $_GET['plaka']  ?? null;     

$yrRes = $baglan->query("
    SELECT DISTINCT YEAR(teslim_alinacak_tarih) AS yr 
    FROM rezervasyon
    UNION
    SELECT DISTINCT YEAR(o.odeme_tarihi) AS yr 
    FROM odeme o
    ORDER BY yr DESC
");
$yearOptions = ['all'=>'Tüm Yıllar'];
while($r = $yrRes->fetch_assoc()){
    $yearOptions[$r['yr']] = $r['yr'];
}

$monthNames = [
  1=>'Ocak',2=>'Şubat',3=>'Mart',4=>'Nisan',5=>'Mayıs',6=>'Haziran',
  7=>'Temmuz',8=>'Ağustos',9=>'Eylül',10=>'Ekim',11=>'Kasım',12=>'Aralık'
];

$raw1 = $baglan->query("
    SELECT YEAR(teslim_alinacak_tarih) AS yr,
           MONTH(teslim_alinacak_tarih) AS mo,
           COUNT(*) AS cnt
    FROM rezervasyon
    GROUP BY YEAR(teslim_alinacak_tarih), MONTH(teslim_alinacak_tarih)
");
$data1 = [];
foreach(array_keys($yearOptions) as $k){
    if($k !== 'all') $data1[$k] = array_fill(1,12,0);
}
$data1['all'] = array_fill(1,12,0);
while($r = $raw1->fetch_assoc()){
    $y = $r['yr']; $m = $r['mo']; $c = $r['cnt'];
    $data1[$y][$m]   = $c;
    $data1['all'][$m] += $c;
}

$raw3 = $baglan->query("
    SELECT YEAR(o.odeme_tarihi) AS yr,
           MONTH(o.odeme_tarihi) AS mo,
           SUM(o.toplam_fiyat) AS total
    FROM odeme o
    JOIN rezervasyon r ON o.id = r.odeme_id
    GROUP BY YEAR(o.odeme_tarihi), MONTH(o.odeme_tarihi)
");
$data3 = [];
foreach(array_keys($yearOptions) as $k){
    if($k !== 'all') $data3[$k] = array_fill(1,12,0);
}
$data3['all'] = array_fill(1,12,0);
while($r = $raw3->fetch_assoc()){
    $y = $r['yr']; $m = $r['mo']; $s = (float)$r['total'];
    $data3[$y][$m]   = $s;
    $data3['all'][$m] += $s;
}

$yrM2 = [];
foreach(array_keys($yearOptions) as $k){
    $yrM2[$k] = ['labels'=>[], 'data'=>[], 'type'=>'bar','label'=>'Model Bazlı Rezervasyon Adedi'];
    $sql = "
      SELECT CONCAT(a.marka,' ',a.model) AS nm, COUNT(*) AS c
      FROM rezervasyon r
      JOIN arac_detay d ON r.arac_detay_id = d.id
      JOIN araclar a     ON d.arac_id = a.id
    " . ($k==='all' ? "" : "WHERE YEAR(r.teslim_alinacak_tarih) = $k") . "
      GROUP BY a.id
    ";
    $q = $baglan->query($sql);
    while($row = $q->fetch_assoc()){
        $yrM2[$k]['labels'][] = $row['nm'];
        $yrM2[$k]['data'][]   = (int)$row['c'];
    }
}

$yrM4 = [];
foreach(array_keys($yearOptions) as $k){
    $yrM4[$k] = ['labels'=>[], 'data'=>[], 'type'=>'bar','label'=>'Model Bazlı Toplam Harcama (TL)'];
    $sql = "
      SELECT CONCAT(a.marka,' ',a.model) AS nm, SUM(o.toplam_fiyat) AS s
      FROM odeme o
      JOIN rezervasyon r   ON o.id = r.odeme_id
      JOIN arac_detay d    ON r.arac_detay_id = d.id
      JOIN araclar a       ON d.arac_id = a.id
    " . ($k==='all' ? "" : "WHERE YEAR(o.odeme_tarihi) = $k") . "
      GROUP BY a.id
    ";
    $q = $baglan->query($sql);
    while($row = $q->fetch_assoc()){
        $yrM4[$k]['labels'][] = $row['nm'];
        $yrM4[$k]['data'][]   = (float)$row['s'];
    }
}

$yrM5 = [];
foreach(array_keys($yearOptions) as $k){
    $yrM5[$k] = ['labels'=>[], 'data'=>[], 'type'=>'bar','label'=>'Şube Bazlı Rezervasyon Adedi'];
    $sql = "
      SELECT CONCAT(s.il,' - ',s.ilce) AS nm, COUNT(*) AS c
      FROM rezervasyon r
      JOIN subeler s ON r.teslim_alinacak_sube_id = s.id
    " . ($k==='all' ? "" : "WHERE YEAR(r.teslim_alinacak_tarih) = $k") . "
      GROUP BY s.id
    ";
    $q = $baglan->query($sql);
    while($row = $q->fetch_assoc()){
        $yrM5[$k]['labels'][] = $row['nm'];
        $yrM5[$k]['data'][]   = (int)$row['c'];
    }
}

$q6 = $baglan->query("
    SELECT AVG(DATEDIFF(teslim_edilecek_tarih, teslim_alinacak_tarih)) AS avgd
    FROM rezervasyon
");
$r6 = $q6->fetch_assoc();
$m6 = [
  'labels'=>['Ortalama Gün'], 
  'data'=>[(float)$r6['avgd']], 
  'type'=>'bar',
  'label'=>'Ortalama Kiralama Süresi (gün)'
];

$m7=$m8=$m9=$m10=null;

$rawMetrics = [
    'm1'=>$data1,
    'm2'=>$yrM2,
    'm3'=>$data3,
    'm4'=>$yrM4,
    'm5'=>$yrM5,
    'm6'=>$m6,
    'm7'=>$m7,
    'm8'=>$m8,
    'm9'=>$m9,
    'm10'=>$m10
];
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Panel</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>

    body {
      margin:0;
      font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      display:flex;
      height:100vh;
      background:#f5f6fa;
    }
      
    .sidebar {
      width:240px; background:#3183e0;
      color:#fff;
      display:flex; 
      flex-direction:column; 
      transition:width .3s;
    }
      
    .sidebar h2 {
      text-align:center;
      margin:20px 0;
      font-size:1.4em;
    }
      
    .sidebar .nav-item {
      position:relative;
    }
      
    .sidebar .nav-item .nav-link {
      padding:12px 20px; 
      color:#fff;
      text-decoration:none;
      display:block; 
      transition:background .2s;
      cursor:pointer;
    }
      
    .sidebar .nav-item.active > .nav-link {
      background:#106db0;
    }
      
    .main {
      flex:1;
      display:flex; 
      flex-direction:column;
    }
      
    header {
      background:#3183e0;
      color:#fff;
      padding:0 20px; 
      height:60px;
      display:flex;
      align-items:center;
      justify-content:space-between;
    }
      
    header h1 {
        margin:0;
        font-size:1.5em; 
    }
      
    header a {
      color:#fff;
      text-decoration:none; 
      background:#106db0;
      padding:6px 12px;
      border-radius:4px; 
      transition:background .2s;
    }
      
    header a:hover { 
        background:#0b5a8e;
    }
      
    .content {
      flex:1;
      padding:20px; 
      overflow:auto;
    }
      
    .section {
        display:none;
    }
      
    .section.active {
        display:block; 
    }

    .filters {
      display:flex;
      gap:20px;
      flex-wrap:wrap;
      margin-bottom:25px;
    }
      
    .filter-group {
      display:flex;
      flex-direction:column;
    }
      
    .filter-group label {
      font-size:0.9em;
      color:#555;
      margin-bottom:6px; 
      text-transform:uppercase;
      letter-spacing:0.5px;
    }
      
    .filter-group select {
      padding:8px 12px;
      border:1px solid #ccc;
      border-radius:20px;
      background:#fff;
      font-size:1em;
      cursor:pointer;
      transition:border-color .2s, box-shadow .2s;
    }
      
    .filter-group select:focus {
      outline:none; 
      border-color:#3183e0;
      box-shadow:0 0 5px rgba(49,131,224,0.3);
    }
      
    .chart-container {
      background:#fff;
      padding:20px;
      border-radius:8px;
      box-shadow:0 2px 8px rgba(0,0,0,0.1);
    }

    .arac-submenu {
      display:flex;
      gap:10px;
      margin-bottom:15px;
      flex-wrap:wrap;
    }
      
    .arac-submenu button {
      padding:8px 16px;
      background:#fff;
      border:1px solid #ccc;
      border-radius:20px;
      cursor:pointer; 
      font-size:0.9em;
      transition:background .2s, border-color .2s;
    }
      
    .arac-submenu button.sub-active {
      background:#3183e0; 
      border-color:#3183e0;
      color:#fff;
    }
      
    .arac-submenu button:hover:not(.sub-active) {
      background:#f0f0f0;
    }

    .cards-grid {
      display:flex;
      flex-wrap:wrap;
      gap:20px;
    }
      
    .cards-grid .card {
      width:calc((100% - 4*20px)/5);
      background:#fff;
      border-radius:8px;
      box-shadow:0 2px 5px rgba(0,0,0,0.1);
      text-align:center;
      overflow:hidden;
      cursor:pointer;
      transition:transform .2s;
    }
      
    .cards-grid .card:hover {
      transform:translateY(-5px);
    }
      
    .cards-grid .card img {
      width:100%;
      height:120px;
      object-fit:cover;
    }
      
    .cards-grid .card .title {
      padding:10px;
      font-weight:bold;
      color:#333;
    }
      
    @media(max-width:1200px){
      .cards-grid .card { 
          width:calc((100% - 3*20px)/4);
        }
    }
      
    @media(max-width:900px){
      .cards-grid .card {
          width:calc((100% - 2*20px)/3); 
        }
    }
      
    @media(max-width:600px){
      .cards-grid .card {
          width:calc((100% - 1*20px)/2); 
        }
    }

    .back-btn {
      display:inline-block;
      padding:4px 8px;
      background:#3183e0; 
      color:#fff;
      border-radius:4px;
      text-decoration:none;
      font-weight:bold;
      transition:background .2s;
    }
      
    .back-btn:hover {
        background:#106db0; 
    }
      
    .detail-flex {
      display:flex;
      gap:20px;
      flex-wrap:wrap;
    }
      
    .gallery {
      flex:0 0 700px;
    }
      
    .main-frame {
      width:700px;
      height:500px;
      overflow:hidden;
      border-radius:4px;
      box-shadow:0 2px 6px rgba(0,0,0,0.2);
    }
      
    .main-frame img {
      width:100%;
      height:100%;
      object-fit:contain;
    }
      
    .thumbs {
      display:flex;
      gap:10px;
      overflow-x:auto; 
      margin-top:10px;
    }
      
    .thumbs img {
      width:100px;
      height:100px;
      object-fit:contain;
      border-radius:4px;
      cursor:pointer;
      box-shadow:0 1px 4px rgba(0,0,0,0.2);
      transition:transform .2s, box-shadow .2s;
    }
      
    .thumbs img:hover {
      transform:scale(1.1);
      box-shadow:0 2px 8px rgba(0,0,0,0.3);
    }
      
    .detail-table {
      flex:1; 
      border-collapse:collapse;
      background:#fff;
      box-shadow:0 2px 8px rgba(0,0,0,0.1);
      border-radius:4px;
      overflow:hidden; 
      width:100%;
    }
      
    .detail-table tr.odd {
        background:#fafafa; 
    }
      
    .detail-table tr.even {
        background:#fff;
    }
      
    .detail-table th, .detail-table td {
      padding:12px 15px;
    }
      
    .detail-table th {
      text-align:left;
      background:#3183e0;
      color:#fff;
      width:30%; 
      font-weight:normal;
    }

    .input-group {
      margin-bottom:15px;
    }
      
    .input-group label {
      display:block;
      margin-bottom:5px;
      font-weight:bold;
    }
      
    .input-group select, .input-group input, .input-group textarea {
      width:100%;
      padding:8px;
      border:1px solid #ccc;
      border-radius:4px;
      box-sizing:border-box;
      transition:border-color .2s;
    }
      
    .input-group select:focus, .input-group input:focus, .input-group textarea:focus {
      outline:none;
      border-color:#3498db;
    }
      
    .input-group input[disabled], .input-group textarea[disabled] {
      background:#eee;
      color:#777; 
      cursor:not-allowed;
    }

#rezervasyon table th, #rezervasyon table td {
  padding: 8px;
  border: 1px solid #ddd;
  text-align: left;
  font-size: 0.9rem;
}
      
#rezervasyon table th {
  background: #3183e0;
  color: #fff;
  font-weight: normal;
  font-size: 0.95rem;
}
      
#rezervasyon .table-wrapper {
  overflow-x: auto;
  margin-top: 10px;
}

  </style>
</head>
<body>

<!-- ---------- SIDEBAR ---------- -->
  <div class="sidebar">
    <div class="nav-item <?= $view==='dashboard'    ? 'active' : '' ?>">
      <a href="?view=dashboard" class="nav-link">Dashboard</a>
    </div>
    <div class="nav-item <?= $view==='araclar'       ? 'active' : '' ?>">
      <div class="nav-link" onclick="location.href='?view=araclar&sub=liste'">Araçlar</div>
    </div>
    <div class="nav-item <?= $view==='rezervasyon'   ? 'active' : '' ?>">
      <a href="?view=rezervasyon" class="nav-link">Rezervasyon</a>
    </div>
  </div>

  <div class="main">
    <header>
      <h1>Yönetici Kontrol Paneli</h1>
      <a href="ana_sayfa.php">Çıkış Yap</a>
    </header>

    <div class="content">
      <!-- ---------- DASHBOARD BÖLÜMÜ ---------- -->
      <div id="dashboard" class="section<?= $view==='dashboard' ? ' active' : '' ?>">
        <h2>Dashboard</h2>
        <div class="filters">
          <div class="filter-group">
            <label for="yearSel">Yıl</label>
            <select id="yearSel">
              <?php foreach($yearOptions as $k=>$v): ?>
                <option value="<?=$k?>"><?=$v?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="filter-group">
            <label for="metricSel">Metrik</label>
            <select id="metricSel">
              <option value="m1">Aylık Rezervasyon Adedi</option>
              <option value="m2">Model Bazlı Rezervasyon Adedi</option>
              <option value="m3">Aylık Toplam Harcama (TL)</option>
              <option value="m4">Model Bazlı Toplam Harcama (TL)</option>
              <option value="m5">Şube Bazlı Rezervasyon Adedi</option>
              <option value="m6">Ortalama Kiralama Süresi (gün)</option>
              <option value="m7" data-unimpl>— Müşteri Segmentleri</option>
              <option value="m8" data-unimpl>— İptal Oranları</option>
              <option value="m9" data-unimpl>— Bakım Maliyetleri</option>
              <option value="m10" data-unimpl>— Doluluk Oranı</option>
            </select>
          </div>
        </div>
        <div class="chart-container">
          <canvas id="dashChart"></canvas>
        </div>
      </div>

      <!-- ---------- ARAÇLAR BÖLÜMÜ ---------- -->
      <div id="araclar" class="section<?= $view==='araclar' ? ' active' : '' ?>">
        <h2>Araçlar</h2>

        <div class="arac-submenu">
          <button data-sub="liste"  class="<?= $sub==='liste' ? 'sub-active' : '' ?>">Araç Listesi</button>
          <button data-sub="ekle"   class="<?= $sub==='ekle'   ? 'sub-active' : '' ?>">Araç Ekle</button>
          <button data-sub="sil"    class="<?= $sub==='sil'    ? 'sub-active' : '' ?>">Araç Sil</button>
        </div>

        <!-- ---------- Araç Listesi ---------- -->
        <?php if($sub==='liste'): ?>
          <?php if (!$filter): ?>
            <div class="cards-grid">
              <?php
              $pq = $baglan->query("
                SELECT CONCAT(a.marka,' ',a.model) AS mm,
                       MIN(d.on_foto)                AS foto
                FROM araclar a
                JOIN arac_detay d ON a.id=d.arac_id
                GROUP BY a.id
                ORDER BY a.marka ASC, a.model ASC
              ");
              while($p = $pq->fetch_assoc()):
              ?>
                <div class="card" onclick="location.href='?view=araclar&sub=liste&filter=<?= urlencode($p['mm']) ?>'">
                  <img src="<?= htmlspecialchars($p['foto']) ?>" alt="Kapak">
                  <div class="title"><?= htmlspecialchars($p['mm']) ?></div>
                </div>
              <?php endwhile; ?>
            </div>

          <?php elseif (!$plaka): ?>
            <p>
              <a href="?view=araclar&sub=liste" class="back-btn">← Geri</a>
              <strong>Filtre:</strong> <?= htmlspecialchars($filter) ?>
            </p>
            <div class="cards-grid">
              <?php
              $fq = $baglan->prepare("
                SELECT d.plaka, d.on_foto
                FROM arac_detay d
                JOIN araclar a ON d.arac_id=a.id
                WHERE CONCAT(a.marka,' ',a.model)=?
                ORDER BY d.plaka ASC
              ");
              $fq->bind_param('s', $filter);
              $fq->execute();
              $res = $fq->get_result();
              while($r = $res->fetch_assoc()):
              ?>
                <div class="card" onclick="location.href='?view=araclar&sub=liste&filter=<?= urlencode($filter) ?>&plaka=<?= urlencode($r['plaka']) ?>'">
                  <img src="<?= htmlspecialchars($r['on_foto']) ?>" alt="Kapak">
                  <div class="title"><?= htmlspecialchars($r['plaka']) ?></div>
                </div>
              <?php endwhile; ?>
            </div>

<?php else: ?>
  <p>
    <a href="?view=araclar&sub=liste&filter=<?= urlencode($filter) ?>" class="back-btn">← Geri</a>
    <strong>Filtre:</strong> <?= htmlspecialchars($filter) ?> /
    <strong>Plaka:</strong> <?= htmlspecialchars($plaka) ?>
  </p>
  <?php
    $dq = $baglan->prepare("
      SELECT 
        a.marka,
        a.model,
        a.yil,
        a.motor_hacmi,
        a.motor_tipi,
        a.vites_tipi,
        d.kilometre,
        d.renk,
        a.bagaj_hacmi,
        a.kisi_kapasitesi,
        a.aciklama,
        s.il,
        s.ilce,
        d.on_foto,
        d.arka_foto,
        d.ic_foto,
        d.sag_foto,
        d.sol_foto
      FROM arac_detay d
      JOIN araclar a ON d.arac_id = a.id
      LEFT JOIN araclar_subeler ars ON ars.arac_detay_id = d.id
      LEFT JOIN subeler s           ON s.id = ars.sube_id
      WHERE d.plaka = ?
      LIMIT 1
    ");
    $dq->bind_param('s', $plaka);
    $dq->execute();
    $detail = $dq->get_result()->fetch_assoc();
    $dq->close();
       
    $fields = [
      'Marka'              => 'marka',
      'Model'              => 'model',
      'Yıl'                => 'yil',
      'Motor Hacmi'        => 'motor_hacmi',
      'Motor Tipi'         => 'motor_tipi',
      'Vites Tipi'         => 'vites_tipi',
      'Kilometre'          => 'kilometre',
      'Renk'               => 'renk',
      'Bagaj Hacmi'        => 'bagaj_hacmi',
      'Kişi Kapasitesi'    => 'kisi_kapasitesi',
      'Açıklama'           => 'aciklama',
      'Bulunduğu Şube'     => ''       
    ];
    $odd = true;
    $photos = ['on_foto','arka_foto','ic_foto','sag_foto','sol_foto'];
  ?>
  <div class="detail-flex">
    <div class="gallery">
      <div class="main-frame">
        <img id="mainPhoto" src="<?= htmlspecialchars($detail['on_foto']) ?>" alt="Ana">
      </div>
      <div class="thumbs">
        <?php foreach($photos as $f): 
          if (!empty($detail[$f])): ?>
            <img src="<?= htmlspecialchars($detail[$f]) ?>"
                 onclick="mainPhoto.src=this.src" alt="Th">
        <?php endif; endforeach; ?>
      </div>
    </div>
    <table class="detail-table">
      <?php foreach($fields as $label => $col): ?>
        <tr class="<?= $odd ? 'odd' : 'even' ?>">
          <th><?= htmlspecialchars($label) ?></th>
          <td>
            <?php
              if ($label === 'Bulunduğu Şube') {
                if (!empty($detail['il']) && !empty($detail['ilce'])) {
                  echo htmlspecialchars($detail['il'] . ' - ' . $detail['ilce']);
                } else {
                  echo '—';
                }
              } else {
                echo nl2br(htmlspecialchars($detail[$col]));
              }
            ?>
          </td>
        </tr>
      <?php 
        $odd = !$odd;
      endforeach; ?>
    </table>
  </div>
<?php endif; ?>

<?php elseif($sub==='ekle'): ?>
  <form id="carAddForm" action="admin_add_car.php" method="post" enctype="multipart/form-data">
    <div class="input-group">
      <label>Marka</label>
      <input  list="brandList"   id="brandInput"  name="brand"  placeholder="Marka Seçiniz"  autocomplete="off"  required>
      <datalist id="brandList">
        <?php
          $bmRes = $baglan->query("
            SELECT DISTINCT marka
            FROM araclar
            ORDER BY marka ASC
          ");
          while($bm = $bmRes->fetch_assoc()):
        ?>
          <option value="<?= htmlspecialchars($bm['marka']) ?>"></option>
        <?php endwhile; ?>
      </datalist>
    </div>
    <div class="input-group">
      <label>Model</label>
      <input 
        list="modelList" id="modelInput"  name="model"  placeholder="Önce Marka Seçin"  autocomplete="off"  required  disabled >
      <datalist id="modelList">
      </datalist>
    </div>
    <div class="input-group">
      <label>Vites Tipi</label>
      <select name="vites_tipi" id="vitesTipi" disabled>
        <option value="">Seçilmedi</option>
        <option value="Manuel">Manuel</option>
        <option value="Otomatik">Otomatik</option>
      </select>
    </div>
    <div class="input-group">
      <label>Kişi Kapasitesi</label>
      <input  type="number" name="kisi_kapasitesi" id="kisiKapasitesi"   step="1"   min="1"   disabled >
    </div>
    <div class="input-group">
      <label>Bagaj Hacmi (L)</label>
      <input type="number"  name="bagaj_hacmi"  id="bagajHacmi"  step="1"  min="0"  disabled  >
    </div>
    <div class="input-group">
      <label>Açıklama</label>
      <textarea name="aciklama" id="aciklama" disabled></textarea>
    </div>
    <div class="input-group">
      <label>Kapak Fotoğraf Önizleme</label><br>
      <img  id="kapakPrev"   src=""   alt="Kapak Fotoğrafı"   style="width:200px; height:120px; object-fit:contain; display:none;"  >
      <label>Kapak Fotoğrafı Yükle</label>
      <input  type="file"    accept="image/*" id="kapakPrevUpload"  name="on_foto"  disabled >
    </div>
    <div class="input-group">
      <label>Motor Tipi</label>
      <select name="motor_tipi" id="motorTipi" disabled>
        <option value="">Seçilmedi</option>
        <option value="Benzin">Benzin</option>
        <option value="Dizel">Dizel</option>
        <option value="Elektrik">Elektrik</option>
        <option value="Hibrit">Hibrit</option>
      </select>
    </div>
    <div class="input-group">
      <label>Motor Hacmi (L)</label>
      <input  type="number"    name="motor_hacmi"  id="motorHacmi"  step="0.1"  min="0.1"  disabled >
    </div>
   <div class="input-group">
      <label>Yaş Şartı</label>
      <select id="yasSarti" disabled>
        <option value="">20</option>
        <?php for($i = 20; $i <= 30; $i++): ?>
          <option value="<?= $i ?>"><?= $i ?></option>
        <?php endfor; ?>
      </select>
    </div>
 <div class="input-group">
      <label>Ehliyet Yaşı</label>
      <select id="ehliyetYasi" disabled>
        <option value="">1</option>
        <?php for($i = 1; $i <= 10; $i++): ?>
          <option value="<?= $i ?>"><?= $i ?></option>
        <?php endfor; ?>
      </select>
    </div>
    <div class="input-group">
      <label>Günlük Fiyat (TL)</label>
      <input type="number"   name="fiyat"   id="fiyatAuto"   step="1"   min="0"   disabled  >
    </div>

    <div class="input-group">
      <label>Plaka (Unique)</label>
      <input   type="text"   name="plaka"   id="plaka"   required >
    </div>
    <div class="input-group">
      <label>Donanım</label>
      <input   type="text"   name="donanim"  id="donanimInput"  placeholder="Donanım Bilgisi"  required>
    </div>
    <div class="input-group">
      <label>Ön Fotoğraf</label>
      <input   type="file"   name="on_foto"  accept="image/*">
    </div>
    <div class="input-group">
      <label>Sağ Fotoğraf</label>
      <input  type="file"   name="sag_foto"   accept="image/*">
    </div>
    <div class="input-group">
      <label>Sol Fotoğraf</label>
      <input type="file"  name="sol_foto" accept="image/*" >
    </div>
    <div class="input-group">
      <label>Arka Fotoğraf</label>
      <input  type="file"   name="arka_foto"  accept="image/*">
    </div>
    <div class="input-group">
      <label>İç Fotoğraf</label>
      <input type="file"  name="ic_foto"  accept="image/*">
    </div>
    <div class="input-group">
      <label>Kilometre</label>
      <input  type="number"  name="kilometre"  min="0"  step="1"  required>
    </div>
    <div class="input-group">
      <label>Renk</label>
      <input type="text"   name="renk"  required>
    </div>
    <div class="input-group">
      <label>Yıl</label>
      <select name="yil" id="yearSelect" required></select>
    </div>
            <div class="input-group">
              <label>Bulunduğu Şube</label>
              <select name="sube_id" id="subeSelect" required>
                <option value="">-- Şube Seçiniz --</option>
                <?php
                  $sRes = $baglan->query("
                    SELECT id, il, ilce
                    FROM subeler
                    ORDER BY il ASC, ilce ASC
                  ");
                  while($s = $sRes->fetch_assoc()):
                ?>
                  <option value="<?= intval($s['id']) ?>">
                    <?= htmlspecialchars($s['il'] . ' - ' . $s['ilce']) ?>
                  </option>
                <?php endwhile; ?>
              </select>
            </div>

            <button type="submit" style="background:#3183e0;color:#fff;padding:10px 20px;border:none;border-radius:4px;cursor:pointer;">
              Ekle
            </button>
          </form>

  <?php elseif($sub==='sil'): ?>
  <!-- ---------- ARAÇ SİLME FORMU ---------- -->
  <div class="input-group">
    <label>Plaka Girin:</label>
    <input type="text" id="silPlakaInput" placeholder="ABC123">
  </div>
  <button id="silBtn" 
          style="background:#c0392b;color:#fff;padding:10px 20px;border:none;border-radius:4px;cursor:pointer;">
    Sorgula ve Sil
  </button>
          
  <div id="deleteModal" style="display:none; position:fixed; top:0; left:0; 
      width:100%; height:100%; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
    <div style="background:#fff; padding:20px; border-radius:8px; width:400px; position:relative;">
      <span id="modalClose" 
            style="position:absolute; top:10px; right:10px; cursor:pointer; font-weight:bold;">×</span>
      <h3>Silme Onayı</h3>
      <div id="modalContent">
      </div>
      <div style="margin-top:15px; text-align:right;">
        <button id="confirmDelete" 
                style="background:#c0392b;color:#fff;padding:8px 16px;border:none;border-radius:4px;cursor:pointer;">
          Evet, Sil
        </button>
        <button id="cancelDelete" 
                style="background:#7f8c8d;color:#fff;padding:8px 16px;border:none;border-radius:4px;cursor:pointer; margin-left:10px;">
          Hayır, Vazgeç
        </button>
      </div>
    </div>
  </div>
  <div id="deleteResult" style="margin-top:15px;"></div>

  <script>
    const silPlakaInput = document.getElementById('silPlakaInput');
    const silBtn         = document.getElementById('silBtn');
    const deleteModal    = document.getElementById('deleteModal');
    const modalClose     = document.getElementById('modalClose');
    const modalContent   = document.getElementById('modalContent');
    const confirmDelete  = document.getElementById('confirmDelete');
    const cancelDelete   = document.getElementById('cancelDelete');
    const deleteResult   = document.getElementById('deleteResult');

    let currentPlaka = '';

    silBtn.addEventListener('click', () => {
      const plaka = silPlakaInput.value.trim();
      deleteResult.innerHTML = '';

      if (!plaka) {
        alert('Lütfen geçerli bir plaka girin.');
        return;
      }
      currentPlaka = plaka;

      fetch(`?action=get_delete_info&plaka=${encodeURIComponent(plaka)}`)
        .then(res => res.json())
        .then(data => {
          if (!data.found) {
            deleteResult.innerHTML = 
              `<p style="color:red;">Plaka <strong>${plaka}</strong> bulunamadı.</p>`;
            return;
          }
          modalContent.innerHTML = `
            <p><strong>Plaka:</strong> ${plaka}</p>
            <p>
              <strong>Marka:</strong> ${data.marka} &nbsp;&nbsp;
              <strong>Model:</strong> ${data.model} &nbsp;&nbsp;
              <strong>Yıl:</strong> ${data.yil}
            </p>
            <p>
              <strong>Kilometre:</strong> ${data.kilometre} &nbsp;&nbsp;
              <strong>Renk:</strong> ${data.renk}
            </p>
            ${data.on_foto 
              ? `<div style="margin:10px 0;">
                   <img src="${data.on_foto}" alt="Ön Fotoğraf" style="max-width:100%; border:1px solid #ccc;">
                 </div>`
              : ``
            }
            <p>Bu aracı silmek istediğinize emin misiniz?</p>
          `;
          deleteModal.style.display = 'flex';
        })
        .catch(err => {
          console.error(err);
          alert('Bilgiler alınırken hata oluştu.');
        });
    });

    modalClose.addEventListener('click', () => {
      deleteModal.style.display = 'none';
    });
    cancelDelete.addEventListener('click', () => {
      deleteModal.style.display = 'none';
    });

    confirmDelete.addEventListener('click', () => {
      // AJAX POST: delete_car endpoint’i
      const formData = new FormData();
      formData.append('action', 'delete_car');
      formData.append('plaka', currentPlaka);

      fetch('', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(resp => {
        deleteModal.style.display = 'none';
        if (resp.success) {
          deleteResult.innerHTML = 
            `<p style="color:green;">Plaka <strong>${currentPlaka}</strong> başarıyla silindi.</p>`;
        } else {
          deleteResult.innerHTML = 
            `<p style="color:red;">Hata: ${resp.message || 'Silme işlemi başarısız.'}</p>`;
        }
      })
      .catch(err => {
        console.error(err);
        deleteModal.style.display = 'none';
        deleteResult.innerHTML = `<p style="color:red;">Sunucu hatası oluştu.</p>`;
      });
    });
      
    window.addEventListener('click', (e) => {
      if (e.target === deleteModal) {
        deleteModal.style.display = 'none';
      }
    });
  </script>

        <?php elseif($sub==='durum'): ?>
          <form class="durum-form" onsubmit="return checkStatus(event)">
            <div class="input-group">
              <label>Plaka Girin:</label>
              <input type="text" id="plakaInput" placeholder="ABC123">
            </div>
            <button type="submit" style="background:#27ae60;color:#fff;padding:10px 20px;border:none;border-radius:4px;cursor:pointer;">Sorgula</button>
          </form>
          <div id="durumResult" class="durum-result" style="display:none;margin-top:15px;"></div>
        <?php endif; ?>

      </div><!-- /#araclar -->
        
                  <!-- ---------- REZERVASYON BÖLÜMÜ ---------- -->
      <div id="rezervasyon" class="section<?= $view==='rezervasyon' ? ' active' : '' ?>">
        <?php
        $updateStmt = $baglan->prepare("
          UPDATE rezervasyon
          SET teslim_edilecek_tarih = CURDATE(),
          teslimat_formu_yuklendi = 1,teslim_alma_formu_yuklendi = 1
          WHERE teslimat_formu_yuklendi = 0
            AND DATE_ADD(CONCAT(teslim_alinacak_tarih, ' ', teslim_alinacak_saat), INTERVAL 3 HOUR) < NOW()
        ");
        $updateStmt->execute();
        $updateStmt->close();

        $bugun = date('Y-m-d H:i:s');

        $sql_gelecek = "
          SELECT
            r.id AS rezervasyon_id,
            d.id AS arac_detay_id,
            d.plaka,
            d.kilometre AS toplam_km,
            a.marka,
            a.model,
            m.ad_soyad,
            m.tc_no,
            m.ehliyet_seri_no,
            m.adres AS musteri_adres,
            m.telefon AS musteri_tel,
            r.teslim_alinacak_tarih,
            r.teslim_alinacak_saat,
            r.teslim_edilecek_tarih,
            r.teslim_edilecek_saat,
            r.teslim_alinacak_sube_id,
            r.teslim_edilecek_sube_id,
            o.toplam_fiyat AS toplam_fiyat,
            DATEDIFF(r.teslim_edilecek_tarih, r.teslim_alinacak_tarih) AS kira_gun_sayisi
          FROM rezervasyon r
            JOIN arac_detay d ON r.arac_detay_id = d.id
            JOIN araclar a     ON d.arac_id = a.id
            JOIN musteriler m   ON r.musteri_id = m.id
            JOIN odeme o        ON r.odeme_id = o.id
          WHERE r.teslimat_formu_yuklendi = 0
          ORDER BY r.teslim_alinacak_tarih ASC
        ";
        $stmtG = $baglan->prepare($sql_gelecek);
        $stmtG->execute();
        $gelecekRezervasyonlar = $stmtG->get_result();
        $stmtG->close();

        $sql_aktif = "
          SELECT
            r.id AS rezervasyon_id,
            d.id AS arac_detay_id,
            d.plaka,
            d.kilometre AS toplam_km,
            a.marka,
            a.model,
            m.ad_soyad,
            m.tc_no,
            m.ehliyet_seri_no,
            m.adres AS musteri_adres,
            m.telefon AS musteri_tel,
            r.teslim_alinacak_tarih,
            r.teslim_alinacak_saat,
            r.teslim_edilecek_tarih,
            r.teslim_edilecek_saat,
            r.teslim_alinacak_sube_id,
            r.teslim_edilecek_sube_id,
            o.toplam_fiyat AS toplam_fiyat,
            DATEDIFF(r.teslim_edilecek_tarih, r.teslim_alinacak_tarih) AS kira_gun_sayisi
          FROM rezervasyon r
            JOIN arac_detay d ON r.arac_detay_id = d.id
            JOIN araclar a     ON d.arac_id = a.id
            JOIN musteriler m   ON r.musteri_id = m.id
            JOIN odeme o        ON r.odeme_id = o.id
          WHERE r.teslimat_formu_yuklendi = 1
            AND r.teslim_alma_formu_yuklendi = 0
          ORDER BY r.teslim_alinacak_tarih ASC
        ";
        $stmtA = $baglan->prepare($sql_aktif);
        $stmtA->execute();
        $aktifKiralamalar = $stmtA->get_result();
        $stmtA->close();
        ?>

        <div class="flex-container">
          <div class="panel">
            <h3>Gelecek Rezervasyonlar</h3>
            <div class="table-wrapper">
              <table>
                <thead>
                  <tr>
                    <th>Plaka</th>
                    <th>Marka/Model</th>
                    <th>Toplam KM</th>
                    <th>Müşteri</th>
                    <th>T.C. No</th>
                    <th>Ehliyet No</th>
                    <th>Adres</th>
                    <th>Tel No</th>
                    <th>Kira (Gün)</th>
                    <th>Günlük Fiyat</th>
                    <th>Toplam Fiyat</th>
                    <th>TA Tarih/Saat</th>
                    <th>TE Tarih/Saat</th>
                    <th>TA Şube</th>
                    <th>TE Şube</th>
                    <th>İşlemler</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if($gelecekRezervasyonlar->num_rows === 0): ?>
                    <tr>
                      <td colspan="16" style="text-align:center;">Hiç gelecek rezervasyon yok.</td>
                    </tr>
                  <?php else: ?>
                    <?php while($row = $gelecekRezervasyonlar->fetch_assoc()): ?>
                      <?php
                      $gunSayisi = intval($row['kira_gun_sayisi']);
                      $toplamFiyat = floatval($row['toplam_fiyat']);
                      $gunlukBedel = $gunSayisi > 0 ? round($toplamFiyat / $gunSayisi, 2) : 0;

                      $taSubeId = $row['teslim_alinacak_sube_id'];
                      $teSubeId = $row['teslim_edilecek_sube_id'];
                    
                      $q1 = $baglan->prepare("SELECT il, ilce FROM subeler WHERE id = ?");
                      $q1->bind_param('i', $taSubeId);
                      $q1->execute();
                      $tmp1 = $q1->get_result()->fetch_assoc();
                      $taSubeAd = $tmp1 ? ($tmp1['il'].' - '.$tmp1['ilce']) : '—';
                      $q1->close();

                      $q2 = $baglan->prepare("SELECT il, ilce FROM subeler WHERE id = ?");
                      $q2->bind_param('i', $teSubeId);
                      $q2->execute();
                      $tmp2 = $q2->get_result()->fetch_assoc();
                      $teSubeAd = $tmp2 ? ($tmp2['il'].' - '.$tmp2['ilce']) : '—';
                      $q2->close();
                      ?>
                      <tr>
                        <td><?= htmlspecialchars($row['plaka']) ?></td>
                        <td><?= htmlspecialchars($row['marka'].' '.$row['model']) ?></td>
                        <td><?= htmlspecialchars($row['toplam_km']) ?></td>
                        <td><?= htmlspecialchars($row['ad_soyad']) ?></td>
                        <td><?= htmlspecialchars($row['tc_no']) ?></td>
                        <td><?= htmlspecialchars($row['ehliyet_seri_no']) ?></td>
                        <td><?= nl2br(htmlspecialchars($row['musteri_adres'])) ?></td>
                        <td><?= htmlspecialchars($row['musteri_tel']) ?></td>
                        <td><?= $gunSayisi ?></td>
                        <td><?= $gunlukBedel ?> TL</td>
                        <td><?= htmlspecialchars($toplamFiyat) ?> TL</td>
                        <td>
                          <?= htmlspecialchars($row['teslim_alinacak_tarih'].' '.$row['teslim_alinacak_saat']) ?>
                        </td>
                        <td>
                          <?= htmlspecialchars($row['teslim_edilecek_tarih'].' '.$row['teslim_edilecek_saat']) ?>
                        </td>
                        <td><?= htmlspecialchars($taSubeAd) ?></td>
                        <td><?= htmlspecialchars($teSubeAd) ?></td>
                        <td>
                          <button
                            onclick="window.open(
                              'teslimat_formu.php?rez_id=<?= $row['rezervasyon_id'] ?>',
                              '_blank',
                              'width=600,height=600'
                            )"
                            style="padding:4px 8px; background:#27ae60; color:#fff; border:none; border-radius:4px; cursor:pointer;">
                            Teslimat Formu
                          </button>
                        </td>
                      </tr>
                    <?php endwhile; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div><!-- .table-wrapper -->
          </div><!-- /.panel (Gelecek Rez.) -->

          <!-- ---------- AKTİF KİRALAMALAR ---------- -->
          <div class="panel">
            <h3>Aktif Kiralamalar</h3>
            <div class="table-wrapper">
              <table>
                <thead>
                  <tr>
                    <th>Plaka</th>
                    <th>Marka/Model</th>
                    <th>Toplam KM</th>
                    <th>Müşteri</th>
                    <th>T.C. No</th>
                    <th>Ehliyet No</th>
                    <th>Adres</th>
                    <th>Tel No</th>
                    <th>Kira (Gün)</th>
                    <th>Günlük Fiyat</th>
                    <th>Toplam Fiyat</th>
                    <th>TA Tarih/Saat</th>
                    <th>TE Tarih/Saat</th>
                    <th>TA Şube</th>
                    <th>TE Şube</th>
                    <th>İşlemler</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if($aktifKiralamalar->num_rows === 0): ?>
                    <tr>
                      <td colspan="16" style="text-align:center;">Hiç aktif kiralama yok.</td>
                    </tr>
                  <?php else: ?>
                    <?php while($row = $aktifKiralamalar->fetch_assoc()): ?>
                      <?php
                      $gunSayisi = intval($row['kira_gun_sayisi']);
                      $toplamFiyat = floatval($row['toplam_fiyat']);
                      $gunlukBedel = $gunSayisi > 0 ? round($toplamFiyat / $gunSayisi, 2) : 0;

                      $taSubeId = $row['teslim_alinacak_sube_id'];
                      $teSubeId = $row['teslim_edilecek_sube_id'];
                      $q1 = $baglan->prepare("SELECT il, ilce FROM subeler WHERE id = ?");
                      $q1->bind_param('i', $taSubeId);
                      $q1->execute();
                      $tmp1 = $q1->get_result()->fetch_assoc();
                      $taSubeAd = $tmp1 ? ($tmp1['il'].' - '.$tmp1['ilce']) : '—';
                      $q1->close();
                      $q2 = $baglan->prepare("SELECT il, ilce FROM subeler WHERE id = ?");
                      $q2->bind_param('i', $teSubeId);
                      $q2->execute();
                      $tmp2 = $q2->get_result()->fetch_assoc();
                      $teSubeAd = $tmp2 ? ($tmp2['il'].' - '.$tmp2['ilce']) : '—';
                      $q2->close();
                      ?>
                      <tr>
                        <td><?= htmlspecialchars($row['plaka']) ?></td>
                        <td><?= htmlspecialchars($row['marka'].' '.$row['model']) ?></td>
                        <td><?= htmlspecialchars($row['toplam_km']) ?></td>
                        <td><?= htmlspecialchars($row['ad_soyad']) ?></td>
                        <td><?= htmlspecialchars($row['tc_no']) ?></td>
                        <td><?= htmlspecialchars($row['ehliyet_seri_no']) ?></td>
                        <td><?= nl2br(htmlspecialchars($row['musteri_adres'])) ?></td>
                        <td><?= htmlspecialchars($row['musteri_tel']) ?></td>
                        <td><?= $gunSayisi ?></td>
                        <td><?= $gunlukBedel ?> TL</td>
                        <td><?= htmlspecialchars($toplamFiyat) ?> TL</td>
                        <td>
                          <?= htmlspecialchars($row['teslim_alinacak_tarih'].' '.$row['teslim_alinacak_saat']) ?>
                        </td>
                        <td>
                          <?= htmlspecialchars($row['teslim_edilecek_tarih'].' '.$row['teslim_edilecek_saat']) ?>
                        </td>
                        <td><?= htmlspecialchars($taSubeAd) ?></td>
                        <td><?= htmlspecialchars($teSubeAd) ?></td>
                        <td>
                          <button
                            onclick="window.open(
                              'teslim_alma_formu.php?rez_id=<?= $row['rezervasyon_id'] ?>',
                              '_blank',
                              'width=600,height=700'
                            )"
                            style="padding:4px 8px; background:#e67e22; color:#fff; border:none; border-radius:4px; cursor:pointer;">
                            Teslim Alma Formu
                          </button>
                        </td>
                      </tr>
                    <?php endwhile; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div><!-- .table-wrapper -->
          </div><!-- /.panel (Aktif Kir.) -->
        </div><!-- /.flex-container -->

      </div> <!-- /#rezervasyon -->

    </div><!-- /.content -->
  </div><!-- /.main -->

<script>
  document.querySelectorAll('.arac-submenu button').forEach(btn => {
    btn.addEventListener('click', () => {
      const sub = btn.getAttribute('data-sub');
      location.href = `?view=araclar&sub=${sub}`;
    });
  });

  function checkStatus(e) {
    e.preventDefault();
    const plaka = document.getElementById('plakaInput').value.trim();
    if (!plaka) {
      alert('Lütfen geçerli bir plaka girin.');
      return false;
    }
    fetch(`admin_check_status.php?plaka=${encodeURIComponent(plaka)}`)
      .then(res => res.json())
      .then(data => {
        const div = document.getElementById('durumResult');
        div.style.display = 'block';
        let html = `<strong>Plaka:</strong> ${plaka}<br>`;
        html += `<strong>Durum:</strong> ${data.durum || 'Bilgi yok'}<br>`;
        if (data.kiralayan) {
          html += `<strong>Kiralayan:</strong> ${data.kiralayan}<br>`;
          html += `<strong>Teslim Tarihi:</strong> ${data.teslim_tarih} ${data.teslim_saat}<br>`;
        }
        div.innerHTML = html;
      })
      .catch(err => {
        alert('Durum alınırken hata oluştu.');
        console.error(err);
      });
    return false;
  }

  <?php if($view==='dashboard'): ?>
    const rawMetrics  = <?= json_encode($rawMetrics) ?>;
    const monthNames   = <?= json_encode($monthNames) ?>;
    let prevMetric     = 'm1';

    const ctx = document.getElementById('dashChart').getContext('2d');
    let chart = new Chart(ctx, {
      type: 'bar',
      data: { labels:[], datasets:[{}] },
      options:{ scales:{ y:{ beginAtZero:true } } }
    });

    function updateChart(){
      const yr     = document.getElementById('yearSel').value;
      const metric = document.getElementById('metricSel').value;
      const opt    = document.querySelector(`#metricSel option[value="${metric}"]`);
      if (opt.dataset.unimpl) {
        alert('Bu metrik henüz hazırlanıyor.');
        document.getElementById('metricSel').value = prevMetric;
        return;
      }
      prevMetric = metric;

      let labels, data;
      if (['m1','m3','m2','m4','m5'].includes(metric)) {
        const mat = rawMetrics[metric];
        const row = (yr==='all' ? mat['all'] : (mat[yr] || {}));
        if (metric==='m1' || metric==='m3') {
          labels = Object.keys(row).map(m => monthNames[m]);
          data   = Object.values(row);
        } else {
          labels = row.labels;
          data   = row.data;
        }
      } else {
        const m = rawMetrics[metric];
        labels = m.labels;
        data   = m.data;
      }

      const isLine = (metric === 'm1');
      chart.config.type = isLine ? 'line' : 'bar';
      chart.config.data.labels = labels;
      chart.config.data.datasets = [{
        label: rawMetrics[metric].label,
        data: data,
        backgroundColor: isLine ? 'rgba(46,204,113,0.4)' : 'rgba(49,131,224,0.6)',
        borderColor: isLine ? 'rgba(46,204,113,1)'   : 'rgba(49,131,224,1)',
        fill: isLine,
        tension: 0.3
      }];
      chart.update();
    }

    document.getElementById('yearSel').addEventListener('change', updateChart);
    document.getElementById('metricSel').addEventListener('change', updateChart);
    updateChart();
  <?php endif; ?>

  <?php if($sub==='ekle'): ?>
    // “Yıl” alanını doldur
    const yearSelect = document.getElementById('yearSelect');
    const currentYear = new Date().getFullYear();
    for (let i = 0; i < 5; i++) {
      const y = currentYear - i;
      const opt = document.createElement('option');
      opt.value = y;
      opt.textContent = y;
      yearSelect.appendChild(opt);
    }

    // Form alanları
    const brandInput      = document.getElementById('brandInput');
    const modelInput      = document.getElementById('modelInput');
    const modelList       = document.getElementById('modelList');
    const vitesTipiFld    = document.getElementById('vitesTipi');
    const kisiKapasFld    = document.getElementById('kisiKapasitesi');
    const bagajHacmiFld   = document.getElementById('bagajHacmi');
    const aciklamaFld     = document.getElementById('aciklama');
    const motorTipiFld    = document.getElementById('motorTipi');
    const motorHacmiFld   = document.getElementById('motorHacmi');
    const yasSartiFld     = document.getElementById('yasSarti');
    const ehliyetYasiFld  = document.getElementById('ehliyetYasi');
    const fiyatAutoFld    = document.getElementById('fiyatAuto');
    const kapakPrev       = document.getElementById('kapakPrev');
    const kapakPrevUpload = document.getElementById('kapakPrevUpload');

    function disableAndClearAutoFields() {
      [vitesTipiFld, kisiKapasFld, bagajHacmiFld, aciklamaFld,
       motorTipiFld, motorHacmiFld, yasSartiFld, ehliyetYasiFld,
       fiyatAutoFld, kapakPrevUpload].forEach(el => {
        el.value = '';
        el.disabled = true;
      });
      kapakPrev.src = '';
      kapakPrev.style.display = 'none';
    }

    function enableAutoFields() {
      [vitesTipiFld, kisiKapasFld, bagajHacmiFld, aciklamaFld,
       motorTipiFld, motorHacmiFld, yasSartiFld, ehliyetYasiFld,
       fiyatAutoFld, kapakPrevUpload].forEach(el => {
        el.disabled = false;
      });
    }

    disableAndClearAutoFields();

    brandInput.addEventListener('input', function(){
      const marka = this.value.trim();
      disableAndClearAutoFields();
      modelInput.value = '';
      modelInput.placeholder = 'Önce Marka Seçin';
      modelInput.disabled = true;
      modelList.innerHTML = '';

      if (!marka) return;

      fetch(`?action=get_models&marka=${encodeURIComponent(marka)}`)
        .then(res => res.json())
        .then(list => {
          modelList.innerHTML = '';
          list.forEach(m => {
            const opt = document.createElement('option');
            opt.value = m;
            modelList.appendChild(opt);
          });
          modelInput.placeholder = 'Model Seçiniz';
          modelInput.disabled = false;
        })
        .catch(err => console.error('Model listesi alınamadı:', err));
    });

    modelInput.addEventListener('input', function(){
      const marka = brandInput.value.trim();
      const model = this.value.trim();
      disableAndClearAutoFields();

      if (!marka || !model) return;

      const brandModel = `${marka} ${model}`.trim();
      fetch(`?action=get_specific_id&brand_model=${encodeURIComponent(brandModel)}`)
        .then(res => res.json())
        .then(obj => {
          const aracId = obj.arac_id || 0;
          if (aracId > 0) {
            fetch(`?action=get_car&arac_id=${encodeURIComponent(aracId)}`)
              .then(res => res.json())
              .then(info => {
                vitesTipiFld.value   = info.vites_tipi   || '';
                kisiKapasFld.value   = info.kisi_kapasitesi || '';
                bagajHacmiFld.value  = info.bagaj_hacmi  || '';
                aciklamaFld.value    = info.aciklama     || '';
                motorTipiFld.value   = info.motor_tipi   || '';
                motorHacmiFld.value  = info.motor_hacmi  || '';
                yasSartiFld.value    = info.yas_sarti    || '';
                ehliyetYasiFld.value = info.ehliyet_yasi || '';
                fiyatAutoFld.value   = info.fiyat        || '';

                if (info.fotograf) {
                  kapakPrev.src = info.fotograf;
                  kapakPrev.style.display = 'block';
                }

                [vitesTipiFld, kisiKapasFld, bagajHacmiFld, aciklamaFld,
                 motorTipiFld, motorHacmiFld, yasSartiFld, ehliyetYasiFld,
                 fiyatAutoFld, kapakPrevUpload].forEach(el => {
                  el.disabled = true;
                });
              })
              .catch(err => {
                console.error('get_car hatası:', err);
                enableAutoFields();
              });
          } else {
            enableAutoFields();
          }
        })
        .catch(err => {
          console.error('get_specific_id hatası:', err);
          enableAutoFields();
        });
    });
  <?php endif; ?>
</script>

</body>
</html>
