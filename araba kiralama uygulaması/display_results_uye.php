<?php
session_start();
include("baglanti.php");
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
        
        @media screen and (max-width: 1500px) { 
            header a {
                font-size: 1.3em;
            }
        }
        
        @media screen and (max-width: 1300px) { 
            header a {
                font-size: 1em;
            } 
            .login-button {
                font-size: 1em;
            }
        }
        
        @media screen and (max-width: 1030px) { 
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
            footer {
                font-size: 1.4em;
            }
        }
        
        @media screen and (max-width: 768px) {
            header a {
                font-size: 0.9em;
            }
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
        }
        
        @media screen and (max-width: 1030px) { 
            .car-grid {
                grid-template-columns: 1fr;
            }
        }
        
    </style>
</head>
<body>
    <header>
        <a href="ana_sayfa_uye.php"><h1>Araba Kiralama Hizmeti</h1></a>
        <?php
        if (isset($_SESSION['musteri_id'])) {
            $musteri_id = $_SESSION['musteri_id'];
            $sql = "SELECT ad_soyad FROM musteriler WHERE id = $musteri_id";
            $result = mysqli_query($baglan, $sql);
            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $ad_soyad = $row['ad_soyad'];
                echo '<a href="dashbord.php" class="login-button">' . htmlspecialchars($ad_soyad) . '</a>';
            }
        }
        ?>
        <a href="ana_sayfa.php" class="login-button">Çıkış Yap</a>    
    </header>

    <nav>
        <a href="filomuz_uye.php">Filomuz</a>
        <a href="şubelerimiz_uye.php">Şubelerimiz</a>
        <a href="#">Hakkımızda</a>
        <a href="#">İletişim</a>
    </nav>

    <div class="container">
        <h2>UYGUN ARAÇLAR</h2>
        <?php
        $pickupDate     = $_SESSION['pickup_date'] ?? '';
        $pickupTime     = $_SESSION['pickup_time'] ?? '';
        $returnDate     = $_SESSION['return_date'] ?? '';
        $returnTime     = $_SESSION['return_time'] ?? '';
        $pickupCity     = $_SESSION['pickup_city'] ?? '';
        $pickupDistrict = $_SESSION['pickup_district'] ?? '';
        $returnCity     = $_SESSION['return_city'] ?? '';
        $returnDistrict = $_SESSION['return_district'] ?? '';

        $sql = "
            SELECT
                araclar.*,
                arac_detay.id AS arac_detay_id,
                arac_detay.arac_id AS base_arac_id,
                arac_detay.plaka,
                arac_detay.renk,
                araclar.fiyat AS gunluk_fiyat
            FROM araclar
            INNER JOIN arac_detay 
                ON araclar.id = arac_detay.arac_id
            INNER JOIN araclar_subeler 
                ON arac_detay.id = araclar_subeler.arac_detay_id
            INNER JOIN subeler 
                ON araclar_subeler.sube_id = subeler.id
            WHERE 
                subeler.il   = ? 
              AND subeler.ilce = ?
              AND TIME(?) BETWEEN subeler.is_basi AND subeler.is_sonu
              AND TIME(?) BETWEEN subeler.is_basi AND subeler.is_sonu
              AND arac_detay.id NOT IN (
                  SELECT arac_detay_id 
                  FROM rezervasyon 
                  WHERE (
                      (? BETWEEN teslim_alinacak_tarih AND teslim_edilecek_tarih)
                      OR (? BETWEEN teslim_alinacak_tarih AND teslim_edilecek_tarih)
                      OR (teslim_alinacak_tarih BETWEEN ? AND ?)
                      OR (teslim_edilecek_tarih BETWEEN ? AND ?)
                  )
              )
            GROUP BY araclar.id
        ";

        $stmt = $baglan->prepare($sql);
        $stmt->bind_param(
            "ssssssssss",
            $pickupCity,
            $pickupDistrict,
            $pickupTime,
            $returnTime,
            $pickupDate,
            $returnDate,
            $pickupDate,
            $returnDate,
            $pickupDate,
            $returnDate
        );
        $stmt->execute();
        $result = $stmt->get_result();
        ?>

        <div class="car-grid">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="car-card">
                    <!-- Araç fotoğrafı -->
                    <img 
                        src="<?= htmlspecialchars($row['fotograf']) ?>" 
                        alt="<?= htmlspecialchars($row['marka'] . ' ' . $row['model']) ?>"
                    >

                    <div class="car-info">
                        <h3>
                            <?= htmlspecialchars($row['marka'] . ' ' . $row['model'] . ' ' . $row['yil']) ?>
                        </h3>
                    </div>

                    <div class="car-features">
                        <div>
                            <h4>Araç Özellikleri</h4>
                            <p>🦑 <?= htmlspecialchars($row['kisi_kapasitesi']) ?> Yetişkin</p>
                            <p>🚒 <?= htmlspecialchars($row['bagaj_hacmi']) ?></p>
                            <p>⛽ <?= htmlspecialchars($row['motor_tipi']) ?></p>
                            <p>⚙️ <?= htmlspecialchars($row['vites_tipi']) ?></p>
                        </div>
                        <div>
                            <h4>Kiralama Koşulları</h4>
                            <p>🗓️ <?= htmlspecialchars($row['yas_sarti']) ?> Yaş ve Üstü</p>
                            <p>🚗 Ehliyet Yaşı <?= htmlspecialchars($row['ehliyet_yasi']) ?> ve Üzeri</p>
                            <p>💰 Günlük Fiyat: <?= htmlspecialchars($row['gunluk_fiyat']) ?> TL</p>

                            <?php 
                            $pickupDateTime = new DateTime($pickupDate . ' ' . $pickupTime);
                            $returnDateTime = new DateTime($returnDate . ' ' . $returnTime);
                            $interval      = $pickupDateTime->diff($returnDateTime);
                            $totalHours    = $interval->days * 24 + $interval->h + ($interval->i > 0 ? 1 : 0); 
                            $dayCount      = ceil($totalHours / 24);
                            $totalPrice    = $dayCount * $row['gunluk_fiyat'];
                            ?>
                            <p>💵 Toplam Fiyat: <?= htmlspecialchars($totalPrice) ?> TL</p>
                        </div>
                    </div>

                    <div class="car-action">
                        <a  href="store_car_id_uye.php?arac_id=<?= urlencode($row['id']) ?>" >
                            Hemen Kirala
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Rent a Car / Tüm hakları saklıdır.</p>
    </footer>
</body>
</html>
