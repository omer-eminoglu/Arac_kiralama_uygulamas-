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

            header("Location: odeme.php");
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
                      }}

 
    </style>
    
    <script>
        window.onload = function(){
            toggleAbroadOptions();
            toggleNationalityOptions();
            showSection('form_uye');
             populateYearSelects();
    populateDaySelects();
        
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
  function toggleAbroadOptions() {
            const abroadCheckbox = document.getElementById('abroad');
            const citySelect = document.getElementById("city");
            const districtSelect = document.getElementById("district");
            const countrySelect = document.getElementById("country");

            if (abroadCheckbox.checked) {
                citySelect.required = false;
                districtSelect.required = false;
                countrySelect.required = true;
                document.getElementById("city-group").style.display = 'none';
                document.getElementById("district-group").style.display = 'none';
                document.getElementById("country-group").style.display = 'block';
            } else {
                citySelect.required = true;
                districtSelect.required = true;
                countrySelect.required = false;
                document.getElementById("city-group").style.display = 'block';
                document.getElementById("district-group").style.display = 'block';
                document.getElementById("country-group").style.display = 'none';
            }
        }

   function toggleNationalityOptions() {
    const nonTurkishCheckbox = document.getElementById('non-turkish');
    const tcGroup = document.getElementById('tc-group');
    const passportGroup = document.getElementById('passport-group');

    if (nonTurkishCheckbox.checked) {
        tcGroup.style.display = 'none';
        passportGroup.style.display = 'block';
        document.getElementById('passport-no').required = true;
        document.getElementById('tc-no').required = false;
    } else {
        passportGroup.style.display = 'none';
        tcGroup.style.display = 'block';
        document.getElementById('tc-no').required = true;
        document.getElementById('passport-no').required = false;
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
            const companyBillingCheckbox = document.getElementById("company-billing");
            const companyForm = document.getElementById("company-form");
            const companyFields = companyForm.querySelectorAll("input, textarea");

            if (companyBillingCheckbox.checked) {
                companyForm.style.display = 'block';
                companyFields.forEach(field => field.required = true);
            } else {
                companyForm.style.display = 'none';
                companyFields.forEach(field => field.required = false);
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
        
        
                  const ilceler = {
"Adana": ["Seyhan", "Yüreğir", "Çukurova", "Sarıçam", "Aladağ", "Ceyhan", "Feke", "İmamoğlu", "Karaisalı", "Karataş", "Kozan", "Pozantı", "Saimbeyli", "Tufanbeyli", "Yumurtalık"],
  "Adıyaman": ["Merkez", "Besni", "Çelikhan", "Gerger", "Gölbaşı", "Kahta", "Samsat", "Sincik", "Tut"],
  "Afyonkarahisar": ["Merkez", "Başmakçı", "Bayat", "Bolvadin", "Çay", "Çobanlar", "Dazkırı", "Dinar", "Emirdağ", "Evciler", "Hocalar", "İhsaniye", "İscehisar", "Kızılören", "Sandıklı", "Sinanpaşa", "Sultandağı", "Şuhut"],
  "Ağrı": ["Merkez", "Diyadin", "Doğubayazıt", "Eleşkirt", "Hamur", "Patnos", "Taşlıçay", "Tutak"],
  "Aksaray": ["Merkez", "Ağaçören", "Eskil", "Gülağaç", "Güzelyurt", "Ortaköy", "Sarıyahşi"],
  "Amasya": ["Merkez", "Göynücek", "Gümüşhacıköy", "Hamamözü", "Merzifon", "Suluova", "Taşova"],
  "Ankara": ["Altındağ", "Ayaş", "Bala", "Beypazarı", "Çamlıdere", "Çankaya", "Çubuk", "Elmadağ", "Etimesgut", "Evren", "Gölbaşı", "Güdül", "Haymana", "Kalecik", "Kahramankazan", "Keçiören", "Kızılcahamam", "Mamak", "Nallıhan", "Polatlı", "Pursaklar", "Sincan", "Şereflikoçhisar", "Yenimahalle"],
  "Antalya": ["Akseki", "Aksu", "Alanya", "Demre", "Döşemealtı", "Elmalı", "Finike", "Gazipaşa", "Gündoğmuş", "İbradı", "Kaş", "Kemer", "Kepez", "Konyaaltı", "Korkuteli", "Kumluca", "Manavgat", "Muratpaşa", "Serik"],
  "Ardahan": ["Merkez", "Çıldır", "Damal", "Göle", "Hanak", "Posof"],
  "Artvin": ["Merkez", "Ardanuç", "Arhavi", "Borçka", "Hopa", "Murgul", "Şavşat", "Yusufeli"],
  "Aydın": ["Efeler", "Bozdoğan", "Buharkent", "Çine", "Didim", "Germencik", "İncirliova", "Karacasu", "Karpuzlu", "Koçarlı", "Köşk", "Kuşadası", "Kuyucak", "Nazilli", "Söke", "Sultanhisar", "Yenipazar"],
  "Balıkesir": ["Altıeylül", "Karesi", "Ayvalık", "Balya", "Bandırma", "Bigadiç", "Burhaniye", "Dursunbey", "Edremit", "Erdek", "Gömeç", "Gönen", "Havran", "İvrindi", "Kepsut", "Manyas", "Marmara", "Savaştepe", "Sındırgı", "Susurluk"],
  "Bartın": ["Merkez", "Amasra", "Kurucaşile", "Ulus"],
  "Batman": ["Merkez", "Beşiri", "Gercüş", "Hasankeyf", "Kozluk", "Sason"],
  "Bayburt": ["Merkez", "Aydıntepe", "Demirözü"],
  "Bilecik": ["Merkez", "Bozüyük", "Gölpazarı", "İnhisar", "Osmaneli", "Pazaryeri", "Söğüt", "Yenipazar"],
  "Bingöl": ["Merkez", "Adaklı", "Genç", "Karlıova", "Kiğı", "Solhan", "Yayladere", "Yedisu"],
  "Bitlis": ["Merkez", "Adilcevaz", "Ahlat", "Güroymak", "Hizan", "Mutki", "Tatvan"],
  "Bolu": ["Merkez", "Dörtdivan", "Gerede", "Göynük", "Kıbrıscık", "Mengen", "Mudurnu", "Seben", "Yeniçağa"],
  "Burdur": ["Merkez", "Ağlasun", "Altınyayla", "Bucak", "Çavdır", "Çeltikçi", "Gölhisar", "Karamanlı", "Kemer", "Tefenni", "Yeşilova"],
  "Bursa": ["Osmangazi", "Yıldırım", "Nilüfer", "Gemlik", "İnegöl", "İznik", "Karacabey", "Keles", "Kestel", "Mudanya", "Mustafakemalpaşa", "Orhaneli", "Orhangazi", "Yenişehir", "Büyükorhan", "Harmancık"],
  "Çanakkale": ["Merkez", "Ayvacık", "Bayramiç", "Biga", "Bozcaada", "Çan", "Eceabat", "Ezine", "Gelibolu", "Gökçeada", "Lapseki", "Yenice"],
  "Çankırı": ["Merkez", "Atkaracalar", "Bayramören", "Çerkeş", "Eldivan", "Ilgaz", "Kızılırmak", "Korgun", "Kurşunlu", "Orta", "Şabanözü", "Yapraklı"],
  "Çorum": ["Merkez", "Alaca", "Bayat", "Boğazkale", "Dodurga", "İskilip", "Kargı", "Laçin", "Mecitözü", "Oğuzlar", "Ortaköy", "Osmancık", "Sungurlu", "Uğurludağ"],
  "Denizli": ["Merkezefendi", "Pamukkale", "Acıpayam", "Babadağ", "Baklan", "Bekilli", "Beyağaç", "Bozkurt", "Buldan", "Çal", "Çameli", "Çardak", "Çivril", "Güney", "Honaz", "Kale", "Sarayköy", "Serinhisar", "Tavas"],
  "Diyarbakır": ["Bağlar", "Kayapınar", "Sur", "Yenişehir", "Bismil", "Çermik", "Çınar", "Çüngüş", "Dicle", "Eğil", "Ergani", "Hani", "Hazro", "Kocaköy", "Kulp", "Lice", "Silvan"],
  "Düzce": ["Merkez", "Akçakoca", "Cumayeri", "Çilimli", "Gölyaka", "Gümüşova", "Kaynaşlı", "Yığılca"],
  "Edirne": ["Merkez", "Enez", "Havsa", "İpsala", "Keşan", "Lalapaşa", "Meriç", "Süloğlu", "Uzunköprü"],
  "Elazığ": ["Merkez", "Ağın", "Alacakaya", "Arıcak", "Baskil", "Karakoçan", "Keban", "Kovancılar", "Maden", "Palu", "Sivrice"],
  "Erzincan": ["Merkez", "Çayırlı", "İliç", "Kemah", "Kemaliye", "Otlukbeli", "Refahiye", "Tercan", "Üzümlü"],
  "Erzurum": ["Yakutiye", "Aziziye", "Palandöken", "Aşkale", "Çat", "Hınıs", "Horasan", "İspir", "Karaçoban", "Karayazı", "Köprüköy", "Narman", "Oltu", "Olur", "Pasinler", "Pazaryolu", "Şenkaya", "Tekman", "Tortum", "Uzundere"],
  "Eskişehir": ["Odunpazarı", "Tepebaşı", "Alpu", "Beylikova", "Çifteler", "Günyüzü", "Han", "İnönü", "Mahmudiye", "Mihalgazi", "Mihalıççık", "Sarıcakaya", "Seyitgazi", "Sivrihisar"],
  "Gaziantep": ["Şahinbey", "Şehitkamil", "Araban", "İslahiye", "Karkamış", "Nizip", "Nurdağı", "Oğuzeli", "Yavuzeli"],
  "Giresun": ["Merkez", "Alucra", "Bulancak", "Çamoluk", "Çanakçı", "Dereli", "Doğankent", "Espiye", "Eynesil", "Görele", "Güce", "Keşap", "Piraziz", "Şebinkarahisar", "Tirebolu", "Yağlıdere"],
  "Gümüşhane": ["Merkez", "Kelkit", "Köse", "Kürtün", "Şiran", "Torul"],
  "Hakkari": ["Merkez", "Çukurca", "Derecik", "Şemdinli", "Yüksekova"],
  "Hatay": ["Antakya", "Defne", "Altınözü", "Arsuz", "Belen", "Dörtyol", "Erzin", "Hassa", "İskenderun", "Kırıkhan", "Kumlu", "Payas", "Reyhanlı", "Samandağ", "Yayladağı"],
  "Iğdır": ["Merkez", "Aralık", "Karakoyunlu", "Tuzluca"],
  "Isparta": ["Merkez", "Aksu", "Atabey", "Eğirdir", "Gelendost", "Gönen", "Keçiborlu", "Senirkent", "Sütçüler", "Şarkikaraağaç", "Uluborlu", "Yalvaç", "Yenişarbademli"],
  "İstanbul": ["Adalar", "Arnavutköy", "Ataşehir", "Avcılar", "Bağcılar", "Bahçelievler", "Bakırköy", "Başakşehir", "Bayrampaşa", "Beşiktaş", "Beykoz", "Beylikdüzü", "Beyoğlu", "Büyükçekmece", "Çatalca", "Çekmeköy", "Esenler", "Esenyurt", "Eyüpsultan", "Fatih", "Gaziosmanpaşa", "Güngören", "Kadıköy", "Kağıthane", "Kartal", "Küçükçekmece", "Maltepe", "Pendik", "Sancaktepe", "Sarıyer", "Silivri", "Sultanbeyli", "Sultangazi", "Şile", "Şişli", "Tuzla", "Ümraniye", "Üsküdar", "Zeytinburnu"],
  "İzmir": ["Aliağa", "Balçova", "Bayındır", "Bayraklı", "Bergama", "Beydağ", "Bornova", "Buca", "Çeşme", "Çiğli", "Dikili", "Foça", "Gaziemir", "Güzelbahçe", "Karabağlar", "Karaburun", "Karşıyaka", "Kemalpaşa", "Kınık", "Kiraz", "Konak", "Menderes", "Menemen", "Narlıdere", "Ödemiş", "Seferihisar", "Selçuk", "Tire", "Torbalı", "Urla"],
  "Kahramanmaraş": ["Onikişubat", "Dulkadiroğlu", "Afşin", "Andırın", "Çağlayancerit", "Ekinözü", "Elbistan", "Göksun", "Nurhak", "Pazarcık", "Türkoğlu"],
  "Karabük": ["Merkez", "Eflani", "Eskipazar", "Ovacık", "Safranbolu", "Yenice"],
  "Karaman": ["Merkez", "Ayrancı", "Başyayla", "Ermenek", "Kazımkarabekir", "Sarıveliler"],
  "Kars": ["Merkez", "Akyaka", "Arpaçay", "Digor", "Kağızman", "Sarıkamış", "Selim", "Susuz"],
  "Kastamonu": ["Merkez", "Abana", "Ağlı", "Araç", "Azdavay", "Bozkurt", "Cide", "Çatalzeytin", "Daday", "Devrekani", "Doğanyurt", "Hanönü", "İhsangazi", "İnebolu", "Küre", "Pınarbaşı", "Seydiler", "Şenpazar", "Taşköprü", "Tosya"],
  "Kayseri": ["Kocasinan", "Melikgazi", "Hacılar", "İncesu", "Akkışla", "Bünyan", "Develi", "Felahiye", "Kale", "Özvatan", "Pınarbaşı", "Sarıoğlan", "Sarız", "Talas", "Tomarza", "Yahyalı", "Yeşilhisar"],
  "Kırıkkale": ["Merkez", "Bahşili", "Balışeyh", "Çelebi", "Delice", "Karakeçili", "Keskin", "Sulakyurt", "Yahşihan"],
  "Kırklareli": ["Merkez", "Babaeski", "Demirköy", "Kofçaz", "Lüleburgaz", "Pehlivanköy", "Pınarhisar", "Vize"],
  "Kırşehir": ["Merkez", "Akçakent", "Akpınar", "Boztepe", "Çiçekdağı", "Kaman", "Mucur"],
  "Kilis": ["Merkez", "Elbeyli", "Musabeyli", "Polateli"],
  "Kocaeli": ["İzmit", "Başiskele", "Çayırova", "Darıca", "Derince", "Dilovası", "Gebze", "Gölcük", "Kandıra", "Karamürsel", "Kartepe", "Körfez"],
  "Konya": ["Merkez", "Ahırlı", "Akören", "Akşehir", "Altınekin", "Beyşehir", "Bozkır", "Cihanbeyli", "Çeltik", "Çumra", "Derbent", "Derebucak", "Doğanhisar", "Emirgazi", "Ereğli", "Güneysınır", "Hadim", "Halkapınar", "Hüyük", "Ilgın", "Kadınhanı", "Karapınar", "Karatay", "Kulu", "Meram", "Sarayönü", "Selçuklu", "Seydişehir", "Taşkent", "Tuzlukçu", "Yalıhüyük", "Yunak"],
  "Kütahya": ["Merkez", "Altıntaş", "Aslanapa", "Çavdarhisar", "Domaniç", "Dumlupınar", "Emet", "Gediz", "Hisarcık", "Pazarlar", "Şaphane", "Simav", "Tavşanlı"],
  "Malatya": ["Battalgazi", "Yeşilyurt", "Akçadağ", "Arapgir", "Arguvan", "Darende", "Doğanşehir", "Doğanyol", "Hekimhan", "Kale", "Kuluncak", "Pütürge", "Yazıhan"],
  "Manisa": ["Yunusemre", "Şehzadeler", "Ahmetli", "Akhisar", "Alaşehir", "Demirci", "Gölmarmara", "Gördes", "Kırkağaç", "Köprübaşı", "Kula", "Salihli", "Sarıgöl", "Saruhanlı", "Selendi", "Soma", "Turgutlu"],
  "Mardin": ["Artuklu", "Dargeçit", "Derik", "Kızıltepe", "Mazıdağı", "Midyat", "Nusaybin", "Ömerli", "Savur", "Yeşilli"],
  "Mersin": ["Akdeniz", "Mezitli", "Toroslar", "Yenişehir", "Anamur", "Aydıncık", "Bozyazı", "Çamlıyayla", "Erdemli", "Gülnar", "Mut", "Silifke", "Tarsus"],
  "Muğla": ["Bodrum", "Dalaman", "Datça", "Fethiye", "Kavaklıdere", "Köyceğiz", "Marmaris", "Menteşe", "Milas", "Ortaca", "Seydikemer", "Ula", "Yatağan"],
  "Muş": ["Merkez", "Bulanık", "Hasköy", "Korkut", "Malazgirt", "Varto"],
  "Nevşehir": ["Merkez", "Avanos", "Derinkuyu", "Gülşehir", "Hacıbektaş", "Kozaklı", "Ürgüp", "Acıgöl"],
  "Niğde": ["Merkez", "Altunhisar", "Bor", "Çamardı", "Çiftlik", "Ulukışla"],
  "Ordu": ["Altınordu", "Akkuş", "Aybastı", "Çamaş", "Çatalpınar", "Çaybaşı", "Fatsa", "Gölköy", "Gülyalı", "Gürgentepe", "İkizce", "Kabadüz", "Kabataş", "Korgan", "Kumru", "Mesudiye", "Perşembe", "Ulubey", "Ünye"],
  "Osmaniye": ["Merkez", "Bahçe", "Düziçi", "Hasanbeyli", "Kadirli", "Sumbas", "Toprakkale"],
  "Rize": ["Merkez", "Ardeşen", "Çamlıhemşin", "Çayeli", "Derepazarı", "Fındıklı", "Güneysu", "Hemşin", "İkizdere", "İyidere", "Kalkandere", "Pazar"],
  "Sakarya": ["Adapazarı", "Akyazı", "Arifiye", "Erenler", "Ferizli", "Geyve", "Hendek", "Karapürçek", "Karasu", "Kaynarca", "Kocaali", "Pamukova", "Sapanca", "Serdivan", "Söğütlü", "Taraklı"],
  "Samsun": ["Atakum", "Canik", "İlkadım", "Tekkeköy", "Alaçam", "Asarcık", "Ayvacık", "Bafra", "Havza", "Kavak", "Ladik", "Ondokuzmayıs", "Salıpazarı", "Terme", "Vezirköprü", "Yakakent"],
  "Şanlıurfa": ["Eyyübiye", "Haliliye", "Karaköprü", "Akçakale", "Birecik", "Bozova", "Ceylanpınar", "Halfeti", "Harran", "Hilvan", "Siverek", "Suruç", "Viranşehir"],
  "Siirt": ["Merkez", "Baykan", "Eruh", "Kurtalan", "Pervari", "Şirvan", "Tillo"],
  "Sinop": ["Merkez", "Ayancık", "Boyabat", "Dikmen", "Durağan", "Erfelek", "Gerze", "Saraydüzü", "Türkeli"],
  "Sivas": ["Merkez", "Akıncılar", "Altınyayla", "Divriği", "Doğanşar", "Gemerek", "Gölova", "Gürün", "Hafik", "İmranlı", "Kangal", "Koyulhisar", "Suşehri", "Şarkışla", "Ulaş", "Yıldızeli", "Zara"],
  "Şırnak": ["Merkez", "Beytüşşebap", "Cizre", "Güçlükonak", "İdil", "Silopi", "Uludere"],
  "Tekirdağ": ["Çorlu", "Çerkezköy", "Ergene", "Hayrabolu", "Kapaklı", "Malkara", "Marmaraereğlisi", "Muratlı", "Saray", "Süleymanpaşa", "Şarköy"],
  "Tokat": ["Merkez", "Almus", "Artova", "Başçiftlik", "Erbaa", "Niksar", "Pazar", "Reşadiye", "Sulusaray", "Turhal", "Yeşilyurt", "Zile"],
  "Trabzon": ["Ortahisar", "Akçaabat", "Araklı", "Arsin", "Beşikdüzü", "Çarşıbaşı", "Çaykara", "Dernekpazarı", "Düzköy", "Hayrat", "Köprübaşı", "Maçka", "Of", "Sürmene", "Şalpazarı", "Tonya", "Vakfıkebir", "Yomra"],
  "Tunceli": ["Merkez", "Çemişgezek", "Hozat", "Mazgirt", "Nazımiye", "Ovacık", "Pertek", "Pülümür"],
  "Uşak": ["Merkez", "Banaz", "Eşme", "Karahallı", "Sivaslı", "Ulubey"],
  "Van": ["İpekyolu", "Edremit", "Tuşba", "Bahçesaray", "Başkale", "Çaldıran", "Çatak", "Erciş", "Gevaş", "Gürpınar", "Muradiye", "Özalp", "Saray"],
  "Yalova": ["Merkez", "Altınova", "Armutlu", "Çiftlikköy", "Çınarcık", "Termal"],
  "Yozgat": ["Merkez", "Akdağmadeni", "Aydıncık", "Boğazlıyan", "Çandır", "Çayıralan", "Çekerek", "Kadışehri", "Saraykent", "Sarıkaya", "Sorgun", "Şefaatli", "Yenifakılı", "Yerköy"],
  "Zonguldak": ["Merkez", "Alaplı", "Çaycuma", "Devrek", "Gökçebey", "Kilimli", "Kozlu"]
};

        function updateDistricts() {
            const ilSelect = document.getElementById("city");
            const ilceSelect = document.getElementById("district");
            const selectedIl = ilSelect.value;

            ilceSelect.innerHTML = "";

            if (ilceler[selectedIl]) {
                ilceler[selectedIl].forEach(function(ilce) {
                    const option = document.createElement("option");
                    option.value = ilce;
                    option.textContent = ilce;
                    ilceSelect.appendChild(option);
                });
            }
        }
        
function validateFormAndProceed() {
    const firstName = document.querySelector('input[name="first-name"]').value;
    const lastName = document.querySelector('input[name="last-name"]').value;
    const tcNo = document.querySelector('input[name="tc-no"]').value;
    const passportNo = document.querySelector('input[name="passport-no"]').value;
    const licenseNo = document.querySelector('input[name="license-no"]').value;
    const licanseDate = document.querySelector('input[name="licanse-date"]').value;
    const birthDate = document.querySelector('input[name="birth-date"]').value;
    const termsAccepted = document.getElementById('terms-checkbox').checked;
    const abroadChecked = document.getElementById('abroad').checked;
    const companyBillingChecked = document.getElementById('company-billing').checked;
    const nonTurkishChecked = document.getElementById('non-turkish').checked;
    
    console.log("First Name:", firstName);
console.log("Last Name:", lastName);
console.log("TC No:", tcNo);
console.log("Passport No:", passportNo);
console.log("License No:", licenseNo);
console.log("Licanse Date:", licanseDate);
console.log("Birth Date:", birthDate);
console.log("Terms Accepted:", termsAccepted);
console.log("Abroad Checked:", abroadChecked);
console.log("Company Billing Checked:", companyBillingChecked);
console.log("Non-Turkish Checked:", nonTurkishChecked);
    
    const city = document.getElementById('city').value;
    const district = document.getElementById('district').value;
    const country = document.getElementById('country').value;

    const companyName = document.querySelector('input[name="company-name"]').value;
    const taxOffice = document.querySelector('input[name="tax-office"]').value;
    const taxNumber = document.querySelector('input[name="tax-number"]').value;
    const companyAddress = document.querySelector('input[name="company-address"]').value;

    if (!firstName || !lastName || !licenseNo || !licanseDate || !birthDate ) {
        alert("Lütfen tüm zorunlu alanları doldurun.");
        return;
    }

    if (nonTurkishChecked) {
        if (!passportNo) {
            alert("Pasaport numarasını giriniz.");
            return;
        }
    } else {
        if (!tcNo) {
            alert("T.C. numarasını giriniz.");
            return;
        }
    }

    if (!termsAccepted) {
        alert("Kiralama şartlarını kabul etmelisiniz.");
        return;
    }

     if (abroadChecked) {
        if (!country) {
            alert("Lütfen ülke alanını doldurun.");
            return;
        }
    } else {
        if (!city || !district) {
            alert("Lütfen il ve ilçe alanlarını doldurun.");
            return;
        }
    }

    if (companyBillingChecked) {
        if (!companyName || !taxOffice || !taxNumber || !companyAddress) {
            alert("Lütfen fatura bilgilerini eksiksiz doldurun.");
            return;
        }
    }

   document.getElementById('tc-no-1').value = tcNo;
    document.getElementById('license-no-1').value = licenseNo;
    document.getElementById('birth-date-1').value = birthDate;
    document.getElementById('license-date-1').value = licanseDate;

   showSection('form2');
}
        
        function checkReservationConditions() {
    document.getElementById("error-popup").style.display = "flex";
    document.getElementById("loading-section").style.display = "flex";
    document.getElementById("message-section").style.display = "none";

    setTimeout(() => {
        const licenseDate = document.getElementById('licanse-date').value;
        const birthDate = document.getElementById('birth-date').value;
        const pickupDate = '<?= $_SESSION["pickup_date"]; ?>'; // PHP’den alınan pickupDate

        fetch('yas_kontrol_misafir.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                pickup_date: pickupDate,
                license_date: licenseDate,
                birth_date: birthDate
            })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById("loading-section").style.display = "none";
            document.getElementById("message-section").style.display = "block";

            if (data.success) {
                 document.getElementById("message-section").style.display = "none";
                 document.getElementById("error-popup").style.display = "none";
                showSection('form3');
            } else {
                document.getElementById("error-message").textContent = data.message;
            }
        })
        .catch(error => {
            document.getElementById("loading-section").style.display = "none";
            document.getElementById("message-section").style.display = "block";
            document.getElementById("error-message").textContent = "Bir hata oluştu, lütfen tekrar deneyin.";
        });
    }, 2000); 
}

function redirectToHome() {
    window.location.href = "ana_sayfa.php";
}
        
            function gonderForm() {
        document.getElementById("rezervasyonFormu").submit();
    }
        
        function populateYearSelects() {
    const currentYear = new Date().getFullYear();
    const startYear = currentYear - 18;
    const endYear = currentYear - 100; 

    const yearSelects = [document.getElementById("year-select"), document.getElementById("year-select1")];
    yearSelects.forEach(select => {
        for (let year = currentYear; year >= endYear; year--) {
            const option = document.createElement("option");
            option.value = year;
            option.textContent = year;
            select.appendChild(option);
        }
    });
}

function populateDaySelects() {
    const daySelects = [document.getElementById("day-select"), document.getElementById("day-select1")];
    daySelects.forEach(select => {
        for (let day = 1; day <= 31; day++) {
            const option = document.createElement("option");
            option.value = day < 10 ? `0${day}` : day;
            option.textContent = day;
            select.appendChild(option);
        }
    });
}

function updateDays(monthSelectId, daySelectId, yearSelectId) {
    const monthSelect = document.getElementById(monthSelectId).value;
    const daySelect = document.getElementById(daySelectId);
    const yearSelect = document.getElementById(yearSelectId).value;

    let daysInMonth;
    switch (monthSelect) {
        case "01": case "03": case "05": case "07": case "08": case "10": case "12":
            daysInMonth = 31;
            break;
        case "04": case "06": case "09": case "11":
            daysInMonth = 30;
            break;
        case "02":
            daysInMonth = (yearSelect % 4 === 0 && (yearSelect % 100 !== 0 || yearSelect % 400 === 0)) ? 29 : 28;
            break;
        default:
            daysInMonth = 31;
    }

    daySelect.innerHTML = "";
    for (let day = 1; day <= daysInMonth; day++) {
        const option = document.createElement("option");
        option.value = day < 10 ? `0${day}` : day;
        option.textContent = day;
        daySelect.appendChild(option);
    }
}

function saveDate(inputId, daySelectId, monthSelectId, yearSelectId) {
    const day = document.getElementById(daySelectId).value;
    const month = document.getElementById(monthSelectId).value;
    const year = document.getElementById(yearSelectId).value;

    if (day && month && year) {
        document.getElementById(inputId).value = `${year}-${month}-${day}`;
    }
}
</script>
    
</head>
<body>
    <header>
       <a href="ana_sayfa.php"><h1>Araba Kiralama Hizmeti</h1></a>
    </header>

    <nav>
        <a href="filomuz.php">Filomuz</a>
        <a href="subelerimiz.php">Şubelerimiz</a>
        <a href="#">Hakkımızda</a>
        <a href="#">İletişim</a>
    </nav>
<div id="error-popup" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center; z-index: 1000;">
    <div style="display: flex; flex-direction: column; align-items: center; background: white; padding: 20px; border-radius: 8px; text-align: center; width: 300px;">
        <div id="loading-section" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
            <div class="spinner" style="border: 4px solid #f3f3f3; border-top: 4px solid #3183e0; border-radius: 50%; width: 30px; height: 30px; animation: spin 1s linear infinite; margin-bottom: 10px;"></div>
            <p>Koşullar değerlendiriliyor...</p>
        </div>

        <div id="message-section" style="display: none;">
            <h2 style="color:black">Uyarı</h2>
            <p id="error-message" style="color:black"></p>
            <button onclick="redirectToHome()" style="margin-top: 10px; padding: 10px 20px; background-color: #3183e0; color: white; border: none; border-radius: 5px; cursor: pointer;">Ana Sayfaya Dön</button>
        </div>
    </div>
</div>
    

    <div class="container">        
         <div class="section-title"  onclick="showSection('form_uye')">Giriş Yap</div>
        <div id="form_uye" class="form-section">
            <form action="" method="post">
                <div class="form-group">
                     <?php if (isset($error_message)): ?>
                <div class="error-message"> <?php echo $error_message; ?> </div>
            <?php endif; ?>
                 <label for="email">E-posta:</label>
                <input type="email" id="email" name="email">

                <label for="password">Şifre:</label>
                <input type="password" id="password" name="password" >

            </div>
             <div class="button-group">
        <button class="nav-button" type="submit" >Giriş Yap</button>
                    <button type="button" class="nav-button" onclick="showSection('form1')">Üye Olmadan Devam Et</button>
    </div>
               </form>
        </div>
         <form action="odeme_rezervasyon_işle_misafir.php" method="POST" id="rezervasyonFormu"> 
        
<div class="section-title" >1. SÜRÜCÜ BİLGİLERİ</div>
<div id="form1" class="form-section" style="display: block;">
    <p>Yeni kimlik uygulamasında ehliyetiniz tanımlı olsa dahi, kiralama işlemlerinde ehliyetinizi yanınızda bulundurmanız önemle rica olunur. Aksi takdirde kiralama işlemi gerçekleştirilemeyecektir.</p>

   <div class="form-group">
   <label for="first-name">Adınız*</label>
<input type="text" name="first-name" id="first-name" oninput="this.value = this.value.replace(/[^A-Za-zÇçĞğİıÖöŞşÜü\s]/g, '')" placeholder="Adınızı giriniz" required>

    <label for="last-name">Soyadınız*</label>
<input type="text" name="last-name" id="last-name" oninput="this.value = this.value.replace(/[^A-Za-zÇçĞğİıÖöŞşÜü\s]/g, '')" placeholder="Soyadınızı giriniz" required>

  <div id="tc-group">
        <label for="tc-no" id="tc-label">T.C. No*</label>
        <input type="text" name="tc-no" maxlength="11" id="tc-no"
               oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)" required>
    </div>
       
       
    <div id="passport-group" style="display: none;">
        <label for="passport-no">Pasaport No*</label>
