        <?php
session_start();
include("baglanti.php");
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ödeme Sayfası</title>
    
     <link rel="stylesheet" href="css/basics.css">
    
       <style>
.container {
    max-width: 40%;
    margin: 30px auto;
    padding: 20px;
    background-color: white;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
}
        .container-date {
    padding: 20px;
    background-color: white;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
}

.section-title {
    background-color: #3183e0;
    color: white;
    padding: 15px;
    font-size: 1.5em;
    cursor: pointer;
    margin-bottom: 10px;
    border-radius: 8px;
    text-align: center;
    
}

.form-section {
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.form-row {
    display:flex;
    justify-content: space-between;
    margin-bottom: 15px;
    column-gap:  20px;
}



.form-group.full-width {
    width: 100%;
}

.form-group label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 10px;
    font-size: 1em;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

.form-row-date {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 15px;
}

.form-group-date,
.form-group {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.form-group-date label,
.form-group label {
    font-weight: bold;
    margin-bottom: 5px;
}

select {
    padding: 8px;
    font-size: 1em;
    border: 1px solid #ccc;
    border-radius: 5px;
}
        .checkbox-group {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}

.checkbox-group input[type="checkbox"] {
    margin-right: 5px;
}

.checkbox-container {
    margin: 5px;
    width: 100%;
    align-items: center;  
    padding: 20px;
    background-color: white;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    background: white;
    display: flex;
    gap: 20px;
}
        .checkbox-container-wrapper {  
    display: flex;
    gap: 20px; 
}


.nav-button, .submit-button {
    background-color: #3183e0;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-size: 1.2em;
    cursor: pointer;
    transition: background-color 0.3s;
    text-align: center;
    margin-top: 20px;
}

.nav-button:hover, .submit-button:hover {
    background-color: #106db0;
}

.button-group {
    display: flex;
    justify-content: center;
    margin-top: 20px;
    column-gap: 20px;
}
    
#terms-popup {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
}


#terms-popup .popup-content {
    background: white;
    width: 80%;
    max-width: 600px;
    margin: 50px auto;
    padding: 20px;
    border-radius: 8px;
    overflow-y: auto;
    max-height: 80vh;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    font-family: Arial, sans-serif;
}


#terms-popup h2 {
    font-size: 1.8em;
    color: #3183e0;
    text-align: center;
    margin-bottom: 20px;
}


.terms-title {
    font-size: 1.2em;
    color: white;
    padding: 10px;
    background-color:#3183e0;
    border-radius: 5px;
    cursor: pointer;
    margin-bottom: 5px;
    transition: background-color 0.3s;
}

.terms-title:hover {
    background-color: #0b5a8e;
}


.terms-content {
    padding: 10px;
    font-size: 1em;
    color: #333;
    line-height: 1.6;
    border-left: 4px solid #3183e0;
    margin-bottom: 10px;
    display: none; 
}


#terms-popup button {
    background-color: #3183e0;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-size: 1.2em;
    cursor: pointer;
    transition: background-color 0.3s;
    width: 100%;
    text-align: center;
}

