<?php
session_start();
include("baglanti.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $arac_id = $_SESSION['arac_id'];
    $pickupDate = $_POST['pickup_date'];
    $pickupTime = $_POST['pickup_time'];
    $returnDate = $_POST['return_date'];
    $returnTime = $_POST['return_time'];
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
    $_SESSION['pickup_date'] = $pickupDate;
    $_SESSION['pickup_time'] = $pickupTime;
    $_SESSION['return_date'] = $returnDate;
    $_SESSION['return_time'] = $returnTime;
    $_SESSION['pickup_city'] =  $pickupCity;
    $_SESSION['pickup_district'] =  $pickupDistrict;  
    $_SESSION['return_city'] =  $returnCity;  
    $_SESSION['return_district'] =  $returnDistrict;


    $sube_sql = "SELECT id FROM subeler WHERE CONCAT(il, ' - ', ilce) = ?";
    $stmt = mysqli_prepare($baglan, $sube_sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $pickupLocation);
        mysqli_stmt_execute($stmt);
        $sube_result = mysqli_stmt_get_result($stmt);
        $sube = mysqli_fetch_assoc($sube_result);
        $sube_id = $sube['id'] ?? null; 
        mysqli_stmt_close($stmt);
    }

    if ($sube_id) {
        $check_arac_sube_sql = "SELECT * FROM araclar_subeler WHERE araclar_id = ? AND sube_id = ?";
        $stmt = mysqli_prepare($baglan, $check_arac_sube_sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ii", $arac_id, $sube_id);
            mysqli_stmt_execute($stmt);
            $arac_sube_result = mysqli_stmt_get_result($stmt);
            mysqli_stmt_close($stmt);

            if (mysqli_num_rows($arac_sube_result) > 0) {
                $pickupDateTime = $pickupDate . ' ' . $pickupTime;
                $returnDateTime = $returnDate . ' ' . $returnTime;

                $check_reservation_sql = "SELECT * FROM rezervasyon 
                                          WHERE arac_id = ? AND teslim_alinacak_sube_id = ? 
                                          AND ((teslim_alinacak_tarih <= ? AND teslim_edilecek_tarih >= ?) 
                                          OR (teslim_alinacak_tarih <= ? AND teslim_edilecek_tarih >= ?))";

                $stmt = mysqli_prepare($baglan, $check_reservation_sql);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "iissss", $arac_id, $sube_id, $returnDateTime, $pickupDateTime, $pickupDateTime, $returnDateTime);
                    mysqli_stmt_execute($stmt);
                    $reservation_result = mysqli_stmt_get_result($stmt);
                    mysqli_stmt_close($stmt);

                    if (mysqli_num_rows($reservation_result) == 0) {
                        $_SESSION['pickup_date'] = $pickupDate;
                        $_SESSION['pickup_time'] = $pickupTime;
                        $_SESSION['return_date'] = $returnDate;
                        $_SESSION['return_time'] = $returnTime;
                        $_SESSION['pickup_location'] = $pickupLocation;
                        $_SESSION['return_location'] = $returnLocation;

       
                        header("Location: rezervasyon_formu_uye.php");
                        exit();
                    } else {
                
                        header("Location: display_results1_uye.php");
                        exit();
                    }
                } else {
                    die("Rezervasyon sorgusu başarısız: " . mysqli_error($baglan));
                }
            } else {
                echo "Şube ve araç eşleşmesi bulunamadı.";
                 header("Location: display_results1_uye.php");
                exit();
            }
        } else {
            die("Araç ve şube eşleşmesi sorgusu başarısız: " . mysqli_error($baglan));
        }
    } 
}
?>