
<?php
include("baglanti.php");

if (isset($_POST['branch_id'])) {
    $branchId = mysqli_real_escape_string($baglan, $_POST['branch_id']);
    $sql = "SELECT * FROM subeler WHERE id = '$branchId'";
    $result = mysqli_query($baglan, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo "<h2>" . $row['il'] . ' ' . $row['ilce'] . " Şubesi</h2>";
        echo "<h4>Adres:</h4><p>" . $row['adres'] . "</p>";
        echo "<h4>Telefon:</h4><p>" . $row['telefon'] . "</p>";
        echo "<h4>Çalışma Saatleri:</h4><p>" . $row['is_basi'] . " - " . $row['is_sonu'] . "</p>";
        echo "<h4>" . $row['ilce'] . " Hakkında:</h4><p>" . $row['hakkinda'] . "</p>";
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
                    <label for="pickup-location">Teslim Alınacak Şube:</label>
                    <select id="pickup-location" name="pickup_location" required>
                        <option value="">Şube Seçin</option>
                        <?php
                        $sql = "SELECT CONCAT(il, ' ', ilce) AS il_ilce FROM subeler ORDER BY il ASC, ilce ASC";
                        $allBranches = mysqli_query($baglan, $sql);

                        while ($branchRow = mysqli_fetch_assoc($allBranches)) {
                            $selected = ($branchRow['il_ilce'] === $row['il'] . ' ' . $row['ilce']) ? "selected" : "";
                            echo "<option value='" . $branchRow['il_ilce'] . "' $selected>" . $branchRow['il_ilce'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <label for="return-location">Teslim Edilecek Şube:</label>
                    <select id="return-location" name="return_location" required>
                        <option value="">Şube Seçin</option>
                        <?php
                        $allBranches = mysqli_query($baglan, $sql);
                        while ($branchRow = mysqli_fetch_assoc($allBranches)) {
                            $selected = ($branchRow['il_ilce'] === $row['il'] . ' ' . $row['ilce']) ? "selected" : "";
                            echo "<option value='" . $branchRow['il_ilce'] . "' $selected>" . $branchRow['il_ilce'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit">Araç Bul</button>
            </form>
        </div>

        <?php
        if (isset($row['konum'])) {
            echo '<iframe src="https://www.google.com/maps/embed?' . $row['konum'] . '" width="100%" height="500" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';
        } else {
            echo "<p>Şube konum bilgisi bulunamadı.</p>";
        }
    } else {
        echo "<p>Şube bilgileri bulunamadı.</p>";
    }
}
?>