#terms-popup button:hover {
    background-color: #0b5a8e;
}

         .error-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
            font-size: 1.2em;
        }
        
          @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
        
                  
        @media screen and (max-width: 1500px) { 
            
             .container {
    max-width: 45%;
}
            header a{
               font-size: 1.5em;
            }
        }
         @media screen and (max-width: 1250px) { 
             header a{
               font-size: 1.2em;
            }
              .container {
    max-width: 55%;
}
             .login-button {
            font-size: 1em;
             }
        }
        @media screen and (max-width: 1030px) { 
           .container {
    max-width: 80%;
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
            text-align: center;
            color: #3183e0;
            font-weight: bold;
              font-size: 1.5em;
        }
            footer {
                font-size: 1.4em;
            }
               
        }  
                  @media screen and (max-width: 768px) { 
           .container {
    max-width: 95%;
                      }
           header a{
               font-size: 0.9em;
            }
                       .login-button {
            font-size: 0.9em;
             }
           }

 
    </style>
    
    <script>
     
    function toggleAbroadOptions() {
        const abroadCheckbox = document.getElementById('abroad');
        const cityGroup = document.getElementById('city-group');
        const districtGroup = document.getElementById('district-group');
        const countryGroup = document.getElementById('country-group');
        
        if (abroadCheckbox.checked) {
            cityGroup.style.display = 'none';
            districtGroup.style.display = 'none';
            countryGroup.style.display = 'block';
        } else {
            cityGroup.style.display = 'block';
            districtGroup.style.display = 'block';
            countryGroup.style.display = 'none';
        }
    }
          function toggleNationalityOptions() {
    const nonTurkishCheckbox = document.getElementById('non-turkish');
    const tcLabel = document.getElementById('tc-label');
    const tcNoInput = document.getElementById('tc-no');
    const PassportLabel = document.getElementById('passport-label');          
    const PassportNoInput = document.getElementById('passport-no');
            
    const tcLabel1 = document.getElementById('tc-label1');
    const tcNoInput1 = document.getElementById('tc-no1');
    const PassportLabel1 = document.getElementById('passport-label1');          
    const PassportNoInput1 = document.getElementById('passport-no1');

    if (nonTurkishCheckbox.checked) {
        // Pasaport numarasını göster
        tcLabel.style.display = 'none';
        tcNoInput.style.display = 'none';
        PassportNoInput.style.display = 'block';
        PassportLabel.style.display = 'block';
        
        tcLabel1.style.display = 'none';
        tcNoInput1.style.display = 'none';
        PassportNoInput1.style.display = 'block';
        PassportLabel1.style.display = 'block';
        
    } else {
        tcLabel.style.display = 'block';
        tcNoInput.style.display = 'block';
        PassportNoInput.style.display = 'none';
        PassportLabel.style.display = 'none';
        
        tcLabel1.style.display = 'block';
        tcNoInput1.style.display = 'block';
        PassportNoInput1.style.display = 'none';
        PassportLabel1.style.display = 'none';
    }
}

 
        function showSection(sectionId) {
            const sections = document.querySelectorAll('.form-section');
            sections.forEach(section => {
                section.style.display = section.id === sectionId ? 'block' : 'none';
            });
        }

    function showTermsPopup() {
        document.getElementById('terms-popup').style.display = 'block';
    }

  
    function acceptTerms() {
        document.getElementById('terms-popup').style.display = 'none';
        document.getElementById('terms-checkbox').checked = true;
    }

   
    function toggleCompanyForm() {
        const companyForm = document.getElementById('company-form');
        if (document.getElementById('company-billing').checked) {
            companyForm.style.display = 'block';
            populateCompanyForm();
        } else {
            companyForm.style.display = 'none';
        }
    }

  
    function toggleTermsSection(sectionId) {
        const sections = document.querySelectorAll('.terms-content');
        sections.forEach(section => {
            if (section.id === sectionId) {
                section.style.display = section.style.display === 'none' ? 'block' : 'none';
            } else {
                section.style.display = 'none';
            }
        });
    }
      function validateFormAndProceed() {
    const termsCheckbox = document.getElementById('terms-checkbox').checked;

    const errors = [];

    if (!termsCheckbox) errors.push("Kiralama Şartlarını kabul etmelisiniz.");

    if (errors.length > 0) {
        alert(errors.join("\n"));
    } else {
        showSection('form2');
    }
}  
        
   function saveFlightCodeAndProceed() {
    const prefix = document.getElementById('flight-code-prefix').value;
    const number = document.getElementById('flight-code-number').value;
    const flightCode = prefix + number;

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "update_flight_code.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (xhr.status === 200) {
            showSection('form4');
        }
    };
    xhr.send("flightCode=" + encodeURIComponent(flightCode) + "&sectionId=form4");
}     

        
function closePopup() {
    const popup = document.getElementById("error-popup");
    if (popup) {
        popup.style.display = "none";
        window.location.href = "ana_sayfa.php";
    }
}

function showPopupWithMessage(message) {
    document.getElementById("loading-popup").style.display = "none";
    document.getElementById("error-popup").style.display = "flex";
    document.getElementById("error-message").textContent = message;
}