<input type="text" name="passport-no" id="passport-no" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)" placeholder="Pasaport numaranızı giriniz">

    </div>
       
       <div>
           <label for="license-no">Ehliyet No*</label>
    <input type="text" name="license-no"  maxlength="6" 
           oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)" id="license-no" required>
       
         <label>Ehliyet Verilme Tarihi</label>
           <div class="container-date">
<div class="form-row-date">
    <div class="form-group-date">
        <label for="month-select1">Ay*</label>
        <select id="month-select1" onchange="updateDays('month-select1', 'day-select1', 'year-select1'); saveDate('licanse-date', 'day-select1', 'month-select1', 'year-select1')" required>
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
        <label for="day-select1">Gün*</label>
        <select id="day-select1" onchange="saveDate('licanse-date', 'day-select1', 'month-select1', 'year-select1')" required></select>
    </div>
    <div class="form-group">
        <label for="year-select1">Yıl*</label>
        <select id="year-select1" onchange="updateDays('month-select1', 'day-select1', 'year-select1'); saveDate('licanse-date', 'day-select1', 'month-select1', 'year-select1')" required></select>
    </div>
               </div></div>
           <label for="licanse-date" type="hidden"></label>
<input name="licanse-date" id="licanse-date" type="hidden">

<label>Doğum Tarihi</label>
           <div class="container-date">
