<?php
$session = \Config\Services::session();
?>
<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>
<div class="card-header">
            <h5 class="ml-2"></h5>
                                <h3 class="card-title"> Hallo <b><?= $session->nama; ?></b>, Selamat datang </h3>
                            </div>
                            <div class="card-body">
                                <h2 class="text-center mb-3" style="font-weight:bold">INFORMASI PEGADAAN</h2>
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-3">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3><?= $jumlah_pengadaan; ?></h3>
                    <p>Jumlah Pengadaan</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="<?= base_url('pengadaan') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        
        <div class="col-3">
            <!-- small box -->
            <div class="small-box" style="background-color:#DBB300FF; color:white">
                <div class="inner">
                <h3>Rp <?= number_format($total_perencanaan, 0, ',', '.'); ?></h3>
                    <p>Total Perencanaan</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="<?= base_url('pengadaan') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        
        <div class="col-3">
            <div class="small-box bg-danger">
                <div class="inner">
                <h3>Rp <?= number_format($total_pelaksanaan, 0, ',', '.'); ?></h3>
                <p>Total Pelaksanaan</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="<?= base_url('pengadaan') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-3">
            <div class="small-box bg-success">
                <div class="inner">
                <h3>Rp <?= number_format($total_pembayaran, 0, ',', '.'); ?></h3>
                <p>Total Pembayaran</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="<?= base_url('pengadaan') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-3">
        <div class="small-box bg-info">
                <div class="inner">
                    <h3><?= $jumlah_pengadaan_belanja_rutin; ?></h3>
                    <p>Jumlah Pengadaan Belanja Rutin</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="<?= base_url('pengadaan') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        
        <div class="col-3">
            <!-- small box -->
            <div class="small-box" style="background-color:#DBB300FF; color:white">
                <div class="inner">
                <h3>Rp <?= number_format($perencanaan_belanja_rutin, 0, ',', '.'); ?></h3>
                    <p>Perencanaan Belanja Rutin</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="<?= base_url('pengadaan') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        
        <div class="col-3">
            <div class="small-box bg-danger">
                <div class="inner">
                <h3>Rp <?= number_format($pelaksanaan_belanja_rutin, 0, ',', '.'); ?></h3>
                <p>Pelaksanaan Belanja Rutin</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="<?= base_url('pengadaan') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-3">
            <div class="small-box bg-success">
                <div class="inner">
                <h3>Rp <?= number_format($pembayaran_belanja_rutin, 0, ',', '.'); ?></h3>
                <p>Pembayaran Belanja Rutin</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="<?= base_url('pengadaan') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-3">
        <div class="small-box bg-info">
                <div class="inner">
                    <h3><?= $jumlah_pengadaan_belanja_modal; ?></h3>
                    <p>Jumlah Pengadaan Belanja Modal</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="<?= base_url('pengadaan') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        
        <div class="col-3">
            <!-- small box -->
            <div class="small-box" style="background-color:#DBB300FF; color:white">
                <div class="inner">
                <h3>Rp <?= number_format($perencanaan_belanja_modal, 0, ',', '.'); ?></h3>
                    <p>Perencanaan Belanja Modal</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="<?= base_url('pengadaan') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        
        <div class="col-3">
            <div class="small-box bg-danger">
                <div class="inner">
                <h3>Rp <?= number_format($pelaksanaan_belanja_modal, 0, ',', '.'); ?></h3>
                <p>Pelaksanaan Belanja Modal</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="<?= base_url('pengadaan') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-3">
            <div class="small-box bg-success">
                <div class="inner">
                <h3>Rp <?= number_format($pembayaran_belanja_modal, 0, ',', '.'); ?></h3>
                <p>Pembayaran Belanja Modal</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="<?= base_url('pengadaan') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="card-body">
                                <h2 class="text-center mb-3" style="font-weight:bold">INFORMASI PENGADAAN</h2>
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-3">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3><?= $jumlah_pengadaan; ?></h3>
                    <p>Jumlah Pengadaan</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="<?= base_url('pengadaan') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        
        <div class="col-3">
            <!-- small box -->
            <div class="small-box" style="background-color:#DBB300FF; color:white">
                <div class="inner">
                <h3>Rp <?= number_format($total_perencanaan, 0, ',', '.'); ?></h3>
                    <p>Total Perencanaan</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="<?= base_url('pengadaan') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        
        <div class="col-3">
            <div class="small-box bg-danger">
                <div class="inner">
                <h3>Rp <?= number_format($total_pelaksanaan, 0, ',', '.'); ?></h3>
                <p>Total Pelaksanaan</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="<?= base_url('pengadaan') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-3">
            <div class="small-box bg-success">
                <div class="inner">
                <h3>Rp <?= number_format($total_pembayaran, 0, ',', '.'); ?></h3>
                <p>Total Pembayaran</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="<?= base_url('pengadaan') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-3">
        <div class="small-box bg-info">
                <div class="inner">
                    <h3><?= $jumlah_pengadaan_belanja_rutin; ?></h3>
                    <p>Jumlah Pengadaan Belanja Rutin</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="<?= base_url('pengadaan') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        
        <div class="col-3">
            <!-- small box -->
            <div class="small-box" style="background-color:#DBB300FF; color:white">
                <div class="inner">
                <h3>Rp <?= number_format($perencanaan_belanja_rutin, 0, ',', '.'); ?></h3>
                    <p>Perencanaan Belanja Rutin</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="<?= base_url('pengadaan') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        
        <div class="col-3">
            <div class="small-box bg-danger">
                <div class="inner">
                <h3>Rp <?= number_format($pelaksanaan_belanja_rutin, 0, ',', '.'); ?></h3>
                <p>Pelaksanaan Belanja Rutin</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="<?= base_url('pengadaan') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-3">
            <div class="small-box bg-success">
                <div class="inner">
                <h3>Rp <?= number_format($pembayaran_belanja_rutin, 0, ',', '.'); ?></h3>
                <p>Pembayaran Belanja Rutin</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="<?= base_url('pengadaan') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-3">
        <div class="small-box bg-info">
                <div class="inner">
                    <h3><?= $jumlah_pengadaan_belanja_modal; ?></h3>
                    <p>Jumlah Pengadaan Belanja Modal</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="<?= base_url('pengadaan') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        
        <div class="col-3">
            <!-- small box -->
            <div class="small-box" style="background-color:#DBB300FF; color:white">
                <div class="inner">
                <h3>Rp <?= number_format($perencanaan_belanja_modal, 0, ',', '.'); ?></h3>
                    <p>Perencanaan Belanja Modal</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="<?= base_url('pengadaan') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        
        <div class="col-3">
            <div class="small-box bg-danger">
                <div class="inner">
                <h3>Rp <?= number_format($pelaksanaan_belanja_modal, 0, ',', '.'); ?></h3>
                <p>Pelaksanaan Belanja Modal</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="<?= base_url('pengadaan') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-3">
            <div class="small-box bg-success">
                <div class="inner">
                <h3>Rp <?= number_format($pembayaran_belanja_modal, 0, ',', '.'); ?></h3>
                <p>Pembayaran Belanja Modal</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="<?= base_url('pengadaan') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
</div>
<!-- /.card-body -->
<?php $this->endSection(); ?>