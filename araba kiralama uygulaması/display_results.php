<?php
session_start();
include("baglanti.php");

$pickupDate     = $_SESSION['pickup_date'];
$pickupTime     = $_SESSION['pickup_time'];
$returnDate     = $_SESSION['return_date'];
$returnTime     = $_SESSION['return_time'];
$pickupCity     = $_SESSION['pickup_city'];
$pickupDistrict = $_SESSION['pickup_district'];
$returnCity     = $_SESSION['return_city'];
$returnDistrict = $_SESSION['return_district'];

$sql = "
    SELECT 
        araclar.*
    FROM 
        araclar
    JOIN 
        arac_detay ON araclar.id = arac_detay.arac_id
    JOIN 
        araclar_subeler ON arac_detay.id = araclar_subeler.arac_detay_id
    JOIN 
        subeler ON araclar_subeler.sube_id = subeler.id
    WHERE 
        subeler.il = '$pickupCity'
        AND subeler.ilce = '$pickupDistrict'
        AND TIME('$pickupTime') BETWEEN subeler.is_basi AND subeler.is_sonu
        AND TIME('$returnTime') BETWEEN subeler.is_basi AND subeler.is_sonu
        AND arac_detay.id NOT IN (
            SELECT 
                arac_detay_id 
            FROM 
                rezervasyon 
            WHERE 
                (
                    ('$pickupDate' BETWEEN teslim_alinacak_tarih AND teslim_edilecek_tarih)
                    OR ('$returnDate' BETWEEN teslim_alinacak_tarih AND teslim_edilecek_tarih)
                    OR (teslim_alinacak_tarih BETWEEN '$pickupDate' AND '$returnDate')
                    OR (teslim_edilecek_tarih BETWEEN '$pickupDate' AND '$returnDate')
                )
        )
    GROUP BY 
        araclar.id
";

