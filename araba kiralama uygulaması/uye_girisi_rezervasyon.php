<?php  
session_start();
include("baglanti.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $pickupDate = $_POST['pickup_date'] ?? null;
    $pickupTime = $_POST['pickup_time'] ?? null;
    $returnDate = $_POST['return_date'] ?? null;
    $returnTime = $_POST['return_time'] ?? null;
    $pickupLocation = $_POST['pickup_location'] ?? null;
    $returnLocation = $_POST['return_location'] ?? null;

    $pickupCity = $pickupDistrict = $returnCity = $returnDistrict = null;

    if ($pickupLocation && strpos($pickupLocation, " - ") !== false) {
        list($pickupCity, $pickupDistrict) = explode(" - ", $pickupLocation);
    }
    if ($returnLocation && strpos($returnLocation, " - ") !== false) {
        list($returnCity, $returnDistrict) = explode(" - ", $returnLocation);
    }

    $sql = "SELECT * FROM musteriler WHERE email = ?";
    $stmt = mysqli_prepare($baglan, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['sifre'])) {
            $_SESSION['musteri_id'] = $row['id'];
            session_regenerate_id(true); 

            $_SESSION['pickup_date'] = $pickupDate;
            $_SESSION['pickup_time'] = $pickupTime;
            $_SESSION['return_date'] = $returnDate;
            $_SESSION['return_time'] = $returnTime;
            $_SESSION['pickup_city'] = $pickupCity;
            $_SESSION['pickup_district'] = $pickupDistrict;
            $_SESSION['return_city'] = $returnCity;
            $_SESSION['return_district'] = $returnDistrict;

            header("Location: rezervasyon_formu_uye.php");
            exit();
        } else {
            $error_message = "E-posta veya şifre hatalı.";
        }
    } else {
        $error_message = "E-posta veya şifre hatalı.";
    }

    mysqli_stmt_close($stmt);
}
?>
