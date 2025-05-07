<?php

$session = \Config\Services::session();
?>
<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>


<div class="card-header">
    <h3 class="card-title">Hallo <b><?= $session->nama; ?></b>, Selamat datang</h3>
</div>

<div class="card-body">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Sub No</th>
                <th>Kompetensi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dataPrapk as $row): ?>
                <tr>
                    <td><?= esc($row['no']); ?></td>
                    <td><?= esc($row['sub_no'] ?? '-'); ?></td>
                    <td><?= esc($row['kompetensi']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<?php $this->endSection(); ?>