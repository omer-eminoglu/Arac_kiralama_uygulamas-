<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['totalPrice'] = $_POST['totalPrice'];
    $_SESSION['aracId'] = $_POST['aracId'];
    $_SESSION['pickupDate'] = $_POST['pickupDate'];
    $_SESSION['pickupTime'] = $_POST['pickupTime'];
    $_SESSION['returnDate'] = $_POST['returnDate'];
    $_SESSION['returnTime'] = $_POST['returnTime'];
    $_SESSION['pickupLocation'] = $_POST['pickupLocation'];
    $_SESSION['returnLocation'] = $_POST['returnLocation'];
}

header("Location: odeme_giris.php");
    exit();
?>