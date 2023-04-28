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
                <li><a href="basket.php" class="page-scroll"><img src="images/basket.jpg" width="30px" height="30px"></a><li>
                <?php 
                if (isset($_SESSION["useruid"])) {
                    echo '<li><a href="profile.php" class="page-scroll">Profile page</a></li>';
                    echo '<li><a href="logout.inc.php" class="page-scroll">Logout</a></li>';
                }
                else {
                    echo '<li><a href="login.php" class="page-scroll">Log In</a></li>';
                    echo '<li><a href="signup.php" class="page-scroll">Register</a></li>';
                }
                ?>
            </ul>
        </nav>  
    </div>