<?php 
    $session = session();
    $error_validasi = $session->getFlashdata('error_validasi');
    $error = $session->getFlashdata('error');
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
    <!-- Google reCaptcha -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <!-- internal -->
	<link rel="stylesheet" href="<?= base_url('public/css/register.css') ?>" />

    <title>Registration</title>
</head>
<body>
    <div class="container-fluid bg-blue">
        <div class="row justify-content-center">
            <!-- <div class="col col-sm-12 col-md-12 col-lg-4 col-xl-4 bg-blue">
                <div class="row justify-content-center">
                    <div class="col col-sm-8 col-md-8 col-lg-8 col-xl-8 pt-5 text-center">
                        <img src="<?= base_url('public/assets/security.gif') ?>" alt="Multindo Auto Finance" class="anime">
                    </div>
                </div>
            </div> -->
            <div class="col col-sm-12 col-md-12 col-lg-6 col-xl-6">
                <div class="card mt-5 mb-5">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col col-sm-8 col-md-8 col-lg-10 col-xl-10 pt-2 pb-4">
                                <form method="POST" action="<?= base_url('auth/doRegist') ?>">
                                    <div class="mb-3 text-center">
                                        <img src="<?= base_url('public/assets/MAF-logo-png.png') ?>" alt="Multindo Auto Finance" class="logo">
                                    </div>
                                    <div class="mb-3 text-center">
                                        <label for="" class="form-label judul">Daftar Akun Anda</label>
                                    </div>

                                    <?php if(isset($error_validasi)){ ?>
                                        <div class="alert alert-warning" role="alert">
                                            Terjadi Kesalahan :<br>
                                            <ul>
                                                <?php foreach($error_validasi as $e){ ?>
                                                <li><?php echo $e ?></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    <?php } ?>

                                    <?php if(isset($error)){ ?>
                                        <div class="alert alert-warning" role="alert">
                                            <?php echo $error; ?>
                                        </div>
                                    <?php } ?>

                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" name="fullName" id="nama"  placeholder="Nama Lengkap Anda" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" id="email" placeholder="example@company.com" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="perusahaan" class="form-label">Perusahaan</label>
                                        <input type="text" class="form-control" name="company" id="perusahaan" placeholder="Nama Perusahaan Anda" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="alamat" class="form-label">Alamat</label>
                                        <input type="text" class="form-control" name="address" id="alamat" placeholder="Alamat Lengkap" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Nomor Telephone</label>
                                        <input type="text" class="form-control" name="phone" id="phone" placeholder="Nomor Telephone Aktif" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="pass" class="form-label">Kata Sandi</label>
                                        <input type="password" class="form-control" name="pass" id="pass" placeholder="Password" required>
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="cek" required>
                                        <label class="form-check-label" for="cek">Saya menyetujui <b><a class="hiden-link" href="https://multindo.co.id/kebijakan-privasi-user.html">Syarat dan Ketentuan</a></b> juga <b><a class="hiden-link" href="https://multindo.co.id/kebijakan-privasi-user.html">Kebijakan Privasi</a></b></label>
                                    </div>
                                    <div class="mb-3 justify-content-center">
                                        <div class="g-recaptcha" data-sitekey="6LeZAv0lAAAAADVQZtk9RR7c1BYWnKxDFqWm-PxW"></div>
                                    </div>
                                    <br/>
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-primary" type="submit">Buat Akun Baru</button>
                                    </div>
                                    <div class="mt-3 mb-5 form-check text-center">
                                        <label class="form-check-label" for="">Sudah punya akun ? <b><a href="<?= base_url('auth/login') ?>">Masuk disini</a></b></label>
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