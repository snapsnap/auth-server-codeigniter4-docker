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
                    <label for="" class="form-label judul">Manage Snap Key</label>
                </div>
                <hr>
                <div>
                    <div class="table-responsive">
                        <table class="table table-bordered teks-table table-admin">
                            <thead>
                                <tr class="text-center">
                                    <th>No.</th>
                                    <th>Create Date</th>
                                    <th>App Name</th>
                                    <th>Partner Public Key</th>
                                    <th>Status</th>
                                    <th>Process Action</th>
                                    <th>Client ID</th>
                                    <th>Client Secret</th>
                                    <th>MAF Public Key</th>
                                    <th>MAF Private Key</th>
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
                                        <a href="<?= base_url('partner/partner/downloadKey?keyName='.$val['clientPubKey']) ?>"><?= $val['clientPubKey']; ?></a>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge <?= $badge; ?> mb-2"><?= $val['specialStatus']; ?></span>
                                    </td>
                                    <td>
                                    <?php
                                        if($val['appStatus'] == 0 || $val['appStatus'] == ''){
                                    ?>
                                        <button class="btn-setuju" type="button" data-val="1" data-appid="<?= $val['appId']; ?>"><i class="bi bi-check"></i>Setujui</button>
                                        <button class="btn-tolak" type="button" data-val="2" data-appid="<?= $val['appId']; ?>"><i class="bi bi-x"></i>Tolak</button>
                                    <?php
                                        }else if($val['appStatus'] == 1){
                                    ?>
                                        <button class="btn btn-outline-success btn-sm teks-btn btnGenerate" id="btnGenerate" type="button" data-appid="<?= $val['appId']; ?>"><i class="bi bi-pencil-square"></i>&nbsp;&nbsp;Generate Credentials</button>
                                    <?php
                                        }else{
                                    ?>
                                        <!-- <button class="btn " type="button" data-appid="<?= $val['appId']; ?>"><i class="bi bi-x"></i>Generate Credentials</button> -->
                                    <?php } ?>
                                    </td>
                                    <td class="text-center">
                                        <?= $val['clientId']; ?>
                                    </td>
                                    <td class="text-center">
                                        <?= $val['clientSecret']; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('partner/partner/downloadKey?keyName='.$val['mafPubKey']) ?>"><?= $val['mafPubKey']; ?></a>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('partner/partner/downloadKey?keyName='.$val['mafPrivKey']) ?>"><?= $val['mafPrivKey']; ?></a>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-del teks-btn delApp" type="button" id="delApp" data-appid="<?= $val['appId']; ?>"><i class="bi bi-trash-fill"></i> Hapus</button>
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
        <div class="modal fade" id="ModalGen" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"><b>Generate Options</b></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                        <div class="mb-3">
                            <span>Lakukan generate credentials untuk partner yang sudah mengajukan aplikasi & sudah anda setujui :</span>
                        </div>
                        <hr>
                        <input type="hidden" id="hAppId" >
                        <div class="mb-3">
                            <button class="btn btn-outline-primary btnClientId" id="btnClientId"><i class="bi bi-fingerprint"></i>&nbsp;Generate Client ID</button>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-outline-primary btnClientSecret" id="btnClientSecret"><i class="bi bi-lock-fill"></i>&nbsp;Generate Client Secret</button>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-outline-primary btnGenMafKey" id="btnGenMafKey"><i class="bi bi-key-fill"></i>&nbsp;Generate MAF RSA Key Combination</button>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
                </div>
            </div>
        </div>
        <!-- End content -->

        <!-- script -->
        <script type="text/javascript" src="<?= base_url('public/js/internal/admin_dashboard.js') ?>"></script>

    <?= $this->endSection() ?>