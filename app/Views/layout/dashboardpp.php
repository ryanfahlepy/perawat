<?php
$session = \Config\Services::session();
?>
<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>
<div class="card-header">
    <h5 class="ml-2 mb-3"></h5>
                                <h3 class="card-title"> Hallo <b><?= $session->nama; ?></b>, Selamat datang </h3>

                                <!-- <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                        <i class="fas fa-minus"></i></button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
                                        <i class="fas fa-times"></i></button>
                                </div> -->
                            </div>
<div class="card-body">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3><?= $jumlah_pengadaan; ?></h3>

                    <p>Total Pengadaan</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="<?= base_url('pengadaan') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
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
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                 <h3>Rp <?= number_format($total_pelaksanaan, 0, ',', '.'); ?></h3>

                    <p>Total Pelaksanaan</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="<?= base_url('pengadaan#pelaksanaan') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box" style="background-color:#DBB300FF">
                <div class="inner">
                <h3 style="color:white">Rp <?= number_format($total_pembayaran, 0, ',', '.'); ?></h3>

                    <p style="color:white">Total Pembayaran</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="<?= base_url('pengadaan#pembayaran') ?>" class="small-box-footer" style="color:white">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                <h3><?= $jumlah_pengadaan; ?></h3>

                    <p>Total Pengadaan</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="<?= base_url('pengadaan') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>
</div>
<!-- /.card-body -->
<?php $this->endSection(); ?>