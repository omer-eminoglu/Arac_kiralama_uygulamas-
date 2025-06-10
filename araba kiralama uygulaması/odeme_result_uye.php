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

echo "<h1>Ödeme Sayfası</h1>";
echo "Toplam Fiyat: " . $_SESSION['totalPrice'] . "<br>";
echo "Araç ID: " . $_SESSION['aracId'] . "<br>";
echo "Teslim Alınacak Tarih: " . $_SESSION['pickupDate'] . "<br>";
echo "Teslim Alınacak Saat: " . $_SESSION['pickupTime'] . "<br>";
echo "Teslim Edilecek Tarih: " . $_SESSION['returnDate'] . "<br>";
echo "Teslim Edilecek Saat: " . $_SESSION['returnTime'] . "<br>";
echo "Teslim Alınacak Yer: " . $_SESSION['pickupLocation'] . "<br>";
echo "Teslim Edilecek Yer: " . $_SESSION['returnLocation'] . "<br>";

header("Location: odeme.php");
    exit();
?>