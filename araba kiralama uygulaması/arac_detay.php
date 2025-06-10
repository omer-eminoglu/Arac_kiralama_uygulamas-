<?php
session_start();
include("baglanti.php");

if (!isset($_GET['arac_detay_id'])) {
    die("<p>Geçerli bir araç seçilmedi.</p>");
}

$aracDetayId = (int) $_GET['arac_detay_id'];
$_SESSION['arac_detay_id'] = $aracDetayId;

$sql = "
    SELECT 
        araclar.id            AS arac_id,
        araclar.marka,
        araclar.model,
        araclar.yil,
        araclar.motor_hacmi,
        araclar.motor_tipi,
        araclar.vites_tipi,
        -- araclar.plaka  // ← BURASI ARTIK KALDIRILDI
        arac_detay.plaka      AS plaka,
        arac_detay.kilometre  AS kilometre,
        arac_detay.renk,
        arac_detay.donanim,
        arac_detay.on_foto,
        arac_detay.ic_foto,
        arac_detay.sag_foto,
        arac_detay.sol_foto,
        arac_detay.arka_foto,
        rezervasyon.teslim_alinacak_tarih,
        rezervasyon.teslim_alinacak_saat,
        rezervasyon.teslim_edilecek_tarih,
        rezervasyon.teslim_edilecek_saat,
        rezervasyon.musteri_id,
        musteriler.ad_soyad,
        odeme.toplam_fiyat,
        subeler.il,
        subeler.ilce
    FROM araclar
    JOIN arac_detay     ON araclar.id      = arac_detay.arac_id
    LEFT JOIN rezervasyon ON arac_detay.id   = rezervasyon.arac_detay_id
    LEFT JOIN musteriler  ON rezervasyon.musteri_id   = musteriler.id
    LEFT JOIN odeme       ON rezervasyon.odeme_id     = odeme.id
    LEFT JOIN subeler     ON rezervasyon.teslim_alinacak_sube_id = subeler.id
    WHERE arac_detay.id = $aracDetayId
";
$result = mysqli_query($baglan, $sql);

if (!$result || mysqli_num_rows($result) === 0) {
    die("<p>Bu araçla ilgili bilgi bulunamadı. ID: " . htmlspecialchars($aracDetayId) . "</p>");
}

$row = mysqli_fetch_assoc($result);
$_SESSION['arac_id'] = $row['arac_id'];
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>        
        <?php 
            echo htmlspecialchars($row['marka'] . " " . $row['model']) . " Detayları";
        ?>
    </title>
    
     <link rel="stylesheet" href="css/basics.css">
    
    <style>
      
        .container {
            width: 1250px;
            max-width: 100%;
            margin: 20px auto;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 10px;
        }
        
        .details {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .details table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .details th, .details td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        
        .details th {
            background-color: #3183e0;
            color: white;
            width: 35%;
        }
        
        .details td {
            background-color: #f9f9f9;
        }
        
        .action-button {
            display: block;
            width: 97%;
            margin: 20px auto 0;
            text-align: center;
            background-color: #3183e0;
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        
        .action-button:hover {
            background-color: #106db0;
        }
        
        .gallery {
            display: flex;
            gap: 20px;
            margin: 20px 0;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .main-photo {
            width: 950px;
            max-width: 100%;
            height: 700px;
            max-height: 80vh;
            object-fit: contain;
            cursor: pointer;
            background-color: white;
        }
        
        .thumbnails {
            display: flex;
            flex-direction: column;
            gap: 5px;
            max-height: 700px;
            overflow-y: auto;
        }
        
        .thumbnails img {
            width: 200px;
            height: auto;
            cursor: pointer;
            border: 2px solid #ddd;
            border-radius: 5px;
            object-fit: contain;
        }
        
        .thumbnails img:hover {
            border: 2px solid #3183e0;
        }
        
        @media (max-width: 1500px) {
            body {
                transform: scale(0.9);
                transform-origin: top left;
                width: 112%;
            }
        }
        
        @media (max-width: 1410px) {
            .container {
                width: 1000px;
                padding: 20px;
            }
            .details {
                display: block;
            }
            .details th, .details td {
                font-size: 1.4em;
            }
            .action-button {
                font-size: 1.4em;
            }
            .gallery {
                display: contents;
            }
            .main-photo {
                width: 950px;
                height: 700px;            
            }
            .thumbnails {
                flex-direction: row;
                overflow-x: auto;
                max-height: 200px;
            }
            .thumbnails img {
                width: 100%;
                height: 150px;
            }
        }
        
        @media (max-width: 1040px) {
            .container {
                width: 850px;
                padding: 10px;
            }     
            .main-photo {
                width: 840px;
                height: 600px;            
            }
        }
        
        @media (max-width: 850px) {
            .container {
                width: 630px;
                padding: 10px;
            }     
            .main-photo {
                width: 620px;
                height: 450px;            
            }
        }
       
    </style>
    <script>
        function changePhoto(src) {
            document.getElementById('mainPhoto').src = src;
        }
        function openFullscreen(src) {
            const img = document.createElement('img');
            img.src = src;
            img.style.width = '100%';
            img.style.height = '100%';
            img.style.objectFit = 'contain';

            const overlay = document.createElement('div');
            overlay.style.position = 'fixed';
            overlay.style.top = '0';
            overlay.style.left = '0';
            overlay.style.width = '100%';
            overlay.style.height = '100%';
            overlay.style.backgroundColor = 'rgba(0,0,0,0.8)';
            overlay.style.display = 'flex';
            overlay.style.alignItems = 'center';
            overlay.style.justifyContent = 'center';
            overlay.style.zIndex = '9999';
            overlay.appendChild(img);

            overlay.addEventListener('click', () => {
                document.body.removeChild(overlay);
            });

            document.body.appendChild(overlay);
        }
    </script>
</head>
<body>
    <header>
        <a href="ana_sayfa_uye.php"><h1>Araba Kiralama Hizmeti</h1></a>
        <?php
            if (isset($_SESSION['musteri_id'])) {
                $musteri_id = (int) $_SESSION['musteri_id'];
                $sql2 = "SELECT ad_soyad FROM musteriler WHERE id = $musteri_id";
                $res2 = mysqli_query($baglan, $sql2);
                if ($res2 && mysqli_num_rows($res2) > 0) {
                    $row2 = mysqli_fetch_assoc($res2);
                    echo '<a href="dashbord.php" class="login-button">'
                         . htmlspecialchars($row2['ad_soyad']) 
                         . '</a>';
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
        <div class="gallery">
            <img 
                id="mainPhoto" 
                class="main-photo" 
                src="<?php echo htmlspecialchars($row['on_foto']); ?>" 
                alt="Ana Fotoğraf" 
                onclick="openFullscreen(this.src)">
            
            <div class="thumbnails">
                <?php if (!empty($row['ic_foto'])): ?>
                    <img src="<?php echo htmlspecialchars($row['ic_foto']); ?>" alt="İç Görünüm" onclick="changePhoto(this.src)">
                <?php endif; ?>
                <?php if (!empty($row['sag_foto'])): ?>
                    <img src="<?php echo htmlspecialchars($row['sag_foto']); ?>" alt="Sağ Görünüm" onclick="changePhoto(this.src)">
                <?php endif; ?>
                <?php if (!empty($row['sol_foto'])): ?>
                    <img src="<?php echo htmlspecialchars($row['sol_foto']); ?>" alt="Sol Görünüm" onclick="changePhoto(this.src)">
                <?php endif; ?>
                <?php if (!empty($row['on_foto'])): ?>
                    <img src="<?php echo htmlspecialchars($row['on_foto']); ?>" alt="Ön Görünüm" onclick="changePhoto(this.src)">
                <?php endif; ?>
                <?php if (!empty($row['arka_foto'])): ?>
                    <img src="<?php echo htmlspecialchars($row['arka_foto']); ?>" alt="Arka Görünüm" onclick="changePhoto(this.src)">
                <?php endif; ?>
            </div>
        </div>

        <div class="details">
            <table>
                <tr>
                    <th colspan="2" style="text-align:center;">
                        <?php echo htmlspecialchars($row['marka'] . " " . $row['model']); ?>
                    </th>
                </tr>
                <tr>
                    <th>Yıl</th>
                    <td><?php echo htmlspecialchars($row['yil']); ?></td>
                </tr>
                <tr>
                    <th>Motor Hacmi</th>
                    <td><?php echo htmlspecialchars($row['motor_hacmi']); ?></td>
                </tr>
                <tr>
                    <th>Motor Tipi</th>
                    <td><?php echo htmlspecialchars($row['motor_tipi']); ?></td>
                </tr>
                <tr>
                    <th>Vites Tipi</th>
                    <td><?php echo htmlspecialchars($row['vites_tipi']); ?></td>
                </tr>
                <tr>
                    <th>Plaka</th>
                    <td><?php echo htmlspecialchars($row['plaka']); ?></td>
                </tr>
                <tr>
                    <th>Kilometre</th>
                    <td><?php echo htmlspecialchars($row['kilometre']); ?> km</td>
                </tr>
                <tr>
                    <th>Renk</th>
                    <td><?php echo htmlspecialchars($row['renk']); ?></td>
                </tr>
                <tr>
                    <th>Donanım</th>
                    <td><?php echo htmlspecialchars($row['donanim']); ?></td>
                </tr>
            </table>

            <?php if (!empty($row['ad_soyad'])): ?>
                <table>
                    <tr>
                        <th colspan="2" style="text-align:center;">Rezervasyon Bilgileri</th>
                    </tr>
                    <tr>
                        <th>Kiralayan Kişi</th>
                        <td><?php echo htmlspecialchars($row['ad_soyad']); ?></td>
                    </tr>
                    <tr>
                        <th>Teslim Alınan Şube</th>
                        <td><?php echo htmlspecialchars($row['il'] . " - " . $row['ilce']);?></td>
                    </tr>
                    <tr>
                        <th>Teslim Edilen Şube</th>
                        <td><?php 
                            echo htmlspecialchars($row['il'] . " - " . $row['ilce']);
                        ?></td>
                    </tr>
                    <tr>
                        <th>Teslim Alınan Tarih</th>
                        <td><?php echo htmlspecialchars($row['teslim_alinacak_tarih']); ?></td>
                    </tr>
                    <tr>
                        <th>Teslim Alınan Saat</th>
                        <td><?php echo htmlspecialchars(date('H:i', strtotime($row['teslim_alinacak_saat']))); ?></td>
                    </tr>
                    <tr>
                        <th>Teslim Edilen Tarih</th>
                        <td><?php echo htmlspecialchars($row['teslim_edilecek_tarih']); ?></td>
                    </tr>
                    <tr>
                        <th>Teslim Edilen Saat</th>
                        <td><?php echo htmlspecialchars(date('H:i', strtotime($row['teslim_edilecek_saat']))); ?></td>
                    </tr>
                    <tr>
                        <th>Toplam Kiralama Tutarı</th>
                        <td><?php echo htmlspecialchars($row['toplam_fiyat']); ?>.00 TL</td>
                    </tr>
                </table>
                <a href="rezervasyon_formu1_uye.php" class="action-button">Yeniden Kirala</a>
            <?php else: ?>
                <p style="margin:20px 0; text-align:center;">Bu araç için aktif bir rezervasyon yok.</p>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Rent a Car / Tüm hakları saklıdır.</p>
    </footer>
</body>
</html>
