<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $this->e($title); ?></title>
    <meta name="description" content="Chartist.html">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
    <link id="vendorsbundle" rel="stylesheet" media="screen, print" href="/css/vendors.bundle.css">
    <link id="appbundle" rel="stylesheet" media="screen, print" href="/css/app.bundle.css">
    <link id="myskin" rel="stylesheet" media="screen, print" href="/css/skins/skin-master.css">
    <link rel="stylesheet" media="screen, print" href="/css/fa-solid.css">
    <link rel="stylesheet" media="screen, print" href="/css/fa-brands.css">
    <link rel="stylesheet" media="screen, print" href="/css/fa-regular.css">
    <script src="https://kit.fontawesome.com/53770adb9e.js" crossorigin="anonymous"></script>

    <?php if ($this->e($auth)): ?>
        <!-- Call App Mode on ios devices -->
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <!-- Remove Tap Highlight on Windows Phone IE -->
        <meta name="msapplication-tap-highlight" content="no">
        <!-- base css -->
        <link id="vendorsbundle" rel="stylesheet" media="screen, print" href="/css/vendors.bundle.css">
        <link id="appbundle" rel="stylesheet" media="screen, print" href="/css/app.bundle.css">
        <link id="mytheme" rel="stylesheet" media="screen, print" href="#">
        <link id="myskin" rel="stylesheet" media="screen, print" href="/css/skins/skin-master.css">
        <!-- Place favicon.ico in the root directory -->
        <link rel="apple-touch-icon" sizes="180x180" href="/img/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon/favicon-32x32.png">
        <link rel="mask-icon" href="/img/favicon/safari-pinned-tab.svg" color="#5bbad5">
        <link rel="stylesheet" media="screen, print" href="/css/page-login-alt.css">
    <?php endif; ?>
</head>
<body class="mod-bg-1 mod-nav-link">

<?php if (!($this->e($auth) || $this->e($reg))): ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary bg-primary-gradient">
        <a class="navbar-brand d-flex align-items-center fw-500" href="/"><img alt="logo"
                                                                               class="d-inline-block align-top mr-2"
                                                                               src="/img/logo.png"> Учебный проект</a>
        <button aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation"
                class="navbar-toggler"
                data-target="#navbarColor02" data-toggle="collapse" type="button"><span
                    class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarColor02">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/">Главная <span class="sr-only">(current)</span></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <?php if (isset($_SESSION['auth_logged_in'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/logout">Выйти</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Войти</a>
                    </li>

                <?php endif; ?>


            </ul>
        </div>
    </nav>
<?php endif; ?>

<?= $this->section('content') ?>
</html>