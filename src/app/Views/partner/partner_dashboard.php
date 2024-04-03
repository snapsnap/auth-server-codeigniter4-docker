<?php 
    $session = session();
?>

    <?= $this->extend('layout/dashboard_layout') ?>
    <?= $this->section('content') ?>
    <?php 
        // $data = $data[0];
        // var_dump($data);
    ?>
        <!-- Content disni -->
        <div class="row">
            <div class="col boks py-3">
                <div class="mb-2">
                    <label for="" class="form-label judul">Snap Key</label>
                </div>
                <div>
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#"><i class="bi bi-key-fill"></i>&nbsp;Manage Snap Key</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('partner/partner/partner_profile') ?>"><i class="bi bi-person-fill"></i>&nbsp;Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('partner/partner/partner_password') ?>"><i class="bi bi-lock-fill"></i>&nbsp;Kata Sandi</a>
                        </li>
                    </ul>
                </div>
                <hr>
                <div class="mb-2">
                    <a href="<?= base_url('partner/partner/partner_createApp') ?>" class="btn btn-gelap" type="button"><i class="bi bi-plus-circle"></i>&nbsp;&nbsp;&nbsp;Buat Aplikasi Baru</a>
                </div>
                <div>
                    <div class="table-responsive">
                        <table class="table table-bordered teks-table table-partner">
                            <thead>
                                <tr class="text-center">
                                    <th>No.</th>
                                    <th>Create Date</th>
                                    <th>App Name</th>
                                    <th>Partner Public Key</th>
                                    <th>Partner Credentials From MAF</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if(!isset($data)){
                                ?>
                                <tr>
                                    <td colspan="7" class="text-center">-- Belum ada data --</td>
                                </tr>
                                <?php
                                }else{
                                ?>
                                <?php
                                    $no = 1;
                                    foreach($data as $val){

                                        if($val['appStatus'] == 0){
                                            $badge = "text-bg-warning";
                                        }elseif($val['appStatus'] == 1){
                                            $badge = "text-bg-success";
                                        }elseif($val['appStatus'] == 2){
                                            $badge = "text-bg-danger";
                                        }else{
                                            $badge = "text-bg-warning";
                                        }
                                ?>
                                <tr>
                                    <td><?= $no; ?>.</td>
                                    <td class="text-center"><?= $val['createDate']; ?></td>
                                    <td><?= $val['appName']; ?></td>
                                    <td class="text-center">
                                        <a class="ckey" data-key="<?= $val['clientPubKey']; ?>" data-appid="<?= $val['appId']; ?>"><?= $val['clientPubKey']; ?></a>
                                    </td>
                                    <td class="teks-key">
                                        <b>Client ID :</b> <?= isset($val['clientId']) ? $val['clientId'] : "-"; ?> <br>
                                        <b>Client Secret :</b> <?= isset($val['clientSecret']) ? $val['clientSecret'] : "-"; ?> <br>
                                        <b>MAF Public Key :</b> <a href="<?= base_url('partner/partner/downloadKey?keyName='.$val['mafPubKey']) ?>"><?= $val['mafPubKey']; ?></a> <br>
                                        <!-- <button class="btn btn-sm btn-success teks-btn" type="button">Edit Your Key<i class="fa-solid fa-square-down"></i></button> -->
                                    </td>
                                    <td class="text-center">
                                        <span class="badge <?= $badge; ?> mb-2"><?= $val['specialStatus']; ?></span>
                                        <!-- <button class="btn btn-download teks-btn" type="button">Download <i class="bi bi-box-arrow-in-down"></i></button> -->
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-danger teks-btn" type="button" id="delApp" data-appid="<?= $val['appId']; ?>"><i class="bi bi-trash-fill"></i> Hapus</button>
                                    </td>
                                </tr>
                                <?php
                                    $no++;
                                    } 
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="ModalKey" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"><b>Key Content</b></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="pubKey" class="form-label">Partner Public Key</label>
                            <div class="group">
                                <input type="hidden" id="hkeyName" value="">
                                <textarea rows="7" class="form-control" id="pubKey"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="upKey">Update Key Anda</button>
                </div>
                </div>
            </div>
        </div>
        <!-- End content -->

        <!-- script -->
        <script type="text/javascript" src="<?= base_url('public/js/internal/partner_dashboard.js') ?>"></script>

    <?= $this->endSection() ?>