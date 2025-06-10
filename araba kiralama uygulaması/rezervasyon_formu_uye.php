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
        echo "Bu arac bulunamadi.";
        exit();
    }
} else {
    echo "Arac ID'si mevcut degil.";
    exit();
}

$pickupDate = $_SESSION['pickup_date'];
$pickupTime = $_SESSION['pickup_time'];
$returnDate = $_SESSION['return_date'];
$returnTime = $_SESSION['return_time'];
$pickupCity = $_SESSION['pickup_city'];
$pickupDistrict = $_SESSION['pickup_district'];
$returnCity = $_SESSION['return_city'];
$returnDistrict = $_SESSION['return_district'];

$pickupDateTime = new DateTime($pickupDate . ' ' . $pickupTime);
$returnDateTime = new DateTime($returnDate . ' ' . $returnTime);
$interval = $pickupDateTime->diff($returnDateTime);

$totalHours = $interval->days * 24 + $interval->h + ($interval->i > 0 ? 1 : 0); 
$dayCount = ceil($totalHours / 24);

$totalPrice = $dayCount * $row['fiyat'];
$paketfiyatmini = $dayCount * ($row['mini']);
$paketfiyatstandart = $dayCount * ($row['standart']);
$paketfiyatfull = $dayCount * ($row['full']);
?>
  <?php 
$sql_prices = "SELECT ek, genc, cocuk FROM paketler WHERE id = 1"; 
$result_prices = mysqli_query($baglan, $sql_prices);
$prices = mysqli_fetch_assoc($result_prices);

$ekSurucuFiyat = $prices['ek'];
$gencSurucuFiyat = $prices['genc'];
$cocukKoltuguFiyat = $prices['cocuk'];
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezervasyon Formu</title>
    
     <link rel="stylesheet" href="css/basics.css">
    
