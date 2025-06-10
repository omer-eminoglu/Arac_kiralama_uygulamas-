<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $flightCode = $_POST['flightCode'] ?? '';
    $sectionId = 'form4';

    $_SESSION['flightCode'] = $flightCode;
    $_SESSION['sectionId'] = $sectionId;


    echo "success";
} else {
    echo "invalid request";
}
?>