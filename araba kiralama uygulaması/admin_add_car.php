<?php
session_start();
include("baglanti.php");

$marka           = trim($_POST['brand']            ?? '');
$model           = trim($_POST['model']            ?? '');
$yil             = intval($_POST['yil']            ?? 0);
$vites_tipi      = trim($_POST['vites_tipi']       ?? '');
$kisi_kapasitesi = intval($_POST['kisi_kapasitesi']?? 0);
$bagaj_hacmi     = trim($_POST['bagaj_hacmi']      ?? '');
$aciklama        = trim($_POST['aciklama']         ?? '');
$motor_tipi      = trim($_POST['motor_tipi']       ?? '');
$motor_hacmi     = trim($_POST['motor_hacmi']      ?? '');
$yas_sarti       = intval($_POST['yas_sarti']      ?? 0);
$ehliyet_yasi    = intval($_POST['ehliyet_yasi']   ?? 0);
$fiyat           = floatval($_POST['fiyat']        ?? 0);
$plaka           = trim($_POST['plaka']            ?? '');
$donanim         = trim($_POST['donanim']          ?? '');
$kilometre       = intval($_POST['kilometre']      ?? 0);
$renk            = trim($_POST['renk']             ?? '');
$sube_id         = intval($_POST['sube_id']        ?? 0);
$on_foto_file    = $_FILES['on_foto']   ?? null;
$ic_foto_file    = $_FILES['ic_foto']   ?? null;
$sag_foto_file   = $_FILES['sag_foto']  ?? null;
$sol_foto_file   = $_FILES['sol_foto']  ?? null;
$arka_foto_file  = $_FILES['arka_foto'] ?? null;

$errors = [];
if ($marka === '')    { $errors[] = 'Marka boş olamaz.'; }
if ($model === '')    { $errors[] = 'Model boş olamaz.'; }
if ($yil < (intval(date('Y')) - 5) || $yil > intval(date('Y'))) {
    $errors[] = 'Yıl, bu yıldan geriye en fazla 5 yıl içinde olmalı.'; }
if ($plaka === '')    { $errors[] = 'Plaka zorunludur.'; }
if ($donanim === '')  { $errors[] = 'Donanım alanı boş olamaz.'; }
if ($kilometre <= 0)  { $errors[] = 'Kilometre pozitif bir sayı olmalı.'; }
if ($renk === '')     { $errors[] = 'Renk alanı boş kalamaz.'; }
if ($sube_id <= 0)    { $errors[] = 'Lütfen bir şube seçin.'; }

$chkExists = $baglan->prepare("SELECT id FROM araclar WHERE marka=? AND model=? LIMIT 1");
$chkExists->bind_param('ss', $marka, $model);
$chkExists->execute();
$resExists = $chkExists->get_result();
$isNewCar  = ($resExists->num_rows === 0);
$chkExists->close();

if ($isNewCar) {
    if ($vites_tipi === '')       { $errors[] = 'Yeni araç için vites tipi girilmelidir.'; }
    if ($kisi_kapasitesi <= 0)    { $errors[] = 'Yeni araç için kişi kapasitesi geçerli olmalı.'; }
    if ($bagaj_hacmi === '')      { $errors[] = 'Yeni araç için bagaj hacmi girilmelidir.'; }
    if ($aciklama === '')         { $errors[] = 'Yeni araç için açıklama girilmelidir.'; }
    if ($motor_tipi === '')       { $errors[] = 'Yeni araç için motor tipi girilmelidir.'; }
    if ($motor_hacmi === '')      { $errors[] = 'Yeni araç için motor hacmi girilmelidir.'; }
    if ($yas_sarti <= 0)          { $errors[] = 'Yeni araç için yaş şartı girilmelidir.'; }
    if ($ehliyet_yasi <= 0)       { $errors[] = 'Yeni araç için ehliyet yaşı girilmelidir.'; }
    if ($fiyat <= 0)              { $errors[] = 'Yeni araç için geçerli bir fiyat girilmelidir.'; }
}
if (!empty($errors)) {
    echo '<h3>Hata Oluştu:</h3><ul>';
    foreach ($errors as $e) {
        echo '<li>' . htmlspecialchars($e) . '</li>';
    }
    echo '</ul><p><a href="javascript:history.back()">Geri Dön</a></p>';
    exit;
}

