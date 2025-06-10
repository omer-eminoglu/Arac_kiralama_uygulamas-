<?php

session_start();
include("baglanti.php");

if (isset($_SESSION['arac_id'])) {
    $arac_id = $_SESSION['arac_id'];

    $sql = "
    SELECT araclar.*, paketler.* 
    FROM araclar, paketler
    WHERE araclar.id = $arac_id
";

    $result = mysqli_query($baglan, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        echo "Bu araç bulunamadı.";
        exit();
    }
} else {
    echo "Araç ID'si mevcut değil.";
    exit();
}    
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezervasyon Formu</title>
    
     <link rel="stylesheet" href="css/basics.css">
    
    <style>
        .container {
            display: flex;
            max-width: 65%;
            margin: 40px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .car-info {
            flex: 2;
            margin-right: 20px;
        }
        .car-info img {
            width: 100%;
            border-radius: 10px;
            padding: 10px;
        }
        .car-info h3 {
            text-align: center;
            color: #3183e0;
            margin-top: 10px;
            font-size: 2em;
            font-weight: bolder;
        }
        .car-info p {
            margin: 10px 30px;
            font-size: 1.2em;
            color: #333;
        }
        .reservation-form {
            flex: 1;
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .reservation-form h2 {
            text-align: center;
            color: #3183e0;
            font-size: 1.9em;
        }
        .reservation-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
            font-size: 1.3em;
        }
        .reservation-form div select, .reservation-form div input {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 1.3em;
        }
        .reservation-form input {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 1.3em;
        }
        .reservation-form button {
            width: 100%;
            padding: 15px;
            background-color: #3183e0;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.5em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .reservation-form button:hover {
            background-color: #106db0;
        }
          @media screen and (max-width: 1500px) { 
            .container {
                max-width: 1450px;
            }
              header a{
               font-size: 1.5em;
            }
        }
       @media screen and (max-width: 1250px) {
    .container {
        flex-direction: column;
        max-width: 90%;
        margin: 20px auto;
    }
    .car-info, .reservation-form {
        width: 96%;
        margin-right: 0;
    }
    .car-info img {
        width: 100%;
        height: auto;
    }
           header a{
               font-size: 1.2em;
            }
}

@media screen and (max-width: 768px) {
    .container {
        padding: 10px;
    }
    .car-info h3, .reservation-form h2 {
        font-size: 1.5em;
    }
    .reservation-form label, .reservation-form div select, .reservation-form div input, .reservation-form button {
        font-size: 1em;
    }
    .car-info, .reservation-form {
        width: 90%;
        margin-right: 0;
    }
}
        

@media screen and (max-width: 480px) {
    nav a {
        font-size: 1.2em;
    }
    header a, .login-button {
        font-size: 1.2em;
    }
    .car-info p {
        font-size: 1em;
    }
    .reservation-form label, .reservation-form div select, .reservation-form div input, .reservation-form button {
        font-size: 0.9em;
    }
    .reservation-form button {
        padding: 10px;
    }
}
        @media screen and (max-width: 1030px) { 
            .car-grid {
                grid-template-columns: 1fr;
            }
            header a{
               font-size: 1em;
            }
            header{
                 padding: 5px 15px 5px 5%; 
            }
            nav a{
               font-size: 1em;
            }
            h2 {
              font-size: 2em;
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
            footer {
                font-size: 1.4em;
            }
               
        }       
        
        .container1 {
            display: flex;
            max-width: 65%;
            margin: 40px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            align-items: center;
            justify-content: space-between; 
            gap: 1%;
            flex-direction: column;
        }
        .package-section {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            width: 100%;
        }
        .package {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            width: 32%;
            position: relative;
            text-align: center;
        }
        .package h3 {
            font-size: 1.8em;
            margin-bottom: 10px;
            color: #3183e0;
        }
        .package p {
            font-size: 1.5em;
            color: #333;
            margin: 5px 0;
        }
        .package .features {
            margin-top: 15px;
            text-align: left;
        }
        .package .features p {
            font-size: 1.2em;
            display: flex;
            align-items: center;
        }
        .package .features p span {
            margin-left: 10px;
        }
        .package button {
            background-color: #e60000;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 1.2em;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .package-selection {
            margin-top: 20px;
            text-align: center;
        }
        .package-selection select {
            padding: 10px;
            font-size: 1.2em;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }
        .next-step {
            margin-top: 20px;
            text-align: center;
        }
        .next-step button {
            background-color: #3183e0;
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 1.5em;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .next-step button:hover {
            background-color: #106db0;
        }
        .info-section {
            max-width: 65%;
            margin: 40px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .info-section h3 {
            color: #3183e0;
            font-size: 1.8em;
            margin-bottom: 10px;
        }
        .info-section p {
            font-size: 1.3em;
            line-height: 1.6;
            color: #333;
            margin-bottom: 20px;
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


};

    </script>
</head>
<body>
    <header>
       <a href="ana_sayfa.php"><h1>Araba Kiralama Hizmeti</h1></a>
       <a href="uye_girisi.php" class="login-button">Üye Girişi</a>
       <a href="uye_ol.php" class="login-button">Üye Ol</a>
    </header>

    <nav>
        <a href="filomuz.php">Filomuz</as>
        <a href="subelerimiz.php">Şubelerimiz</a>
        <a href="#">Hakkımızda</a>
        <a href="#">İletişim</a>
    </nav>

    <div class="container">
        <div class="car-info">
            <img src="<?php echo $row['fotograf']; ?>" alt="<?php echo $row['marka'] . ' ' . $row['model']; ?>">
            <h3><?php echo $row['marka'] . ' ' . $row['model']; ?></h3>
            <p><strong>Yıl: </strong><?php echo $row['yil']; ?></p>
            <p><strong>Vites Tipi: </strong><?php echo $row['vites_tipi']; ?></p>
            <p><strong>Yakıt Tipi: </strong><?php echo $row['motor_tipi']; ?></p>
            <p><strong>Bagaj Hacmi: </strong><?php echo $row['bagaj_hacmi']; ?></p>
            <p><strong>Kişi Kapasitesi: </strong><?php echo $row['kisi_kapasitesi']; ?></p>
            <p><strong>Araba Hakkında: </strong><?php echo $row['aciklama']; ?></p>
        </div>

        <div class="reservation-form">
            <h2>Rezervasyon Yap</h2>
            <form action="filter_check.php" method="post" >
                 <div>
                <label for="pickup-location">Teslim Alınacak Şehir ve İlçe:</label>
                <select id="pickup-location" name="pickup_location" required>
                    <option value="">Şehir ve İlçe Seçin</option>
                    <?php
                        include("baglanti.php");
                        $sql = "SELECT CONCAT(il, ' - ', ilce) AS il_ilce FROM subeler order by il_ilce";
                        $result = mysqli_query($baglan, $sql);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . $row['il_ilce'] . "'>" . $row['il_ilce'] . "</option>";
                        }
                    ?>
                </select>
            </div>

                <label for="pickup-date">Teslim Alınacak Tarih:</label>
                <input type="date" id="pickup-date" name="pickup_date" value="" required>

                <label for="pickup-time">Teslim Alınacak Saat:</label>
                <input type="time" id="pickup-time" name="pickup_time" value="" required>

                <div>
                <label for="return-location">Teslim Edilecek Şehir ve İlçe:</label>
                <select id="return-location" name="return_location" required>
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

                <label for="return-date">Teslim Edilecek Tarih:</label>
                <input type="date" id="return-date" name="return_date" value="" required>

                <label for="return-time">Teslim Edilecek Saat:</label>
                <input type="time" id="return-time" name="return_time" value="" required>


                <button type="submit">Rezervasyon Ara</button>
            </form>
        </div>
    </div>

   
    <footer>
        <p>&copy; 2024 Rent a Car / Tüm hakları saklıdır.</p>
    </footer>
</body>
</html>
