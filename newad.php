<?php
session_start();
include('header.php');
?>
<body>
<?php
if (isset($_SESSION['userId'])) {
    if(isset($_POST['ad-submit'])){
        require 'includes/dbh.inc.php';

        $plantname = $_POST["pname"];
        $plantlatinname = $_POST["psoort"];
        $plantcategory = $_POST["type"];
        $desc = $_POST["desc"];
        $water = $_POST["water"];
        $light = $_POST["licht"];
        $userId = $_SESSION['userId'];

        // Insert blogpost data into database
        $sql = "INSERT INTO Advertisement(plantName, plantLatinName, plantCategory, plantDesc, waterManage, lightPattern, Userid) VALUES(?,?,?,?,?,?,?)";
        $statement = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($statement, $sql)) {
            header("Location: newpost.php?error=sqlerror");
            echo '<div class="newposterror"><p>Er is iets fout gegaan (sql error).</p></div>';
        }
        else {
            mysqli_stmt_bind_param($statement, "sssis", $blogtitle, $blogcategory, $blogdescription, $userId, $blogLink);
            mysqli_stmt_execute($statement);
        }

        // Retrieve blogpost ID before inserting blogpost image(s) to database
        $sql = "SELECT idAd FROM Advertisement WHERE usedId = $userId ORDER BY postDate DESC LIMIT 1";
        $statement = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($statement, $sql)) {
            header("Location: newad.php?error=sqlerror");
            echo '<div class="newaderror"><p>Er is iets fout gegaan (sql error).</p></div>';
        }
        else {
            mysqli_stmt_execute($statement);
            $result = mysqli_stmt_get_result($statement);
            if ($row = mysqli_fetch_assoc($result)) {
                $blogId= $row['idAd'];
            }
        }

        //inserts image in uploads folder
        $fileCount = count($_FILES['file']['name']);
        for($i=0; $i < $fileCount; $i++){
            //checks if there is a file uploaded
            if(UPLOAD_ERR_OK == $_FILES['file']['error'][$i]){
                //give filename a time to avoid overwriting a file (unique name)
                $fileName = time().$_FILES['file']['name'][$i];

                //insert image to database
                $sql = "INSERT INTO AdImage(imgName, idAdvert) VALUES (?, ?)";
                $statement = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($statement, $sql)) {
                    header("Location: newad.php?error=sqlerror");
                    echo '<div class="newaderror"><p>Er is iets fout gegaan (sql error).</p></div>';
                }
                else {
                    mysqli_stmt_bind_param($statement, "ss", pathinfo($fileName, PATHINFO_FILENAME), $blogId);
                    mysqli_stmt_execute($statement);

                    //insert image to uploads folder
                    move_uploaded_file($_FILES['file']['tmp_name'][$i], 'uploads/'.$fileName);
                }
            }
        }
        header("Location: newpost.php?upload=success");
        echo '<div class="newaderror"><p>Uw blogpost is succesvol geupload!</p></div>';
    }
    ?>
    <div class="adform">
        <h2>Nieuwe advertentie</h2><br>
        <form action="" method="post" enctype="multipart/form-data" target="adpagina.php">
            <label for="pname">Plantnaam:<label style="color: red;">*</label></label><br>
            <input type="text" id="pname" name="pname" required><br><br>
            
            <label for="psoort">Plantensoort:</label><br>
            <input type="text" id="psoort" name="psoort"><br><br>
            
            <label>Type advertentie:<label style="color: red;">*</label></label><br>
            <input type="radio" id="stekje" name="type" value="stekje">
            <label for="stekje">Stekje</label><br>
            <input type="radio" id="zaad" name="type" value="zaad">
            <label for="zaad">Zaad</label><br>
            <input type="radio" id="kiemplant" name="type" value="kiemplant">
            <label for="kiemplant">Kiemplant</label><br>
            <input type="radio" id="none" name="type" value="none">
            <label for="none">Weet ik niet</label><br><br>
            
            <br><label for="desc">Beschrijving<label style="color: red;">*</label></label><br>
            <textarea id="desc" name="desc" rows="5" cols="50" required></textarea><br><br>
            
            <label>Hoeveelheid water nodig:<label style="color: red;">*</label></label><br>
            <label>
                <input style="position: absolute; opacity: 0; width: 0; height: 0; cursor: pointer;" type="radio" id="weinig" name="water" value="1">
                <img style="cursor: pointer;" src="images/weinigwater.png">
            </label>
            
            <label>
                <input style="position: absolute; opacity: 0; width: 0; height: 0; cursor: pointer;" type="radio" id="gemiddeld" name="water" value="2">
                <img style="cursor: pointer;" src="images/gemiddeldwater.png">
            </label>   
                     
            <label>
                <input style="position: absolute; opacity: 0; width: 0; height: 0; cursor: pointer;" type="radio" id="veel" name="water" value="3">
                <img style="cursor: pointer;" src="images/veelwater.png">
            </label>
            
            <label>
                <input style="position: absolute; opacity: 0; width: 0; height: 0; cursor: pointer;" type="radio" id="none" name="water" value="0">
                <img style="cursor: pointer;" src="images/weetniet.png">
            </label>
            <br><br>
            
            <label>Hoeveelheid licht nodig:<label style="color: red;">*</label></label><br>
            <label>
                <input style="position: absolute; opacity: 0; width: 0; height: 0; cursor: pointer;" type="radio" id="weinig" name="licht" value="1">
                <img style="cursor: pointer;" src="images/weiniglicht.png">
            </label>
            
            <label>
                <input style="position: absolute; opacity: 0; width: 0; height: 0; cursor: pointer;" type="radio" id="gemiddeld" name="licht" value="2">
                <img style="cursor: pointer;" src="images/gemiddeldlicht.png">
            </label>   
                     
            <label>
                <input style="position: absolute; opacity: 0; width: 0; height: 0; cursor: pointer;" type="radio" id="veel" name="licht" value="3">
                <img style="cursor: pointer;" src="images/veellicht.png">
            </label>
            
            <label>
                <input style="position: absolute; opacity: 0; width: 0; height: 0; cursor: pointer;" type="radio" id="none" name="licht" value="0">
                <img style="cursor: pointer;" src="images/weetniet.png">
            </label>
            <br><br>
            
            <label>Selecteer een foto (max 1MB):</label><br>
            <input type="file" name="files[]" id="file" multiple><br><br>
            
            <label><label style="color: red;">*</label> = verplicht</label><br><br>
            <input class="newAdButtons" type="submit" name="ad-submit" value="Plaatsen!">
        </form>
        

    </div>
    <?php
} else {
    echo '<div class="notloggedin">
            <h4>Om een advertentie te kunnen plaatsen moet u eerst ingelogd zijn. Klik <a href="loginpagina">HIER</a> om in te loggen.</h4>
          </div>';
}
?>

</body>

<?php
include('footer.php');
?>