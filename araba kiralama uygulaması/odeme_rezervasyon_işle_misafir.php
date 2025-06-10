<?php 
session_start();
include("baglanti.php");

if (empty($_POST['card-name']) || empty($_POST['card-number']) || empty($_POST['expiry-month']) || empty($_POST['expiry-year']) || empty($_POST['cvv'])) {
    echo json_encode(['success' => false, 'message' => 'Lütfen tüm kart bilgilerini doldurun.']);
    exit;
}

$firstName = $_POST['first-name'];
$lastName = $_POST['last-name'];
$fullName = $firstName . " " . $lastName;
$birthDate = $_POST['birth-date'];
$email = $_POST['email-1'];
$licenseNo = $_POST['license-no'];
$licenseDate = $_POST['licanse-date'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$nonTurkish = isset($_POST['non-turkish']) ? true : false;
$abroad = isset($_POST['abroad']) ? true : false;
$tcNo = $nonTurkish ? null : $_POST['tc-no'];
$passportNo = $nonTurkish ? $_POST['passport-no'] : null;
$city = $abroad ? null : $_POST['city'];
$district = $abroad ? null : $_POST['district'];
$country = $abroad ? $_POST['country'] : null;

$cardName = $_POST['card-name'];
$cardNumber = $_POST['card-number'];
$expiryMonth = $_POST['expiry-month'];
$expiryYear = $_POST['expiry-year'];
$cvv = $_POST['cvv'];
$totalPrice = $_SESSION['totalPrice'];

$aracId = $_SESSION['arac_id'];
$pickupDate = $_SESSION['pickup_date'];
$returnDate = $_SESSION['return_date'];
$pickupTime = $_SESSION['pickup_time'];
$returnTime = $_SESSION['return_time'];
$pickupSubeId = $_SESSION['pickup_city_id'];
$returnSubeId = $_SESSION['return_city_id'];
$flightCode = $_POST['flight-code-prefix'] . $_POST['flight-code-number'];

$companyBilling = isset($_POST['company-billing']) ? true : false;
$companyName = $companyBilling ? $_POST['company-name'] : null;
$taxOffice = $companyBilling ? $_POST['tax-office'] : null;
$taxNumber = $companyBilling ? $_POST['tax-number'] : null;
$companyAddress = $companyBilling ? $_POST['company-address'] : null;

mysqli_begin_transaction($baglan);

try {
    $sql = "SELECT * FROM musteriler WHERE email = ?";
    $stmt = mysqli_prepare($baglan, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Email sistemde kayıtlı.'); window.history.back();</script>";
        exit;
    }
    mysqli_stmt_close($stmt);

    $sql = "SELECT * FROM musteriler WHERE tc_no = ?";
    $stmt = mysqli_prepare($baglan, $sql);
    mysqli_stmt_bind_param($stmt, "s", $tcNo);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('T.C. kimlik numarası sistemde kayıtlı.'); window.history.back();</script>";
        exit;
    }
    mysqli_stmt_close($stmt);

    $sql = "SELECT * FROM musteriler WHERE passport_no = ?";
    $stmt = mysqli_prepare($baglan, $sql);
    mysqli_stmt_bind_param($stmt, "s", $passportNo);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Pasaport numarası sistemde kayıtlı.'); window.history.back();</script>";
        exit;
    }
    mysqli_stmt_close($stmt);

    $sql = "INSERT INTO musteriler (ad_soyad, dogum_tarihi, email, tc_no, passport_no, ehliyet_seri_no, ehliyet_alis_tarihi, il, ilce, ulke, adres, telefon) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($baglan, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssssssss", $fullName, $birthDate, $email, $tcNo, $passportNo, $licenseNo, $licenseDate, $city, $district, $country, $address, $phone);
    mysqli_stmt_execute($stmt);
    $musteriId = mysqli_insert_id($baglan);
    mysqli_stmt_close($stmt);

    if ($companyBilling) {
        $sql = "INSERT INTO fatura (musteri_id, sirket_adi, vergi_dairesi, vergi_no, adres) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($baglan, $sql);
        mysqli_stmt_bind_param($stmt, "issss", $musteriId, $companyName, $taxOffice, $taxNumber, $companyAddress);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    $sql = "INSERT INTO odeme (toplam_fiyat, kart_isim, kart_no, ay, yil, cvc) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($baglan, $sql);
    mysqli_stmt_bind_param($stmt, "dsssss", $totalPrice, $cardName, $cardNumber, $expiryMonth, $expiryYear, $cvv);
    mysqli_stmt_execute($stmt);
    $odemeId = mysqli_insert_id($baglan);
    mysqli_stmt_close($stmt);

    $aracDetayQuery = "
        SELECT * 
        FROM arac_detay 
        WHERE arac_id = ? 
          AND arac_detay.id NOT IN (
              SELECT arac_detay_id 
              FROM rezervasyon 
              WHERE (teslim_alinacak_tarih >= ? AND teslim_edilecek_tarih <= ?) 
          ) 
          AND arac_detay.id IN (SELECT arac_detay_id FROM araclar_subeler WHERE sube_id = ?) 
        ORDER BY kilometre ASC 
        LIMIT 1";
    $stmt = mysqli_prepare($baglan, $aracDetayQuery);
    mysqli_stmt_bind_param($stmt, "issi", $aracId, $pickupDate, $returnDate, $pickupSubeId);
    mysqli_stmt_execute($stmt);
    $aracDetayResult = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($aracDetayResult) === 0) {
        throw new Exception("Müsait araç bulunamadı.");
    }

    $aracDetay = mysqli_fetch_assoc($aracDetayResult);
    $aracDetayId = $aracDetay['id'];
    mysqli_stmt_close($stmt);

    $sql = "INSERT INTO rezervasyon (arac_detay_id, musteri_id, teslim_alinacak_tarih, teslim_edilecek_tarih, teslim_alinacak_saat, teslim_edilecek_saat, teslim_alinacak_sube_id, teslim_edilecek_sube_id, odeme_id, ucus_kodu) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($baglan, $sql);
    mysqli_stmt_bind_param($stmt, "iissssiiis", $aracDetayId, $musteriId, $pickupDate, $returnDate, $pickupTime, $returnTime, $pickupSubeId, $returnSubeId, $odemeId, $flightCode);
    mysqli_stmt_execute($stmt);

    mysqli_commit($baglan);
    header("Location: basarili_sayfasi.php");
    exit;
} catch (Exception $e) {
    mysqli_rollback($baglan);
    echo "<script>alert('" . $e->getMessage() . "'); window.history.back();</script>";
}

?>