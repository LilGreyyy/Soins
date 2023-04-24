<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/css/profile.css">
    <link href='https://fonts.googleapis.com/css?family=Merriweather' rel='stylesheet'>
</head>
<body>
<nav id="navbar">
    <div class="pace">
        <div class="pace-progress"></div>
    </div>
    <div class="navbar-inner">
        <div class="navbar-left">
            <div id="nav-icon3">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <div class="navbar-right">
            <img id="nb-u-ico" class="navbar-user-icon" src="https://i.imgur.com/WerKNvj.jpg" alt="">
        </div>
    </div>
</nav>
<ul id="usermenu">
    <div class="arrow"></div>
    <li class="usermenu-link-wrap"><a class="usermenu-link">
            <div class="usermenu-link-left"><i class="uil uil-user"></i></div>
            <div class="usermenu-link-right">Profile</div></a>
    </li>
    <li class="usermenu-link-wrap"><a class="usermenu-link">
            <div class="usermenu-link-left"><i class="uil uil-pen"></i></div>
            <div class="usermenu-link-right">Edit Profile</div></a>
    </li>
    <li class="usermenu-link-wrap"><a class="usermenu-link">
            <div class="usermenu-link-left"><i class="uil uil-video"></i></div>
            <div class="usermenu-link-right">My Studio</div></a>
    </li>
    <li class="usermenu-link-wrap"><a class="usermenu-link">
            <div class="usermenu-link-left"><i class="uil uil-setting"></i></div>
            <div class="usermenu-link-right">Settings</div></a>
    </li>
    <li class="usermenu-link-gold-wrap outline"><a class="usermenu-link-gold">
            <div class="usermenu-link-left"><i class="far fa-gem"></i></div>
            <div class="usermenu-link-right">Get Premium</div></a>
    </li>
</ul>
<div id="menu" class="menu">
    <div class="menu-inner">
        <div class="menu-sec">
            <ul class="menu-links">
                <li class="menu-link"><a class="menu-link-icon"><i class="uil uil-home-alt"></i></a>Home</li>
                <li class="menu-link"><a class="menu-link-icon"><i class="uil uil-video"></i></a>Videos</li>
                <li class="menu-link"><a class="menu-link-icon"><i class="uil uil-folder"></i></a>Categories</li>
            </ul>
        </div>
        <div class="menu-sec">
            <ul class="menu-links">
                <li class="menu-link"><a class="menu-link-icon"><i class="uil uil-folder-open"></i></a>Library</li>
                <li class="menu-link"><a class="menu-link-icon"><i class="uil uil-history"></i></a>History</li>
                <li class="menu-link"><a class="menu-link-icon"><i class="uil uil-thumbs-up"></i></a>Liked videos</li>
                <li class="menu-link"><a class="menu-link-icon"><i class="uil uil-heart"></i></a>Favorite videos</li>
                <li class="hidden-links"><a class="menu-link-icon"><i class="uil uil-cloud-heart"></i></a>Hidden 1</li>
                <li class="hidden-links"><a class="menu-link-icon"><i class="uil uil-cloud-bookmark"></i></a>Hidden 2</li>
                <li id="showmore1" class="menu-link" onclick="showmore()"><a class="menu-link-icon"><i class="uil uil-angle-down"></i></a>Show more</li>
            </ul>
        </div>
        <div class="menu-sec">
            <ul class="menu-links">
                <li class="menu-link"><a class="menu-link-icon"><i class="uil uil-users-alt"></i></a>Friends</li>
                <li class="menu-link"><a class="menu-link-icon"><i class="uil uil-star"></i></a>Subscriptions</li>
                <li class="menu-link"><a class="menu-link-icon"><i class="uil uil-chat"></i></a>Chat room</li>
                <li class="hidden-links2"><a class="menu-link-icon"><i class="uil uil-lightbulb-alt"></i></a>Hidden 1</li>
                <li class="hidden-links2"><a class="menu-link-icon"><i class="uil uil-brightness"></i></a>Hidden 2</li>
                <li id="showmore2" class="menu-link" onclick="showmore2()"><a class="menu-link-icon"><i class="fas fa-angle-down"></i></a>Show more</li>
            </ul>
        </div>
        <div class="menu-sec">
            <ul class="menu-links">
                <li class="menu-link" style="color:#626262;"><a class="menu-link-icon"><i class="uil uil-setting"></i></a>Settings</li>
                <li class="menu-link" style="color:#626262;"><a class="menu-link-icon"><i class="uil uil-question-circle"></i></a>Need help?</li>
                <li class="menu-link" style="color:#626262;"><a class="menu-link-icon"><i class="uil uil-comment-alt-exclamation"></i></a>Feedback</li>
            </ul>
        </div>
        <div class="menu-sec-bottom">
            <a class="menu-text-small">About</a>
            <a class="menu-text-small">Copyright</a>
            <a class="menu-text-small">Contact Us</a>
            <a class="menu-text-small">Creators</a>
            <a class="menu-text-small">Terms</a>
            <a class="menu-text-small">Privacy</a>
        </div>
        <p class="menu-text">&copy 2019 Areal Alien</p>
    </div>