function checkConditions() {
    document.getElementById("loading-popup").style.display = "flex";

    setTimeout(() => {
        fetch("yas_kontrol.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "error") {
                showPopupWithMessage(data.message);
            } else if (data.status === "success") {
                document.getElementById("loading-popup").style.display = "none";
                // Şartlar sağlandığında form3'e yönlendirin
                showSection('form3');
            }
        })
        .catch(error => {
            showPopupWithMessage("Bir hata oluştu, lütfen tekrar deneyin.");
        });
    }, 3000); 
}
        
        function submitForm() {
    const cardName = document.querySelector('input[name="card-name"]').value;
    const cardNumber = document.querySelector('input[name="card-number"]').value;
    const expiryMonth = document.querySelector('select[name="expiry-month"]').value;
    const expiryYear = document.querySelector('select[name="expiry-year"]').value;
    const cvv = document.querySelector('input[name="cvv"]').value;

    if (!cardName || !cardNumber || !expiryMonth || !expiryYear || !cvv) {
        alert("Lütfen tüm alanları doldurun.");
        return;
    }

    fetch("odeme_rezervasyon_isle.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({   cardName: cardName,
            cardNumber: cardNumber,
            expiryMonth: expiryMonth,
            expiryYear: expiryYear,
            cvv: cvv})
        })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            alert("Rezervasyon başarılı!");
            window.location.href = "basari_sayfasi.php";
        } else {
            alert("Hata: " + data.message);
        }
    })
    .catch(error => {
        console.error("Bir hata oluştu:", error);
        alert("Bir hata oluştu, lütfen tekrar deneyin.");
    });
}
</script>
    
</head>
<body onload="showSection('<?php echo isset($_SESSION['sectionId']) ? $_SESSION['sectionId'] : 'form1'; ?>'), toggleAbroadOptions() , toggleNationalityOptions()">
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
        <a href="şubelerimiz.php">Şubelerimiz</a>
        <a href="#">Hakkımızda</a>
        <a href="#">İletişim</a>
    </nav>
    
<div id="loading-popup" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center; z-index: 1000;">
    <div style="background: white; padding: 20px; border-radius: 8px; text-align: center; width: 300px;">
        <div style="display: flex; flex-direction: column; align-items: center;">
            <div class="spinner" style="border: 4px solid #f3f3f3; border-top: 4px solid #3183e0; border-radius: 50%; width: 30px; height: 30px; animation: spin 1s linear infinite; margin-bottom: 10px;"></div>
            <p>Koşullar değerlendiriliyor...</p>
        </div>
    </div>
</div>

<div id="error-popup" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center; z-index: 1000;">
    <div style="background: white; padding: 20px; border-radius: 8px; text-align: center; width: 300px;">
        <h2 style="color:black">Uyarı</h2>
        <p id="error-message" style="color:black"></p>
        <button onclick="closePopup()" style="margin-top: 10px; padding: 10px 20px; background-color: #3183e0; color: white; border: none; border-radius: 5px; cursor: pointer;">Ana Sayfaya Dön</button>
    </div>
</div>

    <div class="container">
        
<?php
$musteri_id = $_SESSION['musteri_id'] ?? null;

