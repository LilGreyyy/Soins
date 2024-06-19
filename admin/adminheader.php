<?php
session_start(); // Start the session

// Initialize $showLogin to true by default
$showLogin = true;
$showLogout = false;

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $showLogin = false; // User is logged in, so hide login and sign up buttons
    $showLogout = true; // Show the logout button
}

// Check if the user clicked on the logout link
if (isset($_GET['logout'])) {
    // Destroy the session
    session_destroy();
    
    // Redirect to the login page or any other desired page
    header("Location: /index.php");
    exit();
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/css/header.css">
    <link href='https://fonts.googleapis.com/css?family=Merriweather' rel='stylesheet'>
</head>
<body>
    <div class="container">
        <header class="site-header">
            <div class="header__content--flow">
                <section class="header-content-left">
                <a href="#" class="brand-logo">
                    <img src="/images/chrome_Vz98ykiybM.png" width="120" height="50">
                    </a>
                    <button class="nav-toggle">
                        <span class="toggle--icon">
                        </span>
                    </button>
                </section>
                <section class="header-content-right">
                    <nav class="header-nav">
                        <ul class="nav__list" role="navigation">
                            <li class="list-item">
                                <a href="admin.php" class="nav__link">Mājaslapa</a>
                            </li>
                            <li class="list-item">
                                <a href="/admin/storeupdate.php" class="nav__link">Produkti</a>
                            </li>
                            <li class="list-item">
                                <a href="/admin/adminconsultation.php" class="nav__link">Konsultācijas</a>
                            </li>
                            <li class="list-item">
                                <a href="/admin/adminmasters.php" class="nav__link">Meistari</a>
                            </li>
                            <li class="list-item">
                                <a href="/admin/users.php" class="nav__link">Lietotāji</a>
                            </li>
                            <li class="list-item">
                                <a href="/admin/admincategories.php" class="nav__link">Meistaru kategorijas</a>
                            </li>
                            <?php if ($showLogin): ?>
                                <li class="list-item">
                                    <a href="/login.php" class="nav__link">Log In</a>
                                </li>
                                <li class="list-item">
                                    <a href="/signup.php" class="nav__link">Sign Up</a>
                                </li>
                            <?php endif; ?>
                            <?php if ($showLogout): ?>
                                <li class="list-item">
                                    <a href="?logout" class="nav__link">Iziet</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </section>
            </div>
        </header>        
    </div>