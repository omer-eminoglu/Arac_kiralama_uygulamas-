<?php
session_start();
include("baglanti.php");

$rezervasyon_id = intval($_GET['rez_id'] ?? 0);
if (!$rezervasyon_id) {
    die("Geçersiz rezervasyon ID.");
}

$kontrolDonus = $baglan->prepare("
    SELECT id 
      FROM rezervasyon_form_donus 
     WHERE rezervasyon_id = ? 
     LIMIT 1
");
$kontrolDonus->bind_param('i', $rezervasyon_id);
$kontrolDonus->execute();
$dRes = $kontrolDonus->get_result();
if ($dRes->num_rows > 0) {
    echo "
      <script>
        alert('Bu rezervasyona ait dönüş formu zaten yüklenmiş.');
        window.close();
      </script>";
    exit;
}
$kontrolDonus->close();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $donus_km    = intval($_POST['donus_km'] ?? 0);
    $donus_hasar = trim($_POST['donus_hasar'] ?? '');
    $donus_yakit = $_POST['donus_yakit'] ?? '';

    if ($donus_km <= 0)      $errors[] = "Dönüş KM pozitif bir tamsayı olmalı.";
    if ($donus_hasar === '') $errors[] = "Hasar açıklaması boş olamaz.";
    if (!in_array($donus_yakit, ['Full','Yarı','Boş'])) {
        $errors[] = "Geçersiz yakıt durumu seçildi.";
    }

    if (empty($errors)) {
        $getDetay = $baglan->prepare("
            SELECT arac_detay_id 
              FROM rezervasyon 
             WHERE id = ? 
             LIMIT 1
        ");
        $getDetay->bind_param('i', $rezervasyon_id);
        $getDetay->execute();
        $rowDetay = $getDetay->get_result()->fetch_assoc();
        $aracDetayId = intval($rowDetay['arac_detay_id']);
        $getDetay->close();

        $updDetay = $baglan->prepare("
            UPDATE arac_detay 
               SET kilometre = ? 
             WHERE id = ?
        ");
        $updDetay->bind_param('ii', $donus_km, $aracDetayId);
        $updDetay->execute();
        $updDetay->close();

        $ekleDon = $baglan->prepare("
            INSERT INTO rezervasyon_form_donus 
              (rezervasyon_id, donus_km, donus_hasar, donus_yakit)
            VALUES (?, ?, ?, ?)
        ");
        $ekleDon->bind_param('iiss', $rezervasyon_id, $donus_km, $donus_hasar, $donus_yakit);
        $ekleDon->execute();
        $ekleDon->close();

        $updRez = $baglan->prepare("
            UPDATE rezervasyon 
               SET teslim_alma_formu_yuklendi = 1 
             WHERE id = ?
        ");
        $updRez->bind_param('i', $rezervasyon_id);
        $updRez->execute();
        $updRez->close();

        echo "
          <script>
            alert('Dönüş formu başarıyla kaydedildi.');
            if (window.opener && !window.opener.closed) {
              window.opener.location.reload();
            }
            window.close();
          </script>";
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="utf-8">
  <title>Teslim Alma Formu</title>
  <style>
    body { font-family:'Segoe UI',sans-serif; padding:20px; }
    .input-group { margin-bottom:15px; }
    .input-group label { display:block; font-weight:bold; margin-bottom:5px; }
    .input-group input, 
    .input-group select, 
    .input-group textarea {
      width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;
      box-sizing:border-box; font-size:14px;
    }
    .hata { color:red; margin-bottom:10px; }
    button {
      padding:8px 16px; background:#27ae60; color:#fff; 
      border:none; border-radius:4px; cursor:pointer;
      font-size:14px;
    }
    button:hover { background:#1e874f; }
  </style>
</head>
<body>

  <h2>Rezervasyon #<?= htmlspecialchars($rezervasyon_id) ?> – Teslim Alma Formu</h2>

  <?php if (!empty($errors)): ?>
    <div class="hata">
      <ul>
        <?php foreach ($errors as $e): ?>
          <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="post">
    <div class="input-group">
      <label>Dönüş KM</label>
      <input 
        type="number" 
        name="donus_km" 
        min="1" 
        required
        value="<?= isset($_POST['donus_km']) ? intval($_POST['donus_km']) : '' ?>"
      >
    </div>
    <div class="input-group">
      <label>Hasar Açıklama</label>
      <textarea 
        name="donus_hasar" 
        rows="4" 
        required
      ><?= isset($_POST['donus_hasar']) ? htmlspecialchars($_POST['donus_hasar']) : '' ?></textarea>
    </div>
    <div class="input-group">
      <label>Yakıt Durumu</label>
      <select name="donus_yakit" required>
        <option value="">Seçiniz</option>
        <option value="Full" <?= (isset($_POST['donus_yakit']) && $_POST['donus_yakit']==='Full') ? 'selected' : '' ?>>Full</option>
        <option value="Yarı" <?= (isset($_POST['donus_yakit']) && $_POST['donus_yakit']==='Yarı') ? 'selected' : '' ?>>Yarı</option>
        <option value="Boş" <?= (isset($_POST['donus_yakit']) && $_POST['donus_yakit']==='Boş') ? 'selected' : '' ?>>Boş</option>
      </select>
    </div>
    <button type="submit">Kaydet</button>
  </form>

</body>
</html>