if ($musteri_id) {
    $sql = "SELECT * FROM musteriler WHERE id = ?";
    $stmt = mysqli_prepare($baglan, $sql);
    mysqli_stmt_bind_param($stmt, "i", $musteri_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $musteri = mysqli_fetch_assoc($result);

      
        $ad_soyad = explode(' ', $musteri['ad_soyad']);
        $soyad = array_pop($ad_soyad);
        $ad = implode(' ', $ad_soyad); 
        $abroad_checked = empty($musteri['il']) || empty($musteri['ilce']) && !empty($musteri['ulke']);
        $NonTurkish_checked = empty($musteri['tc_no']) || !empty($musteri['passport_no']);
    }
}
?>
        
                
<div class="section-title" onclick="showSection('form1')">1. SÜRÜCÜ BİLGİLERİ</div>
<div id="form1" class="form-section" style="display: block;">
    <p>Yeni kimlik uygulamasında ehliyetiniz tanımlı olsa dahi, kiralama işlemlerinde ehliyetinizi yanınızda bulundurmanız önemle rica olunur. Aksi takdirde kiralama işlemi gerçekleştirilemeyecektir.</p>

     <div class="form-group">
                <label for="first-name">Adınız*</label>
                <input type="text" name="first-name" id="first-name" value="<?php echo htmlspecialchars($ad ?? ''); ?>" readonly>
         
                <label for="last-name">Soyadınız*</label>
                <input type="text" name="last-name" id="last-name" value="<?php echo htmlspecialchars($soyad ?? ''); ?>" readonly>
         
                <label for="tc-no" id="tc-label">T.C. No*</label>
                <input type="text" name="tc-no" id="tc-no"  value="<?php echo htmlspecialchars($musteri['tc_no'] ?? ''); ?>" readonly>
         
                <label for="passport-no" id="passport-label">Passport No*</label>
                <input type="text" name="passport-no" id="passport-no"  value="<?php echo htmlspecialchars($musteri['passport_no'] ?? ''); ?>" readonly>
         
                <label for="license-no">Ehliyet No*</label>
                <input type="text" name="license-no" id="license-no" value="<?php echo htmlspecialchars($musteri['ehliyet_seri_no'] ?? ''); ?>" readonly>
         
                <label for="birth-date">Doğum Tarihi*</label>
                <input type="date" name="birth-date" id="birth-date" value="<?php echo htmlspecialchars($musteri['dogum_tarihi'] ?? ''); ?>" readonly>
         
            </div>

     <div class="form-group" id="city-group">
                <label for="city">İl*</label>    
                    <input type="text" name="city" id="city" value="<?php echo htmlspecialchars($musteri['il'] ?? ''); ?>" readonly>
            </div>

            <div class="form-group" id="district-group">
                <label for="district">İlçe*</label>
                <input type="text" name="district" id="district" value="<?php echo htmlspecialchars($musteri['ilce'] ?? ''); ?>" readonly>
            </div>

    <div class="form-group" id="country-group">
    <label for="country">Ülke*</label>
         <input type="text" name="country" id="country" value="<?php echo htmlspecialchars($musteri['ulke'] ?? ''); ?>" readonly>
</div>
    
        <div class="form-group">
                <label for="address">Adres*</label>
                <input type="text" name="address" id="address" value="<?php echo htmlspecialchars($musteri['adres'] ?? ''); ?>" readonly>
            </div>
    
 <div class="form-group checkbox-group" style="display:none">
     <input type="checkbox" id="non-turkish" name="non-turkish" onclick="toggleNationalityOptions()" <?php echo $NonTurkish_checked ? 'checked' : ''; ?>>
<label for="non-turkish">T.C. Vatandaşı Değilim</label>
        <input type="checkbox" name="abroad" id="abroad" onclick="toggleAbroadOptions()" <?php echo $abroad_checked ? 'checked' : ''; ?>>
        <label for="abroad">Yurt dışında yaşıyorum.</label>
    </div>

<div class="form-group">
                <label for="phone">Telefon*</label>
                <input type="tel" name="phone" id="phone" placeholder="+90" value="<?php echo htmlspecialchars($musteri['telefon'] ?? ''); ?>" readonly>
                <label for="email">E-Posta*</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($musteri['email'] ?? ''); ?>" readonly>
            </div>
    

<div class="checkbox-container-wrapper">
    <div class="checkbox-container">
<div class="form-group checkbox-group">
    <input type="checkbox" id="terms-checkbox" required>
    <label for="terms-checkbox">
        <a href="javascript:void(0);" onclick="showTermsPopup()">Kiralama Şartlarını</a> okudum kabul ediyorum.
    </label>
        </div></div> <div class="checkbox-container">
    <div class="form-group checkbox-group">
     <input type="checkbox" id="company-billing" onclick="toggleCompanyForm()">
    <label for="company-billing">Fatura bilgilerimi firma adına düzenlemek istiyorum</label>
     </div></div></div>



<div id="company-form" style="display: none; padding: 10px; border: 1px solid #ccc; border-radius: 5px; margin-top: 10px;">
    
    <script>