<style>
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
        .reservation-form div {
            flex-basis: 45%;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
        }
        .reservation-form div select, .reservation-form div input {
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 1em;
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
        
    .extra-products h2 {
        color:  #3183e0;
        font-size: 1.8em;
        margin-bottom: 15px;
    }
    .product {
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .product h3 {
        color:  #3183e0;
    }
    .product-control {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .product-control button {
        background-color:  #3183e0;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1.2em;
    }
    .product-control span {
        font-size: 1.5em;
        font-weight: bold;
    }
          .container, .container1, .extra-products, .info-section {
    max-width: 90%; 
    padding: 10px;
    margin: 20px auto;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    background-color: white;
}

.car-info, .reservation-form {
    width: 90%;
    margin-bottom: 20px;
}

.car-info img {
    width: 100%; 
    height: auto; 
}

.package-section {
    flex-direction: column; 
    gap: 10px;
}

.package {
    width: 90%; 
    margin-bottom: 15px;
}
     
      

@media (min-width: 768px) {
    .container {
        display: flex;
        flex-direction: row;
        max-width: 80%; 
    }
    .car-info, .reservation-form {
        flex: 1;
        margin: 10px;
    }
    .package-section {
        flex-direction: row;
    }
    .package {
        width: 32%; 
    }
}

@media (min-width: 1250px) {
    .container, .container1, .extra-products, .info-section {
        max-width: 75%; 
    }
     header a{
               font-size: 1.2em;
            }
}

        @media screen and (max-width: 1500px) { 
              header a{
               font-size: 1em;
            }
        }
        @media screen and (max-width: 1030px) { 
         
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
        
    </style>
   <script>
        window.onload = function() {
            scrollToPackageSection();
            updateDayCount();
        };
        function updateDayCount() {
            const pickupDate = new Date(document.getElementById('pickup-date').value + ' ' + document.getElementById('pickup-time').value);
            const returnDate = new Date(document.getElementById('return-date').value + ' ' + document.getElementById('return-time').value);
            const timeDiff = returnDate - pickupDate;
            const totalHours = Math.ceil(timeDiff / (1000 * 60 * 60));
            const dayCount = Math.ceil(totalHours / 24);

            sessionStorage.setItem('dayCount', dayCount);
            updateTotalPrice();
        }
        function updateTotalPrice() {
            const dayCount = parseInt(sessionStorage.getItem('dayCount')) || 0;
            const basePrice = dayCount * <?php echo $row['fiyat']; ?>;

            const miniPackagePrice = dayCount * <?php echo $row['mini']; ?>;
            const standartPackagePrice = dayCount * <?php echo $row['standart']; ?>;
            const fullPackagePrice = dayCount * <?php echo $row['full']; ?>;

            document.querySelectorAll('.package')[0].querySelector('.total-price').textContent = 'Toplam: ' + miniPackagePrice + ' TL';
            document.querySelectorAll('.package')[1].querySelector('.total-price').textContent = 'Toplam: ' + standartPackagePrice + ' TL';
            document.querySelectorAll('.package')[2].querySelector('.total-price').textContent = 'Toplam: ' + fullPackagePrice + ' TL';

            const packageSelect = document.getElementById('package-select');
            let selectedPackagePrice = 0;

            switch (packageSelect.selectedIndex) {
                case 1:
                    selectedPackagePrice = miniPackagePrice;
                    break;
                case 2:
                    selectedPackagePrice = standartPackagePrice;
                    break;
                case 3:
                    selectedPackagePrice = fullPackagePrice;
                    break;
                default:
                    selectedPackagePrice = 0;
                    break;
            }
            
            const extraDriverTotal = extraDriverCount * <?php echo $ekSurucuFiyat; ?> * dayCount;
            const youngDriverTotal = youngDriverCount * <?php echo $gencSurucuFiyat; ?> * dayCount;
            const childSeatTotal = childSeatCount * <?php echo $cocukKoltuguFiyat; ?> * dayCount;

            const totalPrice = basePrice + selectedPackagePrice + extraDriverTotal + youngDriverTotal + childSeatTotal;

            document.getElementById('total-price-button').innerText = totalPrice.toFixed(2) + ' TL - Ödemeye Geç';
        }

        let extraDriverCount = 0;
        let youngDriverCount = 0;
        let childSeatCount = 0;
        const maxChildSeats = 3;

        function updateDriver(count) {
            extraDriverCount = Math.max(0, extraDriverCount + count);
            document.getElementById('extra-driver-count').textContent = extraDriverCount;
            document.getElementById('extra-driver-total').textContent = (extraDriverCount * <?php echo $ekSurucuFiyat; ?> * parseInt(sessionStorage.getItem('dayCount') || 0)).toFixed(2) + " TL";
            updateTotalPrice();
        }

        function updateYoungDriver(count) {
            youngDriverCount = Math.max(0, Math.min(extraDriverCount + 1, youngDriverCount + count));
            document.getElementById('young-driver-count').textContent = youngDriverCount;
            document.getElementById('young-driver-total').textContent = (youngDriverCount * <?php echo $gencSurucuFiyat; ?> * parseInt(sessionStorage.getItem('dayCount') || 0)).toFixed(2) + " TL";
            updateTotalPrice();
        }

        function updateChildSeat(count) {
            childSeatCount = Math.max(0, Math.min(maxChildSeats, childSeatCount + count));
            document.getElementById('child-seat-count').textContent = childSeatCount;
            document.getElementById('child-seat-total').textContent = (childSeatCount * <?php echo $cocukKoltuguFiyat; ?> * parseInt(sessionStorage.getItem('dayCount') || 0)).toFixed(2) + " TL";
            updateTotalPrice();
        }

        function scrollToPackageSection() {
            document.querySelector('.container1').scrollIntoView({ behavior: 'smooth' });
        }

        function scrollToAdditionalSection() {
            document.querySelector('.extra-products').scrollIntoView({ behavior: 'smooth' });
        }

       document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('total-price-button').addEventListener('click', function() {
        const totalPriceText = document.getElementById('total-price-button').innerText;
        const totalPrice = parseInt(totalPriceText);

        const aracId = <?php echo json_encode($arac_id); ?>;
        const pickupDate = document.getElementById('pickup-date').value;
        const pickupTime = document.getElementById('pickup-time').value;
        const returnDate = document.getElementById('return-date').value;
        const returnTime = document.getElementById('return-time').value;
        const pickupLocation = document.getElementById('pickup-location').value;
        const returnLocation = document.getElementById('return-location').value;

        sessionStorage.setItem('totalPrice', totalPrice);
        sessionStorage.setItem('aracId', aracId);
        sessionStorage.setItem('pickupDate', pickupDate);
        sessionStorage.setItem('pickupTime', pickupTime);
        sessionStorage.setItem('returnDate', returnDate);
        sessionStorage.setItem('returnTime', returnTime);
        sessionStorage.setItem('pickupLocation', pickupLocation);
        sessionStorage.setItem('returnLocation', returnLocation);

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'odeme_result_uye.php';

        form.innerHTML = `
            <input type="hidden" name="totalPrice" value="${totalPrice}">
            <input type="hidden" name="aracId" value="${aracId}">
            <input type="hidden" name="pickupDate" value="${pickupDate}">
            <input type="hidden" name="pickupTime" value="${pickupTime}">
            <input type="hidden" name="returnDate" value="${returnDate}">
            <input type="hidden" name="returnTime" value="${returnTime}">
            <input type="hidden" name="pickupLocation" value="${pickupLocation}">
            <input type="hidden" name="returnLocation" value="${returnLocation}">
        `;
        document.body.appendChild(form);
        form.submit();
    });
});
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
        <a href="subelerimiz_uye.php">Subelerimiz</a>
        <a href="#">Hakkimizda</a>
        <a href="#">Iletisim</a>
    </nav>
    
    <div class="container">
        
        <?php
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
        echo "Bu arac bulunamadi.";
        exit();
    }
} else {
    echo "Arac ID'si mevcut degil.";
    exit();
}
        ?>
        
        <div class="car-info">
            <img src="<?php echo $row['fotograf']; ?>" alt="<?php echo $row['marka'] . ' ' . $row['model']; ?>">
            <h3><?php echo $row['marka'] . ' ' . $row['model']; ?></h3>
            <p><strong>Yil: </strong><?php echo $row['yil']; ?></p>
            <p><strong>Vites Tipi: </strong><?php echo $row['vites_tipi']; ?></p>
            <p><strong>Yakit Tipi: </strong><?php echo $row['motor_tipi']; ?></p>
            <p><strong>Bagaj Hacmi: </strong><?php echo $row['bagaj_hacmi']; ?></p>
            <p><strong>Kisi Kapasitesi: </strong><?php echo $row['kisi_kapasitesi']; ?></p>
            <p><strong>Araba Hakkinda: </strong><?php echo $row['aciklama']; ?></p>
        </div>

        <div class="reservation-form">
            <h2>Rezervasyon Onayı</h2>
            <form action="" method="post"  >
            <label for="pickup-location">Teslim Alınacak Yer:</label>
                <input type="text" id="pickup-location" name="pickup_location" value="<?php echo $pickupCity . ' - ' . $pickupDistrict; ?>" readonly>

                <label for="pickup-date">Teslim Alınacak Tarih:</label>
                <input type="date" id="pickup-date" name="pickup_date" value="<?php echo $pickupDate; ?>" readonly>

                <label for="pickup-time">Teslim Alınacak Saat:</label>
                <input type="time" id="pickup-time" name="pickup_time" value="<?php echo $pickupTime; ?>" readonly>

                <label for="return-location">Teslim Edilecek Yer:</label>
                <input type="text" id="return-location" name="return_location" value="<?php echo $returnCity . ' - ' . $returnDistrict; ?>" readonly>

                <label for="return-date">Teslim Edilecek Tarih:</label>
                <input type="date" id="return-date" name="return_date" value="<?php echo $returnDate; ?>" readonly>

                <label for="return-time">Teslim Edilecek Saat:</label>
                <input type="time" id="return-time" name="return_time" value="<?php echo $returnTime; ?>" readonly>


                <button type="button" onclick="scrollToPackageSection()" >Güvence Paketi Seçimine Geç</button>
            </form>
        </div>
    </div>
    
    
    <?php



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
        echo "Bu arac bulunamadi.";
        exit();
    }
} else {
    echo "Arac ID'si mevcut degil.";
    exit();
}