</div>
<main>
    <div class="user-header-wrapper">
        <div class="user-icon-wrapper">
            <img class="user-icon" src="https://i.imgur.com/WerKNvj.jpg" alt="">
        </div>
        <div class="user-header-inner">
            <div class="user-header-overlay"></div>
            <img class="user-header" src="https://i.imgur.com/FDiUOQq.jpg" alt="">
        </div>
    </div>
    <div class="user-info-bar">
        <div class="ufo-bar-col1">

        </div>
        <div class="ufo-bar-col2">
            <div class="ufo-bar-col2-inner">
                <div class="username-wrapper">
                    <div class="verified"><p>Verified Account</p></div>
                    <h1 class="username">Areal Alien</h1>
                    <svg class="username-verified" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1350.03 1326.16">
                        <defs><style>.cls-11{fill: var(--primary);}.cls-12{fill:#ffffff;}</style></defs><title>verified</title>
                        <g id="Layer_3" data-name="Layer 3">
                        <polygon class="cls-11" points="0 747.37 120.83 569.85 70.11 355.04 283.43 292.38 307.3 107.41 554.93 107.41 693.66 0 862.23 120.83 1072.57 126.8 1112.84 319.23 1293.35 399.79 1256.05 614.6 1350.03 793.61 1197.87 941.29 1202.35 1147.15 969.64 1178.48 868.2 1326.16 675.02 1235.17 493.77 1315.72 354.99 1133.73 165.58 1123.29 152.16 878.64 0 747.37"/></g>
                        <g id="Layer_2" data-name="Layer 2">
                        <path class="cls-12" d="M755.33,979.23s125.85,78.43,165.06,114c34.93-36,234.37-277.22,308.24-331.94,54.71,21.89,85,73.4,93,80.25-3.64,21.89-321.91,418.58-368.42,445.94-32.74-3.84-259-195.16-275.4-217C689.67,1049.45,725.24,1003.85,755.33,979.23Z" transform="translate(-322.83 -335.95)"/></g>
                    </svg>
                </div>
                <div>
                    <a class="ufo-bar-fff" href="followers.php"><span>10.5K</span> Followers</a>
                    <a class="ufo-bar-fff" href="following.php">Following <span>512</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="ufo-bar-col3">
            <div class="ufo-bar-col3-inner">

            </div>
        </div>
        <div class="ufo-bar-col4">
            <div class="ufo-bar-col4-inner">
                <button class="button2 btn-primary2"><i class="uil uil-plus"></i> Subscribe<div class="btn-secondary2"></div></button>
            </div>
        </div>
        <div class="ufo-bar-col5">

        </div>
    </div>
    <div class="user-info-bar2">
        <div class="ufo-bar2-col1">

        </div>
        <div id="ufo-home" class="ufo-bar2-col2 ufo-bar2-block-active">
            <div class="ufo-bar2-col2-inner flexbox">
                <h3>Home</h3>
            </div>
        </div>
        <div id="ufo-videos" class="ufo-bar2-col3 ufo-bar2-block">
            <div class="ufo-bar2-col3-inner flexbox">
                <h3>Videos</h3>
            </div>
        </div>
        <div id="ufo-images" class="ufo-bar2-col4 ufo-bar2-block">
            <div class="ufo-bar2-col4-inner flexbox">
                <h3>Images</h3>
            </div>
        </div>
        <div id="ufo-playlists" class="ufo-bar2-col5 ufo-bar2-block">
            <div class="ufo-bar2-col5-inner flexbox">
                <h3>Playlists</h3>
            </div>
        </div>
        <div id="ufo-about" class="ufo-bar2-col6 ufo-bar2-block">
            <div class="ufo-bar2-col6-inner flexbox">
                <h3>About</h3>
            </div>
        </div>
        <div class="ufo-bar2-col7">

        </div>
    </div>
    <div class="user-info-block">

    </div>
</main>
<footer id="footer">
    <div class="footer-inner">
        <div class="footer-left">
            <a class="footer-mail">Find us everywhere.</a>
        </div>
        <div class="footer-right">
            <div class="footer-links">
                <a class="footer-link fl-first" href="https://www.youtube.com/ArealAlien" target="_blank">YouTube.</a>
                <a class="footer-link" href="https://twitter.com/Areal_Alien" target="_blank">Twitter.</a>
                <a class="footer-link" href="https://www.instagram.com/areal_alien/" target="_blank">Instagram.</a>
                <a class="footer-link fl-last" href="" target="_blank">About Us.</a>
            </div>
            <div class="footer-text">
                <p>Website &copy 2020 - 2020</p>
                <p>Made with ♥ by Areal Alien</p>
            </div>
            <div class="footer-sites">
                <a>Cookie Policy.</a> | <a href="https://www.unsplash.com" target="_blank">Unsplash.</a>
            </div>
        </div>
    </div>
</footer>
</body>

<script src="/js/profile.js"></script>