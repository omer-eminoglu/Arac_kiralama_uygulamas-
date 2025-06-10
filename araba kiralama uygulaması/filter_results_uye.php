<?php
include("baglanti.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pickupDate = isset($_POST['pickup_date']) ? $_POST['pickup_date'] : null;
    $pickupTime = isset($_POST['pickup_time']) ? $_POST['pickup_time'] : null;
    $returnDate = isset($_POST['return_date']) ? $_POST['return_date'] : null;
    $returnTime = isset($_POST['return_time']) ? $_POST['return_time'] : null;

    $pickupLocation = isset($_POST['pickup_location']) ? $_POST['pickup_location'] : null;
    $returnLocation = isset($_POST['return_location']) ? $_POST['return_location'] : null;

    if ($pickupLocation !== null) {
        list($pickupCity, $pickupDistrict) = explode(" - ", $pickupLocation);
    } else {
        $pickupCity = null;
        $pickupDistrict = null;
    }

    if ($returnLocation !== null) {
        list($returnCity, $returnDistrict) = explode(" - ", $returnLocation);
    } else {
        $returnCity = null;
        $returnDistrict = null;
    }

    session_start();
    $_SESSION['pickup_date'] = $pickupDate;
    $_SESSION['pickup_time'] = $pickupTime;
    $_SESSION['return_date'] = $returnDate;
    $_SESSION['return_time'] = $returnTime;

    $_SESSION['pickup_city'] = $pickupCity;
    $_SESSION['pickup_district'] = $pickupDistrict;
    $_SESSION['return_city'] = $returnCity;
    $_SESSION['return_district'] = $returnDistrict;

    if ($pickupCity && $pickupDistrict) {
        $pickupQuery = "SELECT id FROM subeler WHERE il = ? AND ilce = ?";
        $stmtPickup = $baglan->prepare($pickupQuery);
        $stmtPickup->bind_param("ss", $pickupCity, $pickupDistrict);
        $stmtPickup->execute();
        $pickupResult = $stmtPickup->get_result();
        if ($pickupRow = $pickupResult->fetch_assoc()) {
            $_SESSION['pickup_city_id'] = $pickupRow['id'];
        }
        $stmtPickup->close();
    }

    if ($returnCity && $returnDistrict) {
        $returnQuery = "SELECT id FROM subeler WHERE il = ? AND ilce = ?";
        $stmtReturn = $baglan->prepare($returnQuery);
        $stmtReturn->bind_param("ss", $returnCity, $returnDistrict);
        $stmtReturn->execute();
        $returnResult = $stmtReturn->get_result();
        if ($returnRow = $returnResult->fetch_assoc()) {
            $_SESSION['return_city_id'] = $returnRow['id'];
        }
        $stmtReturn->close();
    }

    header("Location: display_results_uye.php");
    exit();
}
?>
