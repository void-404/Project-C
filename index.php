<?php
include('header.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <?php 

        // Bericht zodat je kan zien of het werkt, later weghalen want is lelijk
            if (isset($_SESSION['userId'])) {
                echo '<p>You are logged in !</p>';
            }
            else {
                echo '<p>You are logged out !</p>';
            }

        ?>

        <!-- Planten homepage -->
        <div class="plant">
            <img src="images/plant1.jpg" alt="">
        </div>

        <div class="plant">
            <img src="images/plant2.jpg" alt="">
        </div>
        
        <div class="plant">
            <img src="images/plant4.jpg" alt="">
        </div>

        <div class="plant">
            <img src="images/plant4.jpg" alt="">
        </div>

        <div class="plant">
            <img src="images/plant4.jpg" alt="">
        </div>

        <div class="plant">
            <img src="images/plant4.jpg" alt="">
        </div>
    </div>
</body>
</html>

<?php 
    include('footer.php')
?>
