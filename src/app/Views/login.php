<?php 
    $session = session();
    $registrasi = $session->getFlashdata('registrasi');
    $error = $session->getFlashdata('error');
    $username = $session->getFlashdata('username');
    $role = $session->getFlashdata('role');
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
    <!-- External -->
    <link href='https://fonts.googleapis.com/css?family=Plus Jakarta Sans' rel='stylesheet'>
    <link rel="shortcut icon" href="<?= base_url('public/assets/fav.svg') ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Google reCaptcha -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <!-- internal -->
	<link rel="stylesheet" href="<?= base_url('public/css/login.css') ?>" />

    <title>Login</title>
</head>
<body>
    <div class="container-fluid bg-blue">
        <div class="row justify-content-center">
            <!-- <div class="col col-sm-12 col-md-12 col-lg-4 col-xl-4">
                <div class="row justify-content-center">
                    <div class="col col-sm-8 col-md-8 col-lg-8 col-xl-8 pt-5 text-center">
                        <img src="<?= base_url('public/assets/data-security.gif') ?>" alt="Multindo Auto Finance" class="anime">
                    </div>
                </div>
            </div> -->
            <div class="col col-sm-12 col-md-12 col-lg-6 col-xl-6">
                <div class="card mt-5 mb-5">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col col-sm-8 col-md-8 col-lg-10 col-xl-10 pt-2 pb-4">
                                <form method="POST" action="<?= base_url('auth/doLogin') ?>">
                                    <div class="mb-3 text-center">
                                        <img src="<?= base_url('public/assets/MAF-logo-png.png') ?>" alt="Multindo Auto Finance" class="logo">
                                    </div>
                                    <div class="mb-3 text-center">
                                        <label class="form-label judul">Masuk Akun Anda</label>
                                    </div>

                                    <?php if($error){ ?>
                                        <div class="alert alert-warning" role="alert">
                                            <?php echo $error?>
                                        </div>
                                    <?php } ?>

                                    <?php if($registrasi){ ?>
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <?php echo $registrasi?>
                                        </div>
                                    <?php } ?>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" id="email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="pass" class="form-label">Kata Sandi</label>
                                        <input type="password" class="form-control" name="pass" id="pass" required>
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="cekboks" required>
                                        <label class="form-check-label" for="cekboks">Saya menyetujui <b><a class="hiden-link" href="https://multindo.co.id/kebijakan-privasi-user.html">Syarat dan Ketentuan</a></b> juga <b><a class="hiden-link" href="https://multindo.co.id/kebijakan-privasi-user.html">Kebijakan Privasi</a></b></label>
                                    </div>
                                    <div class="mb-3 justify-content-center">
                                        <div class="g-recaptcha" data-sitekey="6LeZAv0lAAAAADVQZtk9RR7c1BYWnKxDFqWm-PxW"></div>
                                    </div>
                                    <br/>
                                    <div class="d-grid">
                                        <button class="btn btn-primary" type="submit">Masuk</button>
                                    </div>
                                    <div class="mt-3 mb-5 form-check text-center">
                                        <label class="form-check-label" >Belum punya akun ? <b><a href="<?= base_url('auth/register') ?>">Daftar disini</a></b></label>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>