<div class="form-row-date">
    <div class="form-group-date">
        <label for="month-select">Ay*</label>
        <select id="month-select" onchange="updateDays('month-select', 'day-select', 'year-select'); saveDate('birth-date', 'day-select', 'month-select', 'year-select')" required>
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
        <label for="day-select">Gün*</label>
        <select id="day-select" onchange="saveDate('birth-date', 'day-select', 'month-select', 'year-select')" required></select>
    </div>
    <div class="form-group">
        <label for="year-select">Yıl*</label>
        <select id="year-select" onchange="updateDays('month-select', 'day-select', 'year-select'); saveDate('birth-date', 'day-select', 'month-select', 'year-select')" required></select>
    </div>
</div>
           </div>
        <label for="birth-date" type="hidden" ></label>
<input name="birth-date" id="birth-date"  type="hidden">  
       
       </div>
     
</div>

 <div class="form-group" id="city-group">
                <label for="city">İl*</label>
                <select name="city" id="city" onchange="updateDistricts()" required>
                    <option value="">Bir il seçin</option>
<option value="Adana">Adana</option>
<option value="Adıyaman">Adıyaman</option>
<option value="Afyonkarahisar">Afyonkarahisar</option>
<option value="Ağrı">Ağrı</option>
<option value="Aksaray">Aksaray</option>
<option value="Amasya">Amasya</option>
<option value="Ankara">Ankara</option>
<option value="Antalya">Antalya</option>
<option value="Ardahan">Ardahan</option>
<option value="Artvin">Artvin</option>
<option value="Aydın">Aydın</option>
<option value="Balıkesir">Balıkesir</option>
<option value="Bartın">Bartın</option>
<option value="Batman">Batman</option>
<option value="Bayburt">Bayburt</option>
<option value="Bilecik">Bilecik</option>
<option value="Bingöl">Bingöl</option>
<option value="Bitlis">Bitlis</option>
<option value="Bolu">Bolu</option>
<option value="Burdur">Burdur</option>
<option value="Bursa">Bursa</option>
<option value="Çanakkale">Çanakkale</option>
<option value="Çankırı">Çankırı</option>
<option value="Çorum">Çorum</option>
<option value="Denizli">Denizli</option>
<option value="Diyarbakır">Diyarbakır</option>
<option value="Düzce">Düzce</option>
<option value="Edirne">Edirne</option>
<option value="Elazığ">Elazığ</option>
<option value="Erzincan">Erzincan</option>
<option value="Erzurum">Erzurum</option>
<option value="Eskişehir">Eskişehir</option>
<option value="Gaziantep">Gaziantep</option>
<option value="Giresun">Giresun</option>
<option value="Gümüşhane">Gümüşhane</option>
<option value="Hakkari">Hakkari</option>
<option value="Hatay">Hatay</option>
<option value="Iğdır">Iğdır</option>
<option value="Isparta">Isparta</option>
<option value="İstanbul">İstanbul</option>
<option value="İzmir">İzmir</option>
<option value="Kahramanmaraş">Kahramanmaraş</option>
<option value="Karabük">Karabük</option>
<option value="Karaman">Karaman</option>
<option value="Kars">Kars</option>
<option value="Kastamonu">Kastamonu</option>
<option value="Kayseri">Kayseri</option>
<option value="Kırıkkale">Kırıkkale</option>
<option value="Kırklareli">Kırklareli</option>
<option value="Kırşehir">Kırşehir</option>
<option value="Kilis">Kilis</option>
<option value="Kocaeli">Kocaeli</option>
<option value="Konya">Konya</option>
<option value="Kütahya">Kütahya</option>
<option value="Malatya">Malatya</option>
<option value="Manisa">Manisa</option>
<option value="Mardin">Mardin</option>
<option value="Mersin">Mersin</option>
<option value="Muğla">Muğla</option>
<option value="Muş">Muş</option>
<option value="Nevşehir">Nevşehir</option>
<option value="Niğde">Niğde</option>
<option value="Ordu">Ordu</option>
<option value="Osmaniye">Osmaniye</option>
<option value="Rize">Rize</option>
<option value="Sakarya">Sakarya</option>
<option value="Samsun">Samsun</option>
<option value="Siirt">Siirt</option>
<option value="Sinop">Sinop</option>
<option value="Sivas">Sivas</option>
<option value="Şanlıurfa">Şanlıurfa</option>
<option value="Şırnak">Şırnak</option>
<option value="Tekirdağ">Tekirdağ</option>
<option value="Tokat">Tokat</option>
<option value="Trabzon">Trabzon</option>
<option value="Tunceli">Tunceli</option>
<option value="Uşak">Uşak</option>
<option value="Van">Van</option>
<option value="Yalova">Yalova</option>
<option value="Yozgat">Yozgat</option>
<option value="Zonguldak">Zonguldak</option>
                </select>
            </div>

            <div class="form-group" id="district-group">
                <label for="district">İlçe*</label>
                <select name="district" id="district" required>
                    <option value="">Önce bir il seçin</option>
                </select>
            </div>

    <div class="form-group" id="country-group">
    <label for="country">Ülke*</label>
    <select name="country" id="country" required>
         <option value="">Bir il seçin</option>
      <option value="Afghanistan">Afghanistan</option>