function populateCompanyForm() {
    const companySelect = document.getElementById('company-select');
    const selectedOption = companySelect.options[companySelect.selectedIndex].value;
    if (selectedOption) {
        const companyDetails = JSON.parse(selectedOption);
        document.querySelector('input[name="company-name"]').value = companyDetails.sirket_adi;
        document.querySelector('input[name="tax-office"]').value = companyDetails.vergi_dairesi;
        document.querySelector('input[name="tax-number"]').value = companyDetails.vergi_no;
        document.querySelector('input[name="company-address"]').value = companyDetails.adres;
    }
}
</script>
    
       <?php
        if (isset($musteri_id)) {
            $fatura_sql = "SELECT * FROM fatura WHERE musteri_id = ?";
            $stmt_fatura = mysqli_prepare($baglan, $fatura_sql);
            mysqli_stmt_bind_param($stmt_fatura, "i", $musteri_id);
            mysqli_stmt_execute($stmt_fatura);
            $fatura_result = mysqli_stmt_get_result($stmt_fatura);

            if ($fatura_result && mysqli_num_rows($fatura_result) > 0) {
                echo '<div class="form-group">';
                echo '<label for="company-select">Mevcut Şirket Bilgileri</label>';
                echo '<select id="company-select" onchange="populateCompanyForm()">';
                while ($fatura = mysqli_fetch_assoc($fatura_result)) {
                    $fatura_json = htmlspecialchars(json_encode($fatura), ENT_QUOTES, 'UTF-8');
                    echo '<option value="' . $fatura_json . '">' . $fatura['sirket_adi'] . '</option>';
                }
                echo '</select>';
                echo '</div>';
            }
            mysqli_stmt_close($stmt_fatura);
        }
    ?>
    
    <div class="form-group">
        <label for="company-name">Şirket Adı*</label>
        <input type="text" name="company-name" id="company-name" required>
    </div>
    <div class="form-group">
        <label for="tax-office">Vergi Dairesi*</label>
        <input type="text" name="tax-office" id="tax-office" required>
    </div>
    <div class="form-group">
        <label for="tax-number">Vergi Numarası*</label>
        <input type="text" name="tax-number" id="tax-number" maxlength="10" pattern="\d{1,10}" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>

    </div>
    <div class="form-group">
        <label for="company-address">Şirket Adresi*</label>
        <input name="company-address" id="company-address"  required>
    </div>
</div>


<div id="terms-popup">
    <div class="popup-content">
        <h2>Kiralama Şartları</h2>

  
        <div class="terms-section">
            <div class="terms-title" onclick="toggleTermsSection('terms1')">1. SÜRÜCÜ BELGESİ VE YAŞ SINIRLAMALARI</div>
            <div id="terms1" class="terms-content">
                <p>Araç Kiralamak için en az 21 yaşını doldurmuş olmanız ve en az 1 yıllık B grubu ehliyet sahibi olmanız gerekmektedir.

Genç Sürücü Ücreti </p>
            </div>

            <div class="terms-title" onclick="toggleTermsSection('terms2')">2. KİRALAMA SÜRESİ</div>
            <div id="terms2" class="terms-content">
                <p>Araç kiralama minimum süresi günlük kiralamalarda 24 saat, aylık kiralamalarda ise 30 gündür.</p>
            </div>

            <div class="terms-title" onclick="toggleTermsSection('terms3')">3. FİYATLARA DÂHİL OLAN VE OLMAYAN HUSUSLAR</div>
            <div id="terms3" class="terms-content">
                <p>Fiyatlara Hasar Sorumluluk </p>
            </div>

            <div class="terms-title" onclick="toggleTermsSection('terms4')">4. Sigorta Bilgileri</div>
            <div id="terms4" class="terms-content">
                <p>Sigorta kapsamı, kaza durumunda yapılacak işlemler ve sorumluluklar...</p>
            </div>
        </div>

        <button onclick="acceptTerms()">Onaylıyorum</button>
    </div>
</div>
    
       

    <div class="button-group">
        <button class="nav-button" onclick="validateFormAndProceed()">Sonraki Adım</button>
    </div>
