<?php 
    $session = session();
    $fullname = $session->get('fullname');
    $rolename = $session->get('rolename');
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
	<link rel="stylesheet" href="<?= base_url('public/bootstrap-5.2.3-dist/css/bootstrap.min.css') ?>" />
    <!-- Jquery dan Bootsrap JS -->
	<script src="<?= base_url('public/js/jquery-3.6.4.min.js') ?>"></script>
	<script src="<?= base_url('public/bootstrap-5.2.3-dist/js/bootstrap.min.js') ?>"></script>    
    <!-- Sweetalert -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('public/sweetalert/sweetalert2.css'); ?>">
    <script type="text/javascript" src="<?php echo base_url(); ?>public/sweetalert/sweetalert.min.js"></script>
    <!-- HoldOn -->
    <link rel="stylesheet" href="<?php echo base_url('public/holdon/HoldOn.min.css'); ?>">
    <script type="text/javascript" src="<?php echo base_url('public/holdon/HoldOn.min.js'); ?>"></script>
    <!-- External -->
    <link href='https://fonts.googleapis.com/css?family=Plus Jakarta Sans' rel='stylesheet'>
    <link rel="shortcut icon" href="<?= base_url('public/assets/fav.svg') ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- internal -->
	<link rel="stylesheet" href="<?= base_url('public/css/dashboard_layout.css') ?>" />

    <title>Dashboard</title>
    
    <script type="text/javascript">
        window.base_url = <?php echo json_encode(base_url()); ?>;
    </script>
</head>
<body>
    <nav class="navbar sticky-top text-center bg-blue">
        <div class="container-fluid pb-2">
            <a class="navbar-brand logo" href="<?= base_url('partner/partner/index') ?>">
                <img src="<?= base_url('public/assets/MAF-logo-putih-png.png') ?>" alt="Multindo Auto Finance" class="logo">
            </a>
            <div class="d-flex pt-1">
                <div class="p-2">
                    <img src="<?= base_url('public/assets/user-profile.png') ?>" alt="" class="foto-profile">
                </div>
                <div class="p-2 identity">
                    <label for="" class="teks-putih blok">Welcome, <?= $fullname; ?></label>
                    <label for="" class="teks-putih sub-blok">role: <?= $rolename; ?></label>
                </div>
                <div class="p-2">
                    <a class="btn btn-danger teks-btn" href="" role="button" id="signOut"><i class="bi bi-power"></i> Sign Out</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="container align-items-center mt-3 py-3">
        <!-- Content disini -->
        <?= $this->renderSection('content') ?>
        <!-- End Content -->
    </div>
    
    <!-- script -->
    <script type="text/javascript" src="<?= base_url('public/js/internal/dashboard_layout.js') ?>"></script>
</body>
</html>