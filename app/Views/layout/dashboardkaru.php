<?php

$session = \Config\Services::session();
?>
<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>


<div class="card-header">

    <h3 class="card-title">Hallo <b><?= $session->nama; ?></b>, Selamat datang</h3>
</div>
<div class="col-4">
    <div class="card">
        <div class="card-header">
            <h4>Daftar Perawat</h4>
        </div>
        <div class="card-body" style="max-height: 400px; overflow-y: auto;">
            <table class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>PK</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($users as $user): ?>
                        <?php if ($user->level_user != 1): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td>
                                    <img src="<?= base_url('assets/dist/img/user/' . $user->photo); ?>" alt="Foto" width="40"
                                        height="40">
                                </td>
                                <td><?= esc($user->nama); ?></td>
                                <td><?= esc($user->nama_level); ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>
</div>




<?php $this->endSection(); ?>