</div>

        <div class="section-title" >2. ARAÇ KİRALAMA DEĞERLENDİRME ANALİZİ</div>
        <div id="form2" class="form-section">
             <div class="form-group">
                 <form action="" method="post">
                  <label for="tc-no1" id="tc-label1">T.C. No*</label>
                <input type="text" name="tc-no1" id="tc-no1"  value="<?php echo htmlspecialchars($musteri['tc_no'] ?? ''); ?>" readonly>
         
                <label for="passport-no1" id="passport-label1">Passport No*</label>
                <input type="text" name="passport-no1" id="passport-no1"  value="<?php echo htmlspecialchars($musteri['passport_no'] ?? ''); ?>" readonly>
                     
                <label for="license-no1">Ehliyet No*</label>
                <input type="text" name="license-no1" id="license-no1" value="<?php echo htmlspecialchars($musteri['ehliyet_seri_no'] ?? ''); ?>" readonly>
                     
                <label for="birth-date1">Doğum Tarihi*</label>
                <input type="date" name="birth-date1" id="birth-date1" value="<?php echo htmlspecialchars($musteri['dogum_tarihi'] ?? ''); ?>" readonly>
                     
                  <label for="license-date">Ehliyet Verilme Tarihi</label>
              <input type="date" id="license-date" name="license-date" value="<?php echo htmlspecialchars($musteri['ehliyet_alis_tarihi'] ?? ''); ?>" readonly>
                     
                           <p class="explanation">
                *Ehliyet verilme tarihi KKB sorgunuzu etkileyeceği için lütfen ehliyetinizdeki 10 no'lu alanı girdiğinizden emin olunuz.<br>
                **"Araç bulunmamaktadır.
            </p> 
          <div class="button-group">
    <button class="nav-button" type="button" onclick="checkConditions()">Sonraki Adım</button>
</div>
                     </form>
            </div>
        </div>

        <div class="section-title" >3. Uçuş Bilgileri(Opsiyonel)</div>
        <div id="form3" class="form-section">
      <div class="form-group">
          <form action="" method="post">
          
              <label>Uçuş Kodu</label>
    <div class="form-row">
        <input type="text" id="flight-code-prefix" placeholder="TK" value=""  style="width: 20%;">
        <input type="text" id="flight-code-number" placeholder="2992" style="width: 75%; margin-left: 5%;">
    </div>


<div class="button-group">
    <button class="nav-button" onclick="saveFlightCodeAndProceed()">SONRAKİ ADIM</button>
</div>
          
          </form>
          </div>
        </div>

        <div class="section-title" >4. Ödeme Bilgileri</div>
        <div id="form4" class="form-section">
        <form id="payment-form" action="odeme_rezervasyon_isle.php" method="POST">
    <div class="form-group">
    <label>Kredi Kartı Üzerindeki İsim ve Soyisim*</label>
    <input type="text" name="card-name" required oninput="this.value = this.value.replace(/[^A-Za-zÇçĞğİıÖöŞşÜü\s]/g, '')" title="Sadece harf ve boşluk karakterleri girilebilir.">
</div>
            
   <div class="form-group">
    <label>Kart Numarası*</label>
    <input type="text" name="card-number" required placeholder="0000 0000 0000 0000" maxlength="16" pattern="\d{16}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16)" title="16 haneli kart numaranızı girin." >
</div>
            
    <label>Son Kullanma Tarihi</label>
             <div class="container-date">
    <div class="form-row">
    <div class="form-group">
        <label for="expiry-month">Ay*</label>
<select id="expiry-month" name="expiry-month" required>
            <option value="">Ay</option>
            <option value="01">01</option>
            <option value="02">02</option>
            <option value="03">03</option>
            <option value="04">04</option>
            <option value="05">05</option>
            <option value="06">06</option>
            <option value="07">07</option>
            <option value="08">08</option>
            <option value="09">09</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
        </select>
    </div>
        
       <div class="form-group">
        <label for="expiry-year">&nbsp;Yıl*</label>
        <select name="expiry-year" id="expiry-year" required>
            <option value="">Yıl</option>
            <script>
                const currentYear = new Date().getFullYear();
                for (let year = currentYear; year <= currentYear + 10; year++) {
                    document.write(`<option value="${year}">${year}</option>`);
                }
            </script>
        </select>
    </div></div>  </div>
        <div class="form-group">
            <label>Güvenlik Kodu*</label>
            <input type="text" name="cvv" required maxlength="3" pattern="\d{3}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 3)" title="Kartınızın arka yüzündeki 3 haneli kod.">
        </div>
        <div class="button-group">
        <button type="submit" class="submit-button">Rezervasyonu Tamamla</button>
    </div>
    </div>
    
</form>
        </div>
    </div>
    
    
    
  <footer>
        <p>&copy; 2024 Rent a Car / Tüm hakları saklıdır.</p>
    </footer>
</body>
</html>
