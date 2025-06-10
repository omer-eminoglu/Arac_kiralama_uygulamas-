<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezervasyon Başarılı</title>
    
     <link rel="stylesheet" href="css/basics.css">
    
    <style>
        .container {
            margin: 20px auto ;
            padding: 50px;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 80%;
        }
        
        .container h1 {
            color: #3183e0;
            font-size: 2em;
            margin-bottom: 20px;
        }
        
        .container p {
            font-size: 1.2em;
            color: #555;
        }
        
         .container button {
            padding: 12px 25px;
            background-color: #3183e0;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.2em;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin: 0 auto;
            display: block;
        }
        
        .container button:hover {
            background-color: #106db0;
        }
        
        @media screen and (max-width: 1030px) {                     
        h2 {
            text-align: center;
            color: #3183e0;
            font-weight: bold;
              font-size: 1.5em;
        }}       
        
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
        <h1>Rezervasyon Başarılı✅</h1>
        <p>Rezervasyonunuz başarıyla oluşturulmuştur. Rezervasyonun detayları mailinize iletilicektir.</p>
        <button type="button" onclick="window.location.href='ana_sayfa.php'">Ana Sayfaya Dön</button>
    </div>

    <footer>
        <p>&copy; 2024 Rent a Car / Tüm hakları saklıdır.</p>
    </footer>
</body>
</html>
