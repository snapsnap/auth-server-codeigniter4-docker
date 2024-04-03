<?php 
    $session = session();
    $error_validasi = $session->getFlashdata('error_validasi');
    $error = $session->getFlashdata('error');
    // var_dump(isset($error));
    // die();
?>

    <?= $this->extend('layout/dashboard_layout') ?>
    <?= $this->section('content') ?>
        <!-- Content disni -->
        <div class="row justify-content-md-center">
            <div class="col col-lg-6 col-xl-6 boks py-3">

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
                
                <div class="mb-2">
                    <h4><b>Buat Aplikasi Baru</b></h4>
                </div>
                <hr>
                <div class="mb-2">
                    <form method="POST" action="<?= base_url('partner/partner/doCreateApp') ?>">
                        <div class="mb-3">
                            <label for="appName" class="form-label">Nama Aplikasi</label>
                            <div class="group">
                                <input type="text" class="form-control" name="appName" id="appName" placeholder="Jangan Menggunakan kata “test” dan “prod” di nama aplikasi Anda" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="pubKey" class="form-label">Public Key Anda</label>
                            <div class="group">
                                <textarea rows="7" class="form-control" name="pubKey" id="pubKey" 
                                    placeholder="-----BEGIN PUBLIC KEY-----
MFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBAKiWrTT1ptqixvM8X6EBUeeLyWJeUzMr
dtFSj/H6aleHA6+WLnrK8zlZ8madJEIM9WE0jyhOPWNCxCO5PB2WPxcCAwEAAQ==
-----END PUBLIC KEY-----"
                                 required></textarea>
                            </div>
                        </div>
                        <div class="">
                            <a class="btn btn-outline-secondary teks-btn" type="button" href="<?= base_url('partner/partner/index') ?>">Batal</a>
                            <button class="btn btn-primary teks-btn" type="submit">Ajukan aplikasi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- End content -->
    <?= $this->endSection() ?>