$pickupDate = $_SESSION['pickup_date'];
$pickupTime = $_SESSION['pickup_time'];
$returnDate = $_SESSION['return_date'];
$returnTime = $_SESSION['return_time'];
$pickupCity = $_SESSION['pickup_city'];
$pickupDistrict = $_SESSION['pickup_district'];
$returnCity = $_SESSION['return_city'];
$returnDistrict = $_SESSION['return_district'];

$pickupDateTime = new DateTime($pickupDate . ' ' . $pickupTime);
$returnDateTime = new DateTime($returnDate . ' ' . $returnTime);
$interval = $pickupDateTime->diff($returnDateTime);

$totalHours = $interval->days * 24 + $interval->h + ($interval->i > 0 ? 1 : 0); 
$dayCount = ceil($totalHours / 24);

$totalPrice = $dayCount * $row['fiyat'];
$paketfiyatmini = $dayCount * ($row['mini']);
$paketfiyatstandart = $dayCount * ($row['standart']);
$paketfiyatfull = $dayCount * ($row['full']);
?>
    

    <div class="container1">
        <div class="package-section">
            <div class="package">
                <h3>Mini Guvence Paketi</h3>
                <p>Gunluk <span class="daily-price"><?php echo $row['mini']; ?></span> TL | <span class="total-price">0.00 TL</span></p>
                
                <div class="features">
                    <p>✅ <span>Lastik, Cam, Far, Ayna Guvencesi</span></p>
                    <p>✅ <span>Super Mini Hasar Guvencesi</span></p>
                    <p>❌ <span>Ihtiyari Mali Mesuliyet Guvencesi</span></p>
                    <p>❌ <span>Ferdi Kaza Guvencesi</span></p>
                    <p>❌ <span>Mini Hasar Guvencesi</span></p>
                </div>
            </div>
            <div class="package">
                <h3>Standart Guvence Paketi</h3>
                <p>Gunluk <span class="daily-price"><?php echo $row['standart']; ?></span> TL | <span class="total-price">0.00 TL</span></p>
                <div class="features">
                    <p>✅ <span>Lastik, Cam, Far, Ayna Guvencesi</span></p>
                    <p>✅ <span>Super Mini Hasar Guvencesi</span></p>
                    <p>✅ <span>Ihtiyari Mali Mesuliyet Guvencesi</span></p>
                    <p>❌ <span>Ferdi Kaza Guvencesi</span></p>
                    <p>❌ <span>Mini Hasar Guvencesi</span></p>
                </div>
            </div>
            <div class="package">
                <h3>Full Guvence Paketi</h3>
                <p>Gunluk <span class="daily-price"><?php echo $row['full']; ?></span> TL | <span class="total-price">0.00 TL</span></p>
                <div class="features">
                    <p>✅ <span>Lastik, Cam, Far, Ayna Guvencesi</span></p>
                    <p>✅ <span>Super Mini Hasar Guvencesi</span></p>
                    <p>✅ <span>Ihtiyari Mali Mesuliyet Guvencesi</span></p>
                    <p>✅ <span>Ferdi Kaza Guvencesi</span></p>
                    <p>✅ <span>Mini Hasar Guvencesi</span></p>
                </div>
            </div>
        </div>
        <div class="package-selection">
            <label for="package-select">Paket Secimi:</label>
            <select id="package-select" onchange="updateTotalPrice()">
                <option value="0">Istemiyorum</option>
                <option value="<?php echo $paketfiyatmini; ?>">Mini Guvence Paketi</option>
                <option value="<?php echo $paketfiyatstandart; ?>">Standart Guvence Paketi</option>
                <option value="<?php echo $paketfiyatfull; ?>">Full Guvence Paketi</option>
            </select>
        </div>
        <div class="next-step">
            <button type="button" onclick="scrollToAdditionalSection()" >Ek Paket Seçimine Geç</button>
        </div>
    </div>

    <div class="info-section">
        <h3>Lastik, Cam, Far, Ayna Guvencesi</h3>
        <p>Kaza haricinde olusan lastik- cam – far – ayna hasarlarinda polis/jandarma raporu ya da kaza tespit tutanagi olmaksizin surucunun gecerli yazili beyanina istinaden aracin on farlari, on cami, yan aynalari ve arka stop lambalarinin onarilmasidir. LCFA urunu kaporta hasarlarini kapsamamaktadir.</p>

        <h3>Mini Hasar Guvencesi</h3>
        <p>Rapor duzenlenmesi veya tutanak tutulmasi mumkun olmayan, tek tarafli hasar durumlari icin musteriye 4.000 TL'ye kadar beyanla onarim hakki sunan hasar guvencesidir. Lastik-Cam-Far-Ayna hasarlarini kapsamamaktadir.</p>

        <h3>Ihtiyari Mali Mesuliyet Guvencesi</h3>
        <p>Trafik sigortasi kapsaminda 3. sahislara iliskin hasar limitini bu guvence ile 1.000.0000 TL'ye kadar artirabilirsiniz.</p>

        <h3>Ferdi Kaza Guvencesi</h3>
        <p>Arac icerisindeki koltuk sayisina bagli olarak, surucu ve arac icerisindeki kisileri kapsayan guvencedir.</p>
    </div>

    
    
   <div class="extra-products">
    <h2>Ek Ürünler</h2>
       
       
       <?php 
