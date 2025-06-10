<?php
session_start();
include("baglanti.php");

header("Content-Type: application/json"); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $arac_id = $_SESSION['arac_id'];
    $musteri_id = $_SESSION['musteri_id'];
    $pickupDate = $_SESSION['pickup_date'];
    $pickupTime = $_SESSION['pickup_time'];

    $sql = "SELECT araclar.*, musteriler.* 
            FROM araclar, musteriler
            WHERE araclar.id = $arac_id AND musteriler.id = $musteri_id";
    $result = mysqli_query($baglan, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $ehliyet_yasi = $row['ehliyet_yasi'];
        $yas_sarti = $row['yas_sarti'];
        $dogumTarihi = $row['dogum_tarihi'];
        $licenseDate = $row['ehliyet_alis_tarihi'];
        
        $pickupDateTime = new DateTime($pickupDate . ' ' . $pickupTime);
        $licenseDateTime = new DateTime($licenseDate);
        $interval = $pickupDateTime->diff($licenseDateTime);
        $yearCount = floor(($interval->days * 24 + $interval->h) / (24 * 365));

        $licenseDateTime1 = new DateTime($dogumTarihi);
        $interval1 = $pickupDateTime->diff($licenseDateTime1);
        $yearCountyas = floor(($interval1->days * 24 + $interval1->h) / (24 * 365));

        if ($yearCount < $ehliyet_yasi && $yearCountyas < $yas_sarti) {
            echo json_encode(["status" => "error", "message" => "Ehliyet süreniz ve yaşınız bu aracı kiralamak için yeterli değil."]);
        } elseif ($yearCount < $ehliyet_yasi) {
            echo json_encode(["status" => "error", "message" => "Ehliyet süreniz bu aracı kiralamak için yeterli değil."]);
        } elseif ($yearCountyas < $yas_sarti) {
            echo json_encode(["status" => "error", "message" => "Yaşınız bu aracı kiralamak için yeterli değil."]);
        } else {
            echo json_encode(["status" => "success", "message" => "Şartlar sağlandı."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Veritabanı sorgusu başarısız."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Geçersiz istek."]);
}
exit;
?>
