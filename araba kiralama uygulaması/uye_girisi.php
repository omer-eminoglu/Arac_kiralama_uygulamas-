<?php 
session_start();
include("baglanti.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM musteriler WHERE email = ?";
    $stmt = mysqli_prepare($baglan, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['sifre'])) {
            $_SESSION['musteri_id'] = $row['id'];

            header("Location: dashbord.php");
            exit();
        } else {
            $error_message = "E-posta veya şifre hatalı.";
        }
    } else {
        $error_message = "E-posta veya şifre hatalı.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Üye Girişi</title>
    
     <link rel="stylesheet" href="css/basics.css">
    
    <style>
        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .login-form h2 {
            text-align: center;
            color: #3183e0;
            font-size: 1.9em;
        }
        .login-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
            font-size: 1.3em;
        }
        .login-form input {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 1.3em;
        }
        .login-form button {
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
        .login-form button:hover {
            background-color: #106db0;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
            font-size: 1.2em;
        }
              @media screen and (max-width: 1500px) { 
              header a{
               font-size: 1.3em;
            }
        }
         @media screen and (max-width: 1300px) { 
             header a{
               font-size: 1em;
             }}
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
            footer {
                font-size: 1.2em;
            }
               
        } 
    </style>
</head>
<body>
    <header>
       <a href="ana_sayfa.php"><h1>Araba Kiralama Hizmeti</h1></a>
       <a href="uye_ol.php" class="login-button">Üye Ol</a>
    </header>

    <nav>
        <a href="filomuz.php">Filomuz</a>
        <a href="subelerimiz.php">Şubelerimiz</a>
        <a href="#">Hakkımızda</a>
        <a href="#">İletişim</a>
    </nav>

    <div class="container">
        <div class="login-form">
            <h2>Üye Girişi</h2>
            <?php if (isset($error_message)): ?>
                <div class="error-message"> <?php echo $error_message; ?> </div>
            <?php endif; ?>
            <form action="" method="post">
                <label for="email">E-posta:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Şifre:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Giriş Yap</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Rent a Car / Tüm hakları saklıdır.</p>
    </footer>
</body>
</html>
