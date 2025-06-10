<?php
session_start();
include("baglanti.php");

if (isset($_GET['branch_id'])) {
    $_SESSION['branch_id'] = mysqli_real_escape_string($baglan, $_GET['branch_id']);
}

if (empty($_SESSION['branch_id'])) {
    $_SESSION['branch_id'] = 2;
}

$currentBranchId = $_SESSION['branch_id'];
?>
<!DOCTYPE html> 
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şubelerimiz</title>
    
     <link rel="stylesheet" href="css/basics.css">
    
    <style>
        .container {
            display: flex;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            flex-grow: 1;
        }
        .branch-list {
            width: 25%;
            background-color: #f1f1f1;
            padding: 10px;
            border-right: 1px solid #ddd;
            overflow-y: auto;
            transition: all 0.3s;
        }
        .branch-list.collapsed {
            display: none;
        }
        .branch-list h3 {
            color: #3183e0;
            text-align: center;
        }
        .branch-list ul {
            list-style-type: none;
            padding: 0;
        }
        .branch-list ul li {
            padding: 10px;
            background-color: #fff;
            margin-bottom: 10px;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            transition: background-color 0.3s;
        }
        .branch-list ul li:hover, .branch-list ul li.active {
            background-color: #0b5a8e;
            color: white;
        }
        .branch-details {
            width: 75%;
            padding: 20px;
        }
        .branch-details h2 {
            color: #3183e0;
        }
        .branch-details p {
            margin: 10px 0;
            color: #555;
        }
        .branch-details h4 {
            color: #3183e0;
        }
        .filter-section {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .filter-section h3 {
           text-align: center;
            color: #3183e0;
        }
        .filter-section label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        .filter-section input, .filter-section select {
            width: 100%;
            margin-bottom: 15px;
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 1em;
            box-sizing: border-box;
            margin-right: 10px;
            display: inline-block;
        }
        .filter-section button {
            display: block;
            width: 100%;
            background-color: #3183e0;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
        }
        .filter-section button:hover {
            background-color: #106db0;
        }
        .map {
            width: 100%;
            height: 300px;
            background-color: #ddd;
            margin-top: 20px;
        }
        .menu-toggle {
            display: none;
            background-color: #3183e0;
            color: white;
            padding: 10px;
            font-size: 1.5em;
            border: none;
            cursor: pointer;
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
             header a{
               font-size: 1.2em;
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
              font-size: 2em;
        }
             .filter-container {
            padding: 5px;
        }
        .filter {
            padding: 15px;

        }
        .filter div {
            flex-basis: 48%;
        }
        .filter div label {
              font-size: 1em;
        }
         .filter div select{
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 0.65em;
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
        
        @media screen and (max-width: 768px) {
            .menu-toggle {
                display: block;
            }
            .container {
                  max-width: 768px;
                flex-direction: column;
            }
            .branch-list {
                width: 100%;
            }
            .branch-details {
                width: 100%;
            }
        }
    .branch-list ul li.active {
            background-color: #0b5a8e;
            color: white;
        }
    </style>
    <script>
        function toggleDistricts(cityId) {
            const city = document.getElementById(cityId);
            city.style.display = city.style.display === "none" ? "block" : "none";
        }

        // Branch seçilince sayfayı ?branch_id=xx ile yeniler
        function selectBranch(branchId) {
            window.location.search = '?branch_id=' + branchId;
        }

        function toggleMenu() {
            const menu = document.getElementById('branch-list');
            menu.classList.toggle('collapsed');
        }
        
        function toggleDistricts(cityId) {
            const city = document.getElementById(cityId);
            city.style.display = city.style.display === "none" ? "block" : "none";
        }

        function toggleMenu() {
            const menu = document.getElementById('branch-list');
            menu.classList.toggle('collapsed');
        }
         
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
        <div class="branch-list" id="branch-list">
            <h3>Şubelerimiz</h3>
            <?php
            $sql = "SELECT id, il, ilce FROM subeler ORDER BY il ASC, ilce ASC";
            $result = mysqli_query($baglan, $sql);
            $currentCity = '';
            echo '<ul>';
            while ($row = mysqli_fetch_assoc($result)) {
                $city    = $row['il'];
                $district= $row['ilce'];
                $id      = $row['id'];
                if ($city !== $currentCity) {
                    if ($currentCity !== '') {
                        echo "</ul>";
                    }
                    echo "<li onclick=\"toggleDistricts('". strtolower($city) ."')\">$city</li>";
                    echo "<ul id='". strtolower($city) ."' style='display: none;'>";
                    $currentCity = $city;
                }
                $active = ($id == $currentBranchId) ? 'active' : '';
                echo "<li class=\"$active\" onclick=\"selectBranch('$id')\">$district</li>";
            }
            if ($currentCity !== '') {
                echo "</ul>";
            }
            echo '</ul>';
            ?>
        </div>

        <div class="branch-details" id="branch-info">
            <?php
            $sql = "SELECT * FROM subeler WHERE id = '$currentBranchId'";
            $res = mysqli_query($baglan, $sql);

            if ($res && mysqli_num_rows($res) > 0) {
                $b = mysqli_fetch_assoc($res);
                echo "<h2>{$b['il']} {$b['ilce']} Şubesi</h2>";
                echo "<h4>Adres:</h4><p>{$b['adres']}</p>";
                echo "<h4>Telefon:</h4><p>{$b['telefon']}</p>";
                echo "<h4>Çalışma Saatleri:</h4><p>{$b['is_basi']} - {$b['is_sonu']}</p>";
                echo "<h4>{$b['ilce']} Hakkında:</h4><p>{$b['hakkinda']}</p>";
                ?>
                <div class="filter-section">
                    <form method="POST" action="filter_results.php">
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
                   <?php 
    $currentBranchName = "{$b['il']} - {$b['ilce']}";
    $sqlBranches = "SELECT il, ilce FROM subeler ORDER BY il, ilce";
    $allBranches = mysqli_query($baglan, $sqlBranches);
?>
<div>
    <label for="pickup-location">Teslim Alınacak Şube:</label>
    <select id="pickup-location" name="pickup_location" required>
        <option value="<?= $currentBranchName ?>" selected>
            <?= $currentBranchName ?>
        </option>
        <?php while ($row = mysqli_fetch_assoc($allBranches)): 
            $fullname = "{$row['il']} - {$row['ilce']}";
            if ($fullname === $currentBranchName) continue;
        ?>
            <option value="<?= $fullname ?>">
                <?= $fullname ?>
            </option>
        <?php endwhile; ?>
    </select>
</div>

<div>
    <label for="return-location">Teslim Edilecek Şube:</label>
    <select id="return-location" name="return_location" required>
        <?php mysqli_data_seek($allBranches, 0); ?>

        <option value="<?= $currentBranchName ?>" selected>
            <?= $currentBranchName ?>
        </option>
        <?php while ($row = mysqli_fetch_assoc($allBranches)): 
            $fullname = "{$row['il']} - {$row['ilce']}";
            if ($fullname === $currentBranchName) continue;
        ?>
            <option value="<?= $fullname ?>">
                <?= $fullname ?>
            </option>
        <?php endwhile; ?>
    </select>
</div>
                </div>
                <button type="submit">Araç Bul</button>
             </form>
                </div>
                <?php
                if (!empty($b['konum'])) {
                    echo '<iframe src="https://www.google.com/maps/embed?' 
                         . $b['konum'] . '" width="100%" height="500" style="border:0;" '
                         . 'allowfullscreen="" loading="lazy"></iframe>';
                } else {
                    echo "<p>Şube konum bilgisi bulunamadı.</p>";
                }
            } else {
                echo "<p>Şube bilgileri bulunamadı.</p>";
            }
            ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Rent a Car / Tüm hakları saklıdır.</p>
    </footer>
</body>
</html>