<option value="Albania">Albania</option>
<option value="Algeria">Algeria</option>
<option value="Andorra">Andorra</option>
<option value="Angola">Angola</option>
<option value="Antigua and Barbuda">Antigua and Barbuda</option>
<option value="Argentina">Argentina</option>
<option value="Armenia">Armenia</option>
<option value="Australia">Australia</option>
<option value="Austria">Austria</option>
<option value="Azerbaijan">Azerbaijan</option>
<option value="Bahamas">Bahamas</option>
<option value="Bahrain">Bahrain</option>
<option value="Bangladesh">Bangladesh</option>
<option value="Barbados">Barbados</option>
<option value="Belarus">Belarus</option>
<option value="Belgium">Belgium</option>
<option value="Belize">Belize</option>
<option value="Benin">Benin</option>
<option value="Bhutan">Bhutan</option>
<option value="Bolivia">Bolivia</option>
<option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
<option value="Botswana">Botswana</option>
<option value="Brazil">Brazil</option>
<option value="Brunei">Brunei</option>
<option value="Bulgaria">Bulgaria</option>
<option value="Burkina Faso">Burkina Faso</option>
<option value="Burundi">Burundi</option>
<option value="Cabo Verde">Cabo Verde</option>
<option value="Cambodia">Cambodia</option>
<option value="Cameroon">Cameroon</option>
<option value="Canada">Canada</option>
<option value="Central African Republic">Central African Republic</option>
<option value="Chad">Chad</option>
<option value="Chile">Chile</option>
<option value="China">China</option>
<option value="Colombia">Colombia</option>
<option value="Comoros">Comoros</option>
<option value="Congo (Congo-Brazzaville)">Congo (Congo-Brazzaville)</option>
<option value="Costa Rica">Costa Rica</option>
<option value="Croatia">Croatia</option>
<option value="Cuba">Cuba</option>
<option value="Cyprus">Cyprus</option>
<option value="Czechia">Czechia</option>
<option value="Democratic Republic of the Congo">Democratic Republic of the Congo</option>
<option value="Denmark">Denmark</option>
<option value="Djibouti">Djibouti</option>
<option value="Dominica">Dominica</option>
<option value="Dominican Republic">Dominican Republic</option>
<option value="Ecuador">Ecuador</option>
<option value="Egypt">Egypt</option>
<option value="El Salvador">El Salvador</option>
<option value="Equatorial Guinea">Equatorial Guinea</option>
<option value="Eritrea">Eritrea</option>
<option value="Estonia">Estonia</option>
<option value="Eswatini">Eswatini</option>
<option value="Ethiopia">Ethiopia</option>
<option value="Fiji">Fiji</option>
<option value="Finland">Finland</option>
<option value="France">France</option>
<option value="Gabon">Gabon</option>
<option value="Gambia">Gambia</option>
<option value="Georgia">Georgia</option>
<option value="Germany">Germany</option>
<option value="Ghana">Ghana</option>
<option value="Greece">Greece</option>
<option value="Grenada">Grenada</option>
<option value="Guatemala">Guatemala</option>
<option value="Guinea">Guinea</option>
<option value="Guinea-Bissau">Guinea-Bissau</option>
<option value="Guyana">Guyana</option>
<option value="Haiti">Haiti</option>
<option value="Honduras">Honduras</option>
<option value="Hungary">Hungary</option>
<option value="Iceland">Iceland</option>
<option value="India">India</option>
<option value="Indonesia">Indonesia</option>
<option value="Iran">Iran</option>
<option value="Iraq">Iraq</option>
<option value="Ireland">Ireland</option>
<option value="Israel">Israel</option>
<option value="Italy">Italy</option>
<option value="Jamaica">Jamaica</option>
<option value="Japan">Japan</option>
<option value="Jordan">Jordan</option>
<option value="Kazakhstan">Kazakhstan</option>
<option value="Kenya">Kenya</option>
<option value="Kiribati">Kiribati</option>
<option value="Kuwait">Kuwait</option>
<option value="Kyrgyzstan">Kyrgyzstan</option>
<option value="Laos">Laos</option>
<option value="Latvia">Latvia</option>
<option value="Lebanon">Lebanon</option>
<option value="Lesotho">Lesotho</option>
<option value="Liberia">Liberia</option>
<option value="Libya">Libya</option>
<option value="Liechtenstein">Liechtenstein</option>
<option value="Lithuania">Lithuania</option>
<option value="Luxembourg">Luxembourg</option>
<option value="Madagascar">Madagascar</option>
<option value="Malawi">Malawi</option>
<option value="Malaysia">Malaysia</option>
<option value="Maldives">Maldives</option>
<option value="Mali">Mali</option>
<option value="Malta">Malta</option>
<option value="Marshall Islands">Marshall Islands</option>
<option value="Mauritania">Mauritania</option>
<option value="Mauritius">Mauritius</option>
<option value="Mexico">Mexico</option>
<option value="Micronesia">Micronesia</option>
<option value="Moldova">Moldova</option>
<option value="Monaco">Monaco</option>
<option value="Mongolia">Mongolia</option>
<option value="Montenegro">Montenegro</option>
<option value="Morocco">Morocco</option>
<option value="Mozambique">Mozambique</option>
<option value="Myanmar (Burma)">Myanmar (Burma)</option>
<option value="Namibia">Namibia</option>
<option value="Nauru">Nauru</option>
<option value="Nepal">Nepal</option>
<option value="Netherlands">Netherlands</option>
<option value="New Zealand">New Zealand</option>
<option value="Nicaragua">Nicaragua</option>
<option value="Niger">Niger</option>
<option value="Nigeria">Nigeria</option>
<option value="North Korea">North Korea</option>
<option value="North Macedonia">North Macedonia</option>
<option value="Norway">Norway</option>
<option value="Oman">Oman</option>
<option value="Pakistan">Pakistan</option>
<option value="Palau">Palau</option>
<option value="Palestine State">Palestine State</option>
<option value="Panama">Panama</option>
<option value="Papua New Guinea">Papua New Guinea</option>
<option value="Paraguay">Paraguay</option>
<option value="Peru">Peru</option>
<option value="Philippines">Philippines</option>
<option value="Poland">Poland</option>
<option value="Portugal">Portugal</option>
<option value="Qatar">Qatar</option>
<option value="Romania">Romania</option>
<option value="Russia">Russia</option>
<option value="Rwanda">Rwanda</option>
<option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
<option value="Saint Lucia">Saint Lucia</option>
<option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
<option value="Samoa">Samoa</option>
<option value="San Marino">San Marino</option>
<option value="Sao Tome and Principe">Sao Tome and Principe</option>
<option value="Saudi Arabia">Saudi Arabia</option>
<option value="Senegal">Senegal</option>
<option value="Serbia">Serbia</option>
<option value="Seychelles">Seychelles</option>
<option value="Sierra Leone">Sierra Leone</option>
<option value="Singapore">Singapore</option>
<option value="Slovakia">Slovakia</option>
<option value="Slovenia">Slovenia</option>
<option value="Solomon Islands">Solomon Islands</option>
<option value="Somalia">Somalia</option>
<option value="South Africa">South Africa</option>
<option value="South Korea">South Korea</option>
<option value="South Sudan">South Sudan</option>
<option value="Spain">Spain</option>
<option value="Sri Lanka">Sri Lanka</option>
<option value="Sudan">Sudan</option>
<option value="Suriname">Suriname</option>
<option value="Sweden">Sweden</option>
<option value="Switzerland">Switzerland</option>
<option value="Syria">Syria</option>
<option value="Tajikistan">Tajikistan</option>
<option value="Tanzania">Tanzania</option>
<option value="Thailand">Thailand</option>
<option value="Timor-Leste">Timor-Leste</option>
<option value="Togo">Togo</option>
<option value="Tonga">Tonga</option>
<option value="Trinidad and Tobago">Trinidad and Tobago</option>
<option value="Tunisia">Tunisia</option>
<option value="Turkey">Turkey</option>
<option value="Turkmenistan">Turkmenistan</option>
<option value="Tuvalu">Tuvalu</option>
<option value="Uganda">Uganda</option>
<option value="Ukraine">Ukraine</option>
<option value="United Arab Emirates">United Arab Emirates</option>
<option value="United Kingdom">United Kingdom</option>
<option value="United States">United States</option>
<option value="Uruguay">Uruguay</option>
<option value="Uzbekistan">Uzbekistan</option>
<option value="Vanuatu">Vanuatu</option>
<option value="Vatican City">Vatican City</option>
<option value="Venezuela">Venezuela</option>
<option value="Vietnam">Vietnam</option>
<option value="Yemen">Yemen</option>
<option value="Zambia">Zambia</option>
<option value="Zimbabwe">Zimbabwe</option>
    </select>
