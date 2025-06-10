<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Üye Ol</title>
    
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
        .registration-form h2 {
            text-align: center;
            color: #3183e0;
            font-size: 1.9em;
        }
        .registration-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
            font-size: 1.3em;
        }
        .registration-form input, select {
            width: 97%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 1.3em;
        }
        .registration-form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 1.3em;
        }
        .registration-form button {
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
        .registration-form button:hover {
            background-color: #106db0;
        }
          @media (max-width: 1100px) {
         
            header a{
                font-size: 1.3em;
            }
        }
        @media (max-width: 850px) {
         
            header a{
                font-size: 1em;
            }
             nav a{
                font-size: 1em;
            }
        }
        
        .container-date {
    padding: 10px;
    background-color: white;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
} .form-row {
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
        
    </style>
    <script>
        window.onload = function(){
            populateYearSelects();
            populateDaySelects();
            toggleAbroadOptions()
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

        function toggleAbroadOptions() {
            const abroadCheckbox = document.getElementById('abroad');
            const cityGroup = document.getElementById('city-group');
            const districtGroup = document.getElementById('district-group');
            const countryGroup = document.getElementById('country-group');
            const citySelect = document.getElementById('city');
            const districtSelect = document.getElementById('district');
            const countrySelect = document.getElementById('country');

            if (abroadCheckbox.checked) {
                cityGroup.style.display = 'none';
                districtGroup.style.display = 'none';
                countryGroup.style.display = 'block';
                citySelect.removeAttribute('required');
                districtSelect.removeAttribute('required');
                countrySelect.setAttribute('required', 'required');
            } else {
                cityGroup.style.display = 'block';
                districtGroup.style.display = 'block';
                countryGroup.style.display = 'none';
                citySelect.setAttribute('required', 'required');
                districtSelect.setAttribute('required', 'required');
                countrySelect.removeAttribute('required');
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
       <a href="uye_girisi.php" class="login-button">Üye Girişi</a>
    </header>

    <nav>
        <a href="filomuz.php">Filomuz</a>
        <a href="subelerimiz.php">Şubelerimiz</a>
        <a href="#">Hakkımızda</a>
        <a href="#">İletişim</a>
    </nav>

    <div class="container">
        <div class="registration-form">
            <h2>Üye Ol</h2>
            <form action="uye_olustur.php" method="post">
                <label for="ad_soyad">Ad Soyad:</label>
                <input type="text" id="ad_soyad" name="ad_soyad" required>

     
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

                <label for="ehliyet_seri_no">Ehliyet Seri No:</label>
                <input type="text" id="ehliyet_seri_no" name="ehliyet_seri_no" required>

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

                <label for="telefon">Telefon:</label>
                <input type="tel" id="telefon" name="telefon" maxlength="12" 
           oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12)" required>
                
                <div class="checkbox-container-wrapper">
    <div class="checkbox-container">
                <div class="form-group checkbox-group">
        <input type="checkbox" name="non-turkish" id="non-turkish" onclick="toggleNationalityOptions()">
                        <label for="non-turkish">TC Vatandaşı Değilim</label>
        </div>  </div> <div class="checkbox-container">
                     <div class="form-group checkbox-group">
    <input type="checkbox" name="abroad" id="abroad" onclick="toggleAbroadOptions()">
    <label for="abroad">Yurt dışında yaşıyorum.</label>
                    </div>  </div></div>
                
                <div class="registration-form" id="city-group">
                <label>İl*</label>
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

            <div class="registration-form" id="district-group">
                <label>İlçe*</label>
                <select name="district" id="district" required>
                    <option value="">Önce bir il seçin</option>
                </select>
            </div>

    <div class="registration-form" id="country-group">
    <label>Ülke*</label>
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

                <label for="adres">Adres:</label>
                <input type="text" id="adres" name="adres" required>
                
                
                
                 <div id="tc-group">
        <label id="tc-label">T.C. No*</label>
        <input type="text" name="tc-no" maxlength="11" id="tc-no"
               oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)"  required>
    </div>

    <div id="passport-group" style="display: none;">
        <label id="passport-label">Pasaport No*</label>
        <input type="text" name="passport-no" maxlength="11" id="passport-no"
               oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)" >
    </div>
                
                <label for="email">E-posta:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Şifre:</label>
                <input type="password" id="password" name="password" required>

                <label for="confirm_password">Şifre Tekrarı:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                
                

                <button type="submit">Üye Ol</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Rent a Car / Tüm hakları saklıdır.</p>
    </footer>
</body>
</html>
