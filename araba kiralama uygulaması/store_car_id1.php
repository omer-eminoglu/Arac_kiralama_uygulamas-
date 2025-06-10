<?php

session_start(); 

if (isset($_GET['arac_id'])) {
    $arac_id = $_GET['arac_id'];

    $_SESSION['arac_id'] = $arac_id;

    header("Location: rezervasyon_formu1.php");
    
    exit();
} else {
    echo "Araç ID'si alınamadı.";
}
?>