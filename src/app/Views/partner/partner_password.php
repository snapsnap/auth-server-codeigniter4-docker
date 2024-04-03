<?php 
    $session = session();
?>
    <?= $this->extend('layout/dashboard_layout') ?>
    <?= $this->section('content') ?>
    <?php 
        $data = $data[0];
    ?>
        <!-- Content disni -->
        <div class="row justify-content-md-center">
            <div class="col col-lg-6 col-xl-6 boks py-3">
                <div class="mb-2">
                    <label for="" class="form-label judul">Snap Key</label>
                </div>
                <div>
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('partner/partner/index') ?>"><i class="bi bi-key-fill"></i>&nbsp;Manage Snap Key</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('partner/partner/partner_profile') ?>"><i class="bi bi-person-fill"></i>&nbsp;Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="#"><i class="bi bi-lock-fill"></i>&nbsp;Kata Sandi</a>
                        </li>
                    </ul>
                </div>
                <hr>
                <div class="mb-2">
                    <form>
                        <div class="mb-3">
                            <label for="sandi" class="form-label">Kata Sandi :</label>
                            <span class="span-result">**************</span>
                            <!-- <div class="group">
                                <input disabled type="password" class="form-control" id="sandi" value="123">
                            </div> -->
                        </div>
                        <div class="">
                            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-pencil-square"></i>&nbsp;&nbsp;Ganti Kata Sandi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"><b>Ganti Kata Sandi</b></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formPass">
                        <div class="mb-3">
                            <label for="sandiLama" class="form-label">Kata Sandi Lama</label>
                            <div class="group">
                                <input type="text" class="form-control" id="sandiLama">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="sandiBaru" class="form-label">Kata Sandi Baru</label>
                            <div class="group">
                                <input type="text" class="form-control" id="sandiBaru">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="sandiKonfirm" class="form-label">Konfirmasi Kata Sandi Baru</label>
                            <div class="group">
                                <input type="text" class="form-control" id="sandiKonfirm">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="savePass">Simpan Perubahan</button>
                </div>
                </div>
            </div>
        </div>

        <!-- script -->
        <script type="text/javascript" src="<?= base_url('public/js/internal/partner_password.js') ?>"></script>

        <!-- End content -->
    <?= $this->endSection() ?>