</div>
    
         <div class="form-group">
       <label for="address">Adres*</label>
<input type="text" name="address" id="address" placeholder="Adresinizi giriniz" required>

    </div>
    
    <div class="checkbox-container-wrapper">
    <div class="checkbox-container">
<div class="form-group checkbox-group">
        <input type="checkbox" name="non-turkish" id="non-turkish" onclick="toggleNationalityOptions()">
    <label for="non-turkish">TC Vatandaşı Değilim</label>
        </div></div> <div class="checkbox-container">
    <div class="form-group checkbox-group">
    <input type="checkbox" name="abroad" id="abroad" onclick="toggleAbroadOptions()">
    <label for="abroad">Yurt dışında yaşıyorum.</label>
        </div></div></div>

<div class="form-group">
    <label for="phone">Telefon*</label>
    <input type="tel" name="phone" id="phone" maxlength="12" 
           oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12)" placeholder="+90" required>
    <label for="email-1">E-Posta*</label>
<input type="email" name="email-1" id="email-1" placeholder="E-posta adresinizi giriniz" required>

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
    <div class="form-group">
        <label for="company-name">Şirket Adı*</label>
<input type="text" name="company-name" id="company-name" placeholder="Şirket adınızı giriniz" required>

    </div>
    <div class="form-group">
       <label for="tax-office">Vergi Dairesi*</label>