$sql_prices = "SELECT ek, genc, cocuk FROM paketler WHERE id = 1"; 
$result_prices = mysqli_query($baglan, $sql_prices);
$prices = mysqli_fetch_assoc($result_prices);

$ekSurucuFiyat = $prices['ek'];
$gencSurucuFiyat = $prices['genc'];
$cocukKoltuguFiyat = $prices['cocuk'];
?>
    <div class="product">
        <h3>Ek Sürücü</h3>
        <p>Aracın, kiralayan şahıs dışındaki kişi ve/veya kişilerce kullanılabilmesini sağlamaktadır. Araç teslimi için ek sürücünün; ana sürücü ile birlikte ofiste bulunması gereklidir.</p>
        <div class="product-control">
            <button type="button" onclick="updateDriver(-1)">-</button>
            <span id="extra-driver-count">0</span>
            <button type="button" onclick="updateDriver(1)">+</button>
        </div>
        <p><strong>Fiyat: </strong><?php echo $ekSurucuFiyat; ?> TL | <span id="extra-driver-total">0.00 TL</span></p>
    </div>

    <div class="product">
        <h3>Genç Sürücü</h3>
        <p>Genç Sürücü paketi içinde Süper Mini Hasar Güvencesi bulunmaktadır ve aşağıdaki Genç Sürücü fiyatına dahildir.</p>
        <div class="product-control">
            <button type="button" onclick="updateYoungDriver(-1)">-</button>
            <span id="young-driver-count">0</span>
            <button type="button" onclick="updateYoungDriver(1)">+</button>
        </div>
        <p><strong>Fiyat: </strong><?php echo $gencSurucuFiyat; ?> TL | <span id="young-driver-total">0.00 TL</span></p>
    </div>

    <div class="product">
        <h3>Çocuk Koltuğu</h3>
        <p>Belirli bir yaş ve kilo altındaki bebeklerin ve çocukların araç içerisinde yolculukları sırasında bebek/çocuk oto koltuğu kullanılması zorunludur.</p>
        <div class="product-control">
            <button type="button" onclick="updateChildSeat(-1)">-</button>
            <span id="child-seat-count">0</span>
            <button type="button" onclick="updateChildSeat(1)">+</button>
        </div>
        <p><strong>Fiyat: </strong><?php echo $cocukKoltuguFiyat; ?> TL | <span id="child-seat-total">0.00 TL</span></p>
    </div>
       
            <div class="next-step">
            <button  id="total-price-button"> TL - Ödemeye Geç</button>
        </div>
</div>

    
    <footer>
        <p>&copy; 2024 Rent a Car / Tum haklari saklidir.</p>
    </footer>
</body>
</html>
