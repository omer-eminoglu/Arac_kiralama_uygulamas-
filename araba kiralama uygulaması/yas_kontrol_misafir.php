<?php
session_start();
include("baglanti.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $pickupDate = new DateTime($data['pickup_date']);
    $licenseDate = new DateTime($data['license_date']);
    $birthDate = new DateTime($data['birth_date']);
    $aracId = $_SESSION['arac_id'] ?? null;

    $licenseYears = $pickupDate->diff($licenseDate)->y;
    $ageYears = $pickupDate->diff($birthDate)->y;

    $stmt = $baglan->prepare("SELECT ehliyet_yasi, yas_sarti FROM araclar WHERE id = ?");
    $stmt->bind_param("i", $aracId);
    $stmt->execute();
    $result = $stmt->get_result();
    $arac = $result->fetch_assoc();

    if ($arac) {
        if ($licenseYears < $arac['ehliyet_yasi'] && $ageYears < $arac['yas_sarti'] ) {
            echo json_encode(['success' => false, 'message' => 'Ehliyet süreniz ve yaşınız yeterli değil.']);
            exit;
        }
        
       else if ($licenseYears < $arac['ehliyet_yasi']) {
            echo json_encode(['success' => false, 'message' => 'Ehliyet süreniz yeterli değil.']);
            exit;
        }

        else if ($ageYears < $arac['yas_sarti']) {
            echo json_encode(['success' => false, 'message' => 'Yaşınız bu aracı kiralamak için yeterli değil.']);
            exit;
        }

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Araç bilgisi bulunamadı.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Geçersiz istek.']);
}
?>