$result = mysqli_query($baglan, $sql);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filtrelenmiş Araçlar</title>
    
     <link rel="stylesheet" href="css/basics.css">
    
    <style>
        .container {
            max-width: 100%;
            margin: 40px auto;
            padding: 20px;
            background-color: white;
        }
        
        h2 {
            text-align: center;
            color: #3183e0;
            font-weight: bold;
        }
        
        .car-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
            margin-top: 20px;
        }
        
        .car-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 2px 6px #3183e0;
            overflow: hidden;
            position: relative;
            padding: 20px;
        }
        
        .car-card img {
            width: 100%;
            object-fit: contain;
            display: block;
        }
        
        .car-info {
            text-align: center;
            margin-top: 10px;
        }
        
        .car-info h3 {
            color: #3183e0;
            font-size: 1.9em;
            margin: 10px 0;
            font-weight: bold;
        }
        
        .car-features {
            display: flex;
            justify-content: space-between;
            text-align: left;
            margin-top: 10px;
        }
        
        .car-features div {
            width: 45%;
        }
        
        .car-features h4 {
            color: #3183e0;
            font-size: 1.7em;
            margin-bottom: 5px;
        }
        
        .car-features p {
            margin: 5px 0;
            font-size: 1.3em;
            color: #333;
        }
        
        .car-action {
            text-align: center;
            margin-top: 20px;
        }
        
        .car-action a {
            display: inline-block;
            padding: 15px 30px;
            background-color: #3183e0;
            color: white;
            text-decoration: none;
            border-radius: 20px;
            font-weight: bold;
        }
        
        .car-action a:hover {
            background-color: #106db0;
        }
        
        @media screen and (max-width: 2560px) { 
            .container {
                max-width: 2560px;
            }
            .car-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }
        
        @media screen and (max-width: 1920px) { 
            .container {
                max-width: 1920px;
            }
            .car-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        @media screen and (max-width: 1500px) { 
            .container {
                max-width: 1450px;
            }
            .car-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            header a {
                font-size: 1.5em;
            }
        }
        
        @media screen and (max-width: 1250px) { 
            header a {
                font-size: 1.2em;
            }
        }
        
        @media screen and (max-width: 1030px) { 
            .car-grid {
                grid-template-columns: 1fr;
            }
            header a {
                font-size: 1em;
            }
            header {
                padding: 5px 15px 5px 5%; 
            }
            nav a {
                font-size: 1em;
            }
            h2 {
                font-size: 1.5em;
            }
            .container {
                padding: 10px;
            }
            footer {
                font-size: 1.4em;
            }
        } 
        
    </style>
</head>
<body>
    <header>
        <a href="ana_sayfa.php"><h1>Araba Kiralama Hizmeti</h1></a>
        <a href="uye_girisi.php" class="login-button">Üye Girişi</a>
        <a href="uye_ol.php" class="login-button">Üye Ol</a>
    </header>

    <nav>
        <a href="filomuz.php">Filomuz</a>
        <a href="şubelerimiz.php">Şubelerimiz</a>
        <a href="#">Hakkımızda</a>
        <a href="#">İletişim</a>
    </nav>

    <div class="container">
        <h2>UYGUN ARAÇLAR</h2>
        <?php
        if (mysqli_num_rows($result) > 0) {
            echo '<div class="car-grid">';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="car-card">';
                // Araç fotoğrafı ve marka/model/yıl bilgisi
                echo '<img src="' . htmlspecialchars($row['fotograf']) . '" alt="' . htmlspecialchars($row['marka'] . ' ' . $row['model']) . '">';
                echo '<div class="car-info">';
                echo '<h3>' . htmlspecialchars($row['marka']) . ' ' . htmlspecialchars($row['model']) . ' ' . htmlspecialchars($row['yil']) . '</h3>';
                echo '</div>';

                // Araç özellikleri
                echo '<div class="car-features">';
                echo '<div>';
                echo '<h4>Araç Özellikleri</h4>';
                echo '<p>🧑‍🤝‍🧑 ' . htmlspecialchars($row['kisi_kapasitesi']) . ' Yetişkin</p>';
                echo '<p>🧳 ' . htmlspecialchars($row['bagaj_hacmi']) . '</p>';
                echo '<p>⛽ ' . htmlspecialchars($row['motor_tipi']) . '</p>';
                echo '<p>⚙️ ' . htmlspecialchars($row['vites_tipi']) . '</p>';
                echo '</div>';

                // Kiralama koşulları ve hesaplaması
                $pickupDateTime   = new DateTime($pickupDate . ' ' . $pickupTime);
                $returnDateTime   = new DateTime($returnDate . ' ' . $returnTime);
                $interval         = $pickupDateTime->diff($returnDateTime);
                $totalHours       = $interval->days * 24 + $interval->h + ($interval->i > 0 ? 1 : 0);
                $dayCount         = ceil($totalHours / 24);
                $totalPrice       = $dayCount * $row['fiyat'];

                echo '<div>';
                echo '<h4>Kiralama Koşulları</h4>';
                echo '<p>📅 ' . htmlspecialchars($row['yas_sarti']) . ' Yaş ve Üstü</p>';
                echo '<p>🚗 Ehliyet Yaşı ' . htmlspecialchars($row['ehliyet_yasi']) . ' ve Üzeri</p>';
                echo '<p>💰 Günlük Fiyat: ' . htmlspecialchars($row['fiyat']) . ' TL</p>';
                echo '<p>💵 Toplam Fiyat: ' . $totalPrice . ' TL</p>';
                echo '</div>';
                echo '</div>';

                // “Hemen Kirala” butonu → Bu aşamada ilgili aracın ID'si (araclar.id) session veya başka bir sayfada kullanılmak üzere gönderiliyor.
                echo '<div class="car-action">';
                echo '<a href="store_car_id.php?arac_id=' . urlencode($row['id']) . '">Hemen Kirala</a>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo '<p>Filtreye uygun araç bulunmamaktadır.</p>';
        }
        ?>
    </div>

    <footer>
        <p>&copy; 2024 Rent a Car / Tüm hakları saklıdır.</p>
    </footer>
</body>
</html>
