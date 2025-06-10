<?php
session_start();
include("baglanti.php");

// JSON veya form-data kabul et
$input = json_decode(file_get_contents('php://input'), true);
$confirm = $_POST['confirm'] ?? $input['confirm'] ?? '';
$plaka   = trim($_POST['plaka']   ?? $input['plaka']   ?? '');

if ($confirm === 'yes' && $plaka) {
    // 1) Detay ID'sini al
    $stmt = $baglan->prepare("SELECT id FROM arac_detay WHERE plaka = ?");
    $stmt->bind_param('s', $plaka);
    $stmt->execute();
    $stmt->bind_result($detay_id);
    if (! $stmt->fetch()) {
        $stmt->close();
        die("Bu plaka sistemde yok veya zaten silinmiş.");
    }
    $stmt->close();
    
    $delSubs = $baglan->prepare("DELETE FROM araclar_subeler WHERE arac_detay_id = ?");
    $delSubs->bind_param('i', $detay_id);
    $delSubs->execute();
    $delSubs->close();

    $delDet = $baglan->prepare("DELETE FROM arac_detay WHERE id = ?");
    $delDet->bind_param('i', $detay_id);
    $delDet->execute();
    if ($delDet->affected_rows === 0) {
        die("Detay kaydı silinemedi: " . $baglan->error);
    }
    $delDet->close();

    echo '<p>Plaka <strong>' . htmlspecialchars($plaka) . '</strong> başarıyla silindi.</p>';
    echo '<p><a href="admin.php?view=araclar&sub=sil">Yeni Plaka Gir</a> | ';
    echo '<a href="admin.php?view=araclar&sub=liste">Araç Listesine Dön</a></p>';
    exit;
}

if ($confirm === 'no') {
    header("Location: admin.php?view=araclar&sub=sil");
    exit;
}

if (isset($_POST['arac_detay_id'])) {
    $plaka = trim($_POST['arac_detay_id']);

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
    $q->close();

    if ($res->num_rows === 0) {
        echo '<p>Plaka <strong>' . htmlspecialchars($plaka) . '</strong> sistemde bulunamadı.</p>';
        echo '<p><a href="admin.php?view=araclar&sub=sil">Geri Dön ve Yeni Plaka Gir</a></p>';
        exit;
    }

    $row       = $res->fetch_assoc();
    $marca     = htmlspecialchars($row['marka']);
    $modelo    = htmlspecialchars($row['model']);
    $yil_arac  = intval($row['yil']);
    $kilom     = intval($row['kilometre']);
    $renk_arac = htmlspecialchars($row['renk']);
    $photo     = htmlspecialchars($row['on_foto']);

    echo '<h3>Plaka: ' . htmlspecialchars($plaka) . '</h3>';
    echo '<p><strong>Marka:</strong> ' . $marca .
         '  <strong>Model:</strong> ' . $modelo .
         '  <strong>Yıl:</strong> ' . $yil_arac . '</p>';
    echo '<p><strong>Kilometre:</strong> ' . $kilom .
         '  <strong>Renk:</strong> ' . $renk_arac . '</p>';
    if ($photo) {
        echo '<div style="margin:15px 0;">
                <img src="' . $photo . '" alt="Ön Fotoğraf" style="max-width:300px;border:1px solid #ccc;">
              </div>';
    }
    echo '<p>Bu aracı silmek istediğinizden emin misiniz?</p>';

    // Onay formu
    echo '<form method="post" style="display:inline-block; margin-right:10px;">';
    echo '  <input type="hidden" name="plaka" value="' . htmlspecialchars($plaka) . '">';
    echo '  <button type="submit" name="confirm" value="yes"
                style="background:#c0392b; color:#fff; padding:8px 16px; border:none; border-radius:4px; cursor:pointer;">
            Evet, Sil
           </button>';
    echo '</form>';

    echo '<form method="post" style="display:inline-block;">';
    echo '  <input type="hidden" name="confirm" value="no">';
    echo '  <button type="submit"
                style="background:#7f8c8d; color:#fff; padding:8px 16px; border:none; border-radius:4px; cursor:pointer;">
            Hayır, Vazgeç
           </button>';
    echo '</form>';

    exit;
}

header("Location: admin.php?view=araclar&sub=sil");
exit;
?>
