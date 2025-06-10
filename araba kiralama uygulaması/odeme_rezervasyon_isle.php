<?php
session_start();
include("baglanti.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cardName = $_POST["card-name"] ?? '';
    $cardNumber = $_POST["card-number"] ?? '';
    $expiryMonth = $_POST["expiry-month"] ?? '';
    $expiryYear = $_POST["expiry-year"] ?? '';
    $cvv = $_POST["cvv"] ?? '';
    $totalPrice = $_SESSION["totalPrice"] ?? 0;
    
    $companyBilling = isset($_POST["company-billing"]);
    $companyName = $_POST["company-name"] ?? '';
    $taxOffice = $_POST["tax-office"] ?? '';
    $taxNumber = $_POST["tax-number"] ?? '';
    $companyAddress = $_POST["company-address"] ?? '';

    if (empty($cardName) || empty($cardNumber) || empty($expiryMonth) || empty($expiryYear) || empty($cvv)) {
        echo "Lütfen tüm alanları doldurun.";
        exit;
    }
    $odemeQuery = "INSERT INTO odeme (toplam_fiyat, kart_isim, kart_no, ay, yil, cvc) VALUES (?, ?, ?, ?, ?, ?)";
    $stmtOdeme = $baglan->prepare($odemeQuery);
    $stmtOdeme->bind_param("dssiii", $totalPrice, $cardName, $cardNumber, $expiryMonth, $expiryYear, $cvv);
    $stmtOdeme->execute();
    $odemeId = $stmtOdeme->insert_id;
    $stmtOdeme->close();

    if ($odemeId) {
        if ($companyBilling) {
            $faturaQuery = "INSERT INTO fatura (odeme_id, sirket_adi, vergi_dairesi, vergi_numarasi, sirket_adresi) VALUES (?, ?, ?, ?, ?)";
            $stmtFatura = $baglan->prepare($faturaQuery);
            $stmtFatura->bind_param("issss", $odemeId, $companyName, $taxOffice, $taxNumber, $companyAddress);
            if (!$stmtFatura->execute()) {
                echo "Fatura kayıt hatası: " . $stmtFatura->error;
                exit;
            }
            $stmtFatura->close();
        }

        $aracId = $_SESSION["arac_id"];
        $musteriId = $_SESSION["musteri_id"];
        $pickupDate = $_SESSION["pickup_date"];
        $pickupTime = $_SESSION["pickup_time"];
        $returnDate = $_SESSION["return_date"];
        $returnTime = $_SESSION["return_time"];
        $pickupCity = $_SESSION["pickup_city_id"];
        $returnCity = $_SESSION["return_city_id"];
        $flightCode = $_SESSION['flightCode'];
        
        $pickupDate = (new DateTime($_SESSION["pickup_date"]))->format('Y-m-d');
        $returnDate = (new DateTime($_SESSION["return_date"]))->format('Y-m-d');

        $aracDetayQuery = "
         SELECT * 
        FROM arac_detay 
        WHERE arac_id = ? 
          AND arac_detay.id NOT IN (
              SELECT arac_detay_id 
              FROM rezervasyon 
              WHERE (teslim_alinacak_tarih >= ? AND teslim_edilecek_tarih <= ?) 
          ) 
          and arac_detay.id IN (SELECT arac_detay_id FROM araclar_subeler WHERE sube_id = ?) 
        ORDER BY kilometre ASC 
        LIMIT 1";

        $stmtAracDetay = $baglan->prepare($aracDetayQuery);
        $stmtAracDetay->bind_param("issi", $aracId, $pickupDate, $returnDate, $pickupCity);

        if ($stmtAracDetay->execute()) {
            $result = $stmtAracDetay->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $aracDetayId = $row["id"];
                echo "Bulunan arac_detay_id: " . $aracDetayId;
            } else {
                echo "Uygun araç bulunamadı.";
                echo "<script>showPopupWithMessage('Uygun araç bulunamadı. Lütfen başka bir tarih seçin.');</script>";
                exit;
            }
        } else {
            echo "Sorgu hatası: " . $stmtAracDetay->error;
            echo "<script>showPopupWithMessage('Bir hata oluştu, lütfen tekrar deneyin.');</script>";
            exit;
        }
        $stmtAracDetay->close();

        if ($aracDetayId) {
            $rezervasyonQuery = "
                INSERT INTO rezervasyon 
                (arac_detay_id, musteri_id, teslim_alinacak_tarih, teslim_alinacak_saat, teslim_edilecek_tarih, teslim_edilecek_saat, teslim_alinacak_sube_id, teslim_edilecek_sube_id, odeme_id, ucus_kodu) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?)";
            $stmtRezervasyon = $baglan->prepare($rezervasyonQuery);
            $stmtRezervasyon->bind_param(
                "iissssiiis",
                $aracDetayId,
                $musteriId,
                $pickupDate,
                $pickupTime,
                $returnDate,
                $returnTime,
                $pickupCity,
                $returnCity,
                $odemeId,
                $flightCode
            );

            if ($stmtRezervasyon->execute()) {
                echo "Rezervasyon başarıyla tamamlandı.";
                header("Location: dashbord.php");
                exit;
            } else {
                echo "Rezervasyon kaydı oluşturulamadı.";
                echo "<script>showPopupWithMessage('Rezervasyon kaydı oluşturulamadı. Lütfen tekrar deneyin.');</script>";
                exit;
            }
            $stmtRezervasyon->close();
        } else {
            echo "Uygun araç bulunamadı.";
            echo "<script>showPopupWithMessage('Uygun araç bulunamadı. Lütfen başka bir tarih seçin.');</script>";
            exit;
        }
    } else {
        echo "Ödeme kaydı oluşturulamadı.";
        echo "<script>showPopupWithMessage('Ödeme kaydı oluşturulamadı. Lütfen tekrar deneyin.');</script>";
        exit;
    }
} else {
    echo "Geçersiz istek.";
    echo "<script>showPopupWithMessage('Geçersiz istek.');</script>";
    exit;
}
?>
