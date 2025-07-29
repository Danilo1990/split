<?php session_start(); ?>

<?php require_once 'functions.php'; ?>
<?php injectScripts(); ?>
<?php head('Notifiche'); ?>
<body>
    <?php if (isset($_SESSION['user_email'])) { navBar(); } ?>
    <div class="container">
        <?php mostraNovita() ?>
    </div>
<?php footer() ?>