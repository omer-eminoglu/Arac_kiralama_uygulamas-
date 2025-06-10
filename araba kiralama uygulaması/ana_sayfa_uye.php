<?php
include("baglanti.php");
session_start();
?>
<!DOCTYPE html>   
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Araba Kiralama</title>
    
    <link rel="stylesheet" href="css/basics.css">
    <link rel="stylesheet" href="css/filter.css">
    
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
              font-size: 2.5em;
        }

        .car-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr); 
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
            font-weight: bold;
            margin-bottom: 5px;
        }

        .car-features p {
            margin: 5px 0;
            font-size: 1.3em;
            font-weight: bold;
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
            font-size: 1.3em;
        }

        .car-action a:hover {
            background-color: #106db0;
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
        @media screen and (max-width: 1075px) { 
            .car-grid {
                grid-template-columns: 1fr;
            }               
        }
        h2 {
            text-align: center;
            color: #3183e0;
            font-weight: bold;
              font-size: 1.5em;
        }
              .container {
            padding: 10px;

        }       
        
    </style>

    <script>
         window.onload = function() {
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0'); 
    const dd = String(today.getDate()).padStart(2, '0');
    const formattedToday = `${yyyy}-${mm}-${dd}`;
    
    const pickupDate = document.getElementById('pickup-date');
    const returnDate = document.getElementById('return-date');
    const pickupTime = document.getElementById('pickup-time');
    const returnTime = document.getElementById('return-time');
    const pickupLocation = document.getElementById('pickup-location');
    const returnLocation = document.getElementById('return-location');
    
    pickupDate.min = formattedToday;
    returnDate.min = formattedToday;

    pickupDate.addEventListener('change', function() {
        const selectedPickupDate = new Date(pickupDate.value);
        
        const minReturnDate = new Date(selectedPickupDate);
        minReturnDate.setDate(selectedPickupDate.getDate() + 1);

        const minReturnYear = minReturnDate.getFullYear();
        const minReturnMonth = String(minReturnDate.getMonth() + 1).padStart(2, '0');
        const minReturnDay = String(minReturnDate.getDate()).padStart(2, '0');
        returnDate.min = `${minReturnYear}-${minReturnMonth}-${minReturnDay}`;

        const defaultReturnDate = new Date(selectedPickupDate);
        defaultReturnDate.setDate(selectedPickupDate.getDate() + 2);

        const defaultReturnYear = defaultReturnDate.getFullYear();
        const defaultReturnMonth = String(defaultReturnDate.getMonth() + 1).padStart(2, '0');
        const defaultReturnDay = String(defaultReturnDate.getDate()).padStart(2, '0');
        returnDate.value = `${defaultReturnYear}-${defaultReturnMonth}-${defaultReturnDay}`;
    });

    pickupTime.addEventListener('change', function() {
        returnTime.value = pickupTime.value;
    });

    pickupLocation.addEventListener('change', function() {
        returnLocation.value = pickupLocation.value;
    });

    function createTimeOptions(selectElement) {
        const timeOptions = [];
        for (let hour = 0; hour < 24; hour++) {
            const hourString = String(hour).padStart(2, '0');
            timeOptions.push(`${hourString}:00`, `${hourString}:30`);
        }
        timeOptions.forEach(time => {
            const option = document.createElement('option');
            option.value = time;
            option.text = time;
            selectElement.add(option);
        });
    }
    createTimeOptions(pickupTime);
    createTimeOptions(returnTime);
};

    </script>
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
           echo' <a href="dashbord.php" class="login-button">' . $ad_soyad . ' </a>';
            echo '</div>';
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
    
   <div class="filter-background">
    <div class="filter-container">
        <form class="filter" method="POST" action="filter_results_uye.php">
            <h3>Filtreleme Seçenekleri</h3>
            <div>
                <label for="pickup-date">Teslim Alınacak Tarih:</label>
                <input type="date" id="pickup-date" name="pickup_date" required>
                <label for="pickup-time">Teslim Alınacak Saat:</label>
                <input type="time" id="pickup-time" name="pickup_time" required>
            </div>
            <div>
                <label for="return-date">Teslim Edilecek Tarih:</label>
                <input type="date" id="return-date" name="return_date" required>
                <label for="return-time">Teslim Edilecek Saat:</label>
                <input type="time" id="return-time" name="return_time" required>
            </div>
            <div>
                <label for="pickup-location">Teslim Alınacak Şehir ve İlçe:</label>
                <select id="pickup-location" name="pickup_location" required>
                    <option value="">Şehir ve İlçe Seçin</option>
                   <?php
include("baglanti.php");
$sql = "SELECT CONCAT(il, ' - ', ilce) AS il_ilce FROM subeler ORDER BY il ASC, ilce ASC";
$result = mysqli_query($baglan, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    echo "<option value='" . $row['il_ilce'] . "'>" . $row['il_ilce'] . "</option>";
}
?>
                </select>
            </div>
            <div>
                <label for="return-location">Teslim Edilecek Şehir ve İlçe:</label>
                <select id="return-location" name="return_location" required>
                    <option value="">Şehir ve İlçe Seçin</option>
                    <?php
                        $result = mysqli_query($baglan, $sql);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . $row['il_ilce'] . "'>" . $row['il_ilce'] . "</option>";
                        }
                    ?>
                </select>
            </div>
            <button type="submit">Araç Bul</button>
        </form>
    </div>
    <div>
      <div class="container">
        <h2>----------------En Yeni Araçlar----------------</h2>

          <?php
        include("baglanti.php");
         $sql = "SELECT * FROM araclar ORDER BY id DESC LIMIT 4";
        $result = mysqli_query($baglan, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo '<div class="car-grid">';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="car-card">';
                echo '<img src="' . $row['fotograf'] . '" alt="' . $row['marka'] . ' ' . $row['model'] . '">';
                echo '<div class="car-info">';
                echo '<h3>' . $row['marka'] . ' ' . $row['model'] . ' ' . $row['yil'] . '</h3>';
                echo '</div>';
                
                echo '<div class="car-features">';
                echo '<div>';
                echo '<h4>Araç Özellikleri</h4>';
                echo '<p>🧑‍🤝‍🧑 ' . $row['kisi_kapasitesi'] . ' Yetişkin</p>';
                echo '<p>🧳 ' . $row['bagaj_hacmi'] . '</p>';
                echo '<p>⛽ ' . $row['motor_tipi'] . '</p>';
                echo '<p>⚙️ ' . $row['vites_tipi'] . '</p>';
                echo '</div>';
                echo '<div>';
                echo '<h4>Kiralama Koşulları</h4>';
                echo '<p>📅 ' . $row['yas_sarti'] . ' Yaş ve Üstü</p>';
                echo '<p>🚗 Ehliyet Yaşı ' . $row['ehliyet_yasi'] . ' ve Üzeri</p>';
                echo '</div>';
                echo '</div>';

            echo '<div class="car-action">';
echo '<a href="store_car_id1.php?arac_id=' . $row['id'] . '">Hemen Kirala</a>'; 
echo '</div>';
        echo '</div>';
                
            }
            echo '</div>';
        } else {
            echo '<p>Henüz araç bulunmamaktadır.</p>';
        }
        ?>
    </div>

    <footer>
        <p>&copy; 2024 Rent a Car / Tüm hakları saklıdır.</p>
    </footer>
</body>
</html>
