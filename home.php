<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: home.php");
}
?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/css/home.css">
    <link href='https://fonts.googleapis.com/css?family=Merriweather' rel='stylesheet'>
</head>
<body>
    <header>
        <h1>Beauty & Skin Care</h1>
    </header>
    <div class="container-fluid">
        <nav class="main-nav main-nav-scrolled">
            <ul class="navigation">
                <li>
                    <a href="#home" class="page-scroll">Home</a>
                </li>
                <li>
                    <a href="#store" class="page-scroll">Store</a>
                </li>
                <li>
                    <a href="#consultation" class="page-scroll">Consultations</a>
                </li>
                <li>
                    <a href="#aboutus" class="page-scroll">About Us</a>
                </li>
                <li>
                    <a href="profile.php" class="page-scroll">Profile</a>
                </li>
                <?php if (!empty($_SESSION['user'])): ?>
                    <li><a href="basket.php" class="page-scroll"><img src="images/basket.jpg" width="30px" height="30px"></a><li>
                    <li><a href="vendor/exit.php" class="page-scroll">Sign Out</a></li>
                <?php else: 
                    echo '<li><a href="login.php" class="page-scroll">Log In</a></li>';
                    echo '<li><a href="registration.php" class="page-scroll">Register</a></li>';
                ?>
                <?php endif;?>
            </ul>
        </nav>  
    </div>
    <div class="home-main">
        <div class="homeContent" id="home">
            <div class="smallSpace"></div>
            <div class="someContent">
                <h2>Soins</h2>
                <div class="smallContent"></div>
                <h3>Care of your skin</h3>
                <div class="afterContent"></div>
            </div>
        </div>
        
        <div class="storeContent" id="store">
            <div class="someProducts">
                <img src="../images/product1.png" class="productsimg">
                <img src="../images/product2.png" class="productsimg">
                <img src="../images/product3.png" class="productsimg">
                <img src="../images/product4.png" class="productsimg">
                <img src="../images/product5.png" class="productsimg">
            </div>
            <div class="storeInfo">
                <p class="smth1">Products available from many countries</p>
                <p class="smth2">You can see it in store</p>
                <p class="smth3">🠗</p>
                <a href="store.php">Store</a>
            </div>
        </div>
        <div class="consContent" id="consultation">
           <p class="smth4">Experts will help you choose the right care for your skin</p> 
           <div class="consImg">
                <img src="../images/cons1.avif" class="consimg">
                <img src="../images/cons2.avif" class="consimg">
            </div>
            <a href="consultation.php">Consultations</a>
        </div>
        <div class="aboutusContent" id="aboutus">
            <div class="blankSp"></div>
            <div class="workInfo">
                <p class="smth5">One of the newest and most modern companies!</p>
                <p class="smth6">Working hours:</p>
                <p class="worktime">Monday -> 9:00-19:00</p>
                <p class="worktime">Tuesday -> 9:00-19:00</p>
                <p class="worktime">Wednesday -> 9:00-19:00</p>
                <p class="worktime">Thursday -> 9:00-19:00</p>
                <p class="worktime">Friday -> 10:00-19:00</p>
                <p class="worktime">Saturday -> 11:00-19:00</p>
                <p class="worktime">Sunday -> 11:00-18:00</p>
                <p class="moreInfo">For more information click <a href="aboutus.php" class="someMoreInfo">here</a></p>
            </div>
        </div>
</body>
<script src="/js/home.js"></script>  