$ctrl = $baglan->prepare("SELECT COUNT(*) AS cnt FROM arac_detay WHERE plaka = ?");
$ctrl->bind_param('s', $plaka);
$ctrl->execute();
$cnt = $ctrl->get_result()->fetch_assoc()['cnt'];
$ctrl->close();
if ($cnt > 0) {
    echo '<p style="color:red;">Bu plaka zaten sistemde kayıtlı. Lütfen farklı bir plaka deneyin.</p>';
    echo '<p><a href="javascript:history.back()">Geri Dön</a></p>';
    exit;
}

function uploadSingleImageWithPattern($fileInput, $uploadDir, $baseName, $suffix, $index) {
    if (!$fileInput || $fileInput['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    $ext = pathinfo($fileInput['name'], PATHINFO_EXTENSION);
    $cleanBase = strtolower(str_replace(' ', '_', $baseName));
    $filename = $cleanBase . '_' . $index . $suffix . '.' . $ext;
    $destinationFull = rtrim($uploadDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;
    if (move_uploaded_file($fileInput['tmp_name'], $destinationFull)) {
        return 'uploads/' . $filename;
    }
    return null;
}

$uploadDir = __DIR__ . '/uploads';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$stmt = $baglan->prepare("SELECT id FROM araclar WHERE marka = ? AND model = ? LIMIT 1");
$stmt->bind_param('ss', $marka, $model);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    $row    = $res->fetch_assoc();
    $arac_id = intval($row['id']);
} else {
    $insert = $baglan->prepare("
        INSERT INTO araclar 
          (marka, model, yil, vites_tipi, kisi_kapasitesi, bagaj_hacmi, aciklama, fotograf, 
           motor_tipi, motor_hacmi, yas_sarti, ehliyet_yasi, fiyat)
        VALUES (?, ?, ?, ?, ?, ?, ?, '', ?, ?, ?, ?, ?)
    ");
    $insert->bind_param(
        'ssisiisssiiii',
        $marka,
        $model,
        $yil,
        $vites_tipi,
        $kisi_kapasitesi,
        $bagaj_hacmi,
        $aciklama,
        $motor_tipi,
        $motor_hacmi,
        $yas_sarti,
        $ehliyet_yasi,
        $fiyat
    );
    $insert->execute();
    $arac_id = $insert->insert_id;
    $insert->close();
}

$stmt = $baglan->prepare("SELECT COUNT(*) AS cnt FROM arac_detay WHERE arac_id = ?");
$stmt->bind_param('i', $arac_id);
$stmt->execute();
$countRow = $stmt->get_result()->fetch_assoc();
$index    = intval($countRow['cnt']) + 1;
$stmt->close();

$on_foto_path   = uploadSingleImageWithPattern($on_foto_file,   $uploadDir, $model, '_on',   $index);
$ic_foto_path   = uploadSingleImageWithPattern($ic_foto_file,   $uploadDir, $model, '_ic',   $index);
$sag_foto_path  = uploadSingleImageWithPattern($sag_foto_file,  $uploadDir, $model, '_sag',  $index);
$sol_foto_path  = uploadSingleImageWithPattern($sol_foto_file,  $uploadDir, $model, '_sol',  $index);
$arka_foto_path = uploadSingleImageWithPattern($arka_foto_file, $uploadDir, $model, '_arka', $index);

if ($on_foto_path && $res->num_rows === 0) {
    $u = $baglan->prepare("UPDATE araclar SET fotograf = ? WHERE id = ?");
    $u->bind_param('si', $on_foto_path, $arac_id);
    $u->execute();
    $u->close();
}

$stmt = $baglan->prepare("
    INSERT INTO arac_detay 
      (arac_id, plaka, donanim, ic_foto, sag_foto, sol_foto, on_foto, arka_foto, kilometre, renk)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");
$stmt->bind_param(
    'isssssssis',
    $arac_id,
    $plaka,
    $donanim,
    $ic_foto_path,
    $sag_foto_path,
    $sol_foto_path,
    $on_foto_path,
    $arka_foto_path,
    $kilometre,
    $renk
);
$stmt->execute();
$arac_detay_id = $stmt->insert_id;
$stmt->close();

$insertLink = $baglan->prepare("
    INSERT INTO araclar_subeler (araclar_id, arac_detay_id, sube_id) 
    VALUES (?, ?, ?)
");
$insertLink->bind_param('iii', $arac_id, $arac_detay_id, $sube_id);
$insertLink->execute();
$insertLink->close();

echo '<p>Yeni araç ve şube bağlantısı başarıyla kaydedildi.</p>';
echo '<p><a href="admin.php?view=araclar&sub=liste">Araç Listesine Geri Dön</a></p>';
exit;
?>
