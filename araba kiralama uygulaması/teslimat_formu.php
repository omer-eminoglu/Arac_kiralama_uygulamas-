<?php 
session_start();
include("baglanti.php");

$rezervasyon_id = intval($_GET['rez_id'] ?? 0);
if (!$rezervasyon_id) {
    die("Geçersiz rezervasyon ID.");
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cikis_km    = intval($_POST['cikis_km'] ?? 0);
    $cikis_hasar = trim($_POST['cikis_hasar'] ?? '');
    $cikis_yakit = $_POST['cikis_yakit'] ?? '';

    if ($cikis_km <= 0) {
        $errors[] = "Çıkış KM pozitif bir tamsayı olmalı.";
    }
    if ($cikis_hasar === '') {
        $errors[] = "Hasar açıklaması boş olamaz.";
    }
    if (!in_array($cikis_yakit, ['Full','Yarı','Boş'])) {
        $errors[] = "Geçersiz yakıt durumu seçimi.";
    }

    if (empty($errors)) {
        $ekle = $baglan->prepare("
            INSERT INTO rezervasyon_form 
              (rezervasyon_id, cikis_km, cikis_hasar, cikis_yakit)
            VALUES (?, ?, ?, ?)
        ");
        $ekle->bind_param('iiss', $rezervasyon_id, $cikis_km, $cikis_hasar, $cikis_yakit);
        $ekle->execute();
        $ekle->close();

        $upd = $baglan->prepare("
            UPDATE rezervasyon 
            SET teslimat_formu_yuklendi = 1 
            WHERE id = ?
        ");
        $upd->bind_param('i', $rezervasyon_id);
        $upd->execute();
        $upd->close();

        echo "<script>
                alert('Teslimat formu başarıyla kaydedildi.');
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
  <title>Teslimat Formu</title>
  <style>
    body { font-family:'Segoe UI',sans-serif; padding:20px; }
    .input-group { margin-bottom:15px; }
    .input-group label { display:block; font-weight:bold; margin-bottom:5px; }
    .input-group input, 
    .input-group select, 
    .input-group textarea {
      width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;
      box-sizing:border-box;
    }
    .hata { color:red; margin-bottom:10px; }
    button {
      padding:8px 16px; background:#3183e0; color:#fff; 
      border:none; border-radius:4px; cursor:pointer;
    }
    button:hover { background:#106db0; }
  </style>
</head>
<body>

  <h2>Rezervasyon #<?= htmlspecialchars($rezervasyon_id) ?> – Teslimat Formu</h2>

  <?php if (!empty($errors)): ?>
    <div class="hata">
      <ul>
        <?php foreach($errors as $e): ?>
          <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="post">
    <div class="input-group">
      <label>Çıkış KM</label>
      <input type="number" name="cikis_km" min="1" required 
             value="<?= isset($_POST['cikis_km']) ? intval($_POST['cikis_km']) : '' ?>">
    </div>
    <div class="input-group">
      <label>Hasar Açıklama</label>
      <textarea name="cikis_hasar" rows="4" required><?= isset($_POST['cikis_hasar']) ? htmlspecialchars($_POST['cikis_hasar']) : '' ?></textarea>
    </div>
    <div class="input-group">
      <label>Yakıt Durumu</label>
      <select name="cikis_yakit" required>
        <option value="">Seçiniz</option>
        <option value="Full" <?= (isset($_POST['cikis_yakit']) && $_POST['cikis_yakit']==='Full') ? 'selected' : '' ?>>Full</option>
        <option value="Yarı" <?= (isset($_POST['cikis_yakit']) && $_POST['cikis_yakit']==='Yarı') ? 'selected' : '' ?>>Yarı</option>
        <option value="Boş" <?= (isset($_POST['cikis_yakit']) && $_POST['cikis_yakit']==='Boş') ? 'selected' : '' ?>>Boş</option>
      </select>
    </div>
    <button type="submit">Kaydet</button>
  </form>

</body>
</html>
