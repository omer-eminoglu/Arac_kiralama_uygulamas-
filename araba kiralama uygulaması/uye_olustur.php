<?php
session_start();
include("baglanti.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ad_soyad = $_POST['ad_soyad'];
    $dogum_tarihi = $_POST['birth-date'];
    $email = $_POST['email'];
    $sifre = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $tc_no = $_POST['tc-no'];
    $passport_no = $_POST['passport-no'];
    $ehliyet_alis_tarihi = $_POST['licanse-date'];
    $ehliyet_seri_no = $_POST['ehliyet_seri_no'];
    $adres = $_POST['adres'];
    $il = isset($_POST['city']) ? $_POST['city'] : null;
    $ilce = isset($_POST['district']) ? $_POST['district'] : null;
    $ulke = isset($_POST['country']) ? $_POST['country'] : null;
    $telefon = $_POST['telefon'];

    $abroad = isset($_POST['abroad']);
    $non_turkish = isset($_POST['non-turkish']);

    if ($non_turkish) {
        $tc_no = null;
    } else {
        $passport_no = null; 
    }
    
    $dogum_tarihi_obj = new DateTime($dogum_tarihi);
    $bugun = new DateTime();
    $yas = $bugun->diff($dogum_tarihi_obj)->y;

    if ($yas < 18) {
        die("Hata: 18 yaşından küçükler kayıt olamaz.");
    }
    
    $ehliyet_tarihi_obj = new DateTime($ehliyet_alis_tarihi);
    $ehliyet_suresi = $bugun->diff($ehliyet_tarihi_obj)->y;

    if ($ehliyet_suresi < 2) {
        die("Hata: Ehliyet süresi en az 2 yıl olmalıdır.");
    }

    if ($sifre !== $confirm_password) {
        die("Hata: Şifreler uyuşmuyor.");
    }

    $sql_check = "SELECT * FROM musteriler WHERE email = ? OR tc_no = ? OR passport_no = ? OR ehliyet_seri_no = ?";
    $stmt_check = mysqli_prepare($baglan, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "ssss", $email, $tc_no, $passport_no, $ehliyet_seri_no);
    mysqli_stmt_execute($stmt_check);
    $result_check = mysqli_stmt_get_result($stmt_check);

    if (mysqli_num_rows($result_check) > 0) {
        die("Hata: Bu e-posta, TC No, pasaport no veya ehliyet seri numarası zaten kayıtlı.");
    }

    $sql_insert = "INSERT INTO musteriler (ad_soyad, dogum_tarihi, email, tc_no, passport_no, ehliyet_alis_tarihi, ehliyet_seri_no, adres, sifre, il, ilce, ulke, telefon)
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = mysqli_prepare($baglan, $sql_insert);
    $hashed_password = password_hash($sifre, PASSWORD_DEFAULT);
    mysqli_stmt_bind_param($stmt_insert, "sssssssssssss", $ad_soyad, $dogum_tarihi, $email, $tc_no, $passport_no, $ehliyet_alis_tarihi, $ehliyet_seri_no, $adres, $hashed_password, $il, $ilce, $ulke, $telefon);

    if (mysqli_stmt_execute($stmt_insert)) {
        echo "Kayıt başarılı! Üye giriş sayfasına yönlendiriliyorsunuz...";
        header("refresh:2; url=uye_girisi.php");
    } else {
        echo "Hata: Kayıt işlemi başarısız oldu.";
    }
}
?>