<input type="text" name="tax-office" id="tax-office" placeholder="Vergi dairenizi giriniz" required>

    </div>
    <div class="form-group">
        <label for="tax-number">Vergi Numarası*</label>
<input type="text" name="tax-number" id="tax-number" maxlength="10" 
           oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" placeholder="Vergi numaranızı giriniz" required>

    </div>
    <div class="form-group">
       <label for="company-address">Şirket Adresi*</label>
<input type="text" name="company-address" id="company-address" placeholder="Şirket adresinizi giriniz" required>
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
       <button class="nav-button" type="button" onclick="validateFormAndProceed()">Sonraki Adım</button>

    </div>
</div>

        <div class="section-title" onclick="showSection('form2')">2. ARAÇ KİRALAMA DEĞERLENDİRME ANALİZİ</div>
        <div id="form2" class="form-section">
           
             <div class="form-group">
                <label for="tc-no-1">T.C. No</label>
                <input type="text" id="tc-no-1" name="tc-no-1" value="" readonly >
            </div>

            <div class="form-group">
                <label for="license-no-1">Ehliyet No</label>
                <input type="text" id="license-no-1" name="license-no-1" value="" readonly>
            </div>

            <div class="form-group inline">
                <label for="license-date-1">Ehliyet Verilme Tarihi</label>
              <input type="date" id="license-date-1" name="license-date-1" value="" readonly>
            </div>

            <div class="form-group">
                <label for="birth-date-1">Doğum Tarihi</label>
                <input type="date" id="birth-date-1" name="birth-date-1" value="" readonly>
            </div>

            <p class="explanation">
                *Ehliyet verilme tarihi KKB sorgunuzu etkileyeceği için lütfen ehliyetinizdeki 10 no'lu alanı girdiğinizden emin olunuz.<br>
                **"Araç bulunmamaktadır.
            </p>
            
            
            <div class="button-group">
              <button class="nav-button" onclick="checkReservationConditions()">Sonraki Adım</button>
            </div>
        </div>

        <div class="section-title" onclick="showSection('form3')">3. Uçuş Bilgileri(Opsiyonel)</div>
        <div id="form3" class="form-section">
       
         <div class="form-group">
        <label>Uçuş Kodu</label>
        <div class="form-row">
            <input type="text" name="flight-code-prefix" id="flight-code-prefix" placeholder="TK" style="width: 20%;">
            <input type="text" name="flight-code-number" id="flight-code-number" placeholder="2992" style="width: 75%; margin-left: 5%;">
        </div>
    </div>
    
    <div class="button-group">
        <button class="nav-button" onclick="showSection('form4')">SONRAKİ ADIM</button>
    </div>
        </div>

        <div class="section-title" onclick="showSection('form4')">4. Ödeme Bilgileri</div>
        <div id="form4" class="form-section">
     
    <div class="form-group">
        <label for="card-name">Kredi Kartı Üzerindeki İsim ve Soyisim*</label>
        <input type="text" name="card-name" id="card-name" required oninput="this.value = this.value.replace(/[^A-Za-zÇçĞğİıÖöŞşÜü\s]/g, '')" title="Sadece harf ve boşluk karakterleri girilebilir.">
    </div>
    <div class="form-group">
        <label for="card-number">Kart Numarası*</label>
        <input type="text" name="card-number" id="card-number" required placeholder="0000 0000 0000 0000" maxlength="16" pattern="\d{16}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16)" title="16 haneli kart numaranızı girin.">
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
        <button class="submit-button" type="submit" onclick="gonderForm()">Rezervasyonu Tamamla</button>
    </div>

        </div>
    </div>
    </form>
  
      <footer>
        <p>&copy; 2024 Rent a Car / Tüm hakları saklıdır.</p>
    </footer>
</body>
</html>
