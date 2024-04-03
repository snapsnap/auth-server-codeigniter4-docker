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
                            <a class="nav-link active" href="#"><i class="bi bi-person-fill"></i>&nbsp;Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('partner/partner/partner_password') ?>"><i class="bi bi-lock-fill"></i>&nbsp;Kata Sandi</a>
                        </li>
                    </ul>
                </div>
                <hr>
                <div class="mb-2">
                    <form>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap :</label>
                            <span class="span-result"><?= $data['partnerName']; ?></span>
                            <!-- <input disabled type="email" class="form-control" id="nama" aria-describedby="emailHelp" placeholder="Nama Lengkap Anda"> -->
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email :</label>
                            <span class="span-result"><?= $data['email']; ?></span>
                            <!-- <input disabled type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="example@company.com"> -->
                        </div>
                        <div class="mb-3">
                            <label for="perusahaan" class="form-label">Perusahaan :</label>
                            <span class="span-result"><?= $data['partnerCompany']; ?></span>
                            <!-- <input disabled type="password" class="form-control" id="perusahaan" placeholder="Nama Perusahaan Anda"> -->
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Nomor Telephone :</label>
                            <span class="span-result"><?= $data['partnerPhone']; ?></span>
                            <!-- <input disabled type="email" class="form-control" id="phone" aria-describedby="emailHelp" placeholder="Nomor Telephone Aktif"> -->
                        </div>
                        <div class="mb-5">
                            <label for="alamat" class="form-label">Alamat :</label>
                            <span class="span-result"><?= $data['partnerAddress']; ?></span>
                            <!-- <input disabled type="password" class="form-control" id="alamat" placeholder="Alamat Lengkap"> -->
                        </div>
                        <div class="mb-5">
                            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-pencil-square"></i>&nbsp;&nbsp;Perbarui Profile</button>
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel"><b>Manajemen Profile</b></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formProfile">
                        <input type="hidden" id="hPartnerId" value="<?= $data['partnerId']; ?>">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="email" class="form-control" id="nama" aria-describedby="emailHelp" placeholder="Nama Lengkap Anda" value="<?= $data['partnerName']; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input disabled type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="example@company.com" value="<?= $data['email']; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="perusahaan" class="form-label">Perusahaan</label>
                            <input type="text" class="form-control" id="perusahaan" placeholder="Nama Perusahaan Anda" value="<?= $data['partnerCompany']; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Nomor Telephone</label>
                            <input type="email" class="form-control" id="phone" aria-describedby="emailHelp" placeholder="Nomor Telephone Aktif" value="<?= $data['partnerPhone']; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="alamat" placeholder="Alamat Lengkap" value="<?= $data['partnerAddress']; ?>">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="saveProfile">Simpan Perubahan</button>
                </div>
                </div>
            </div>
        </div>

        <!-- script -->
        <script type="text/javascript" src="<?= base_url('public/js/internal/partner_profile.js') ?>"></script>

        <!-- End content -->
    <?= $this->endSection() ?>