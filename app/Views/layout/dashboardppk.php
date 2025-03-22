<?php
$session = \Config\Services::session();
?>
<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>

<div class="card-header">
    <h3 class="card-title">Hallo <b><?= $session->nama; ?></b>, Selamat datang</h3>
</div>

<!-- STYLE -->
<style>
    .floating-detail-box {
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 10;
        background: white;
        color: #333;
        padding: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        border-radius: 8px;
        display: none;
        width: 100%;
    }

    .position-relative {
        position: relative;
    }

    .detail-text {
        font-size: 20px;
    }

    .small-box {
        cursor: pointer;
    }
</style>

<div class="card-body">
    <h2 class="text-center mb-3" style="font-weight:bold">INFORMASI PENGADAAN</h2>
    <div class="row">
        <!-- JUMLAH PENGADAAN -->
        <div class="col-3 position-relative">
            <div class="small-box bg-info"
                onmouseover="showDetail('detail-pengadaan')" 
                onmouseout="hideDetail('detail-pengadaan')">
                <div class="inner">
                    <h3><?= $jumlah_pengadaan; ?></h3>
                    <p>Jumlah Pengadaan</p>
                </div>
                <div class="icon"><i class="ion ion-bag"></i></div>
                <a class="small-box-footer">Arahkan kursor untuk lihat rincian</a>
                <div id="detail-pengadaan" class="floating-detail-box">
                    <div class="detail-text"><strong>DIPA DISINFOLAHTAL:</strong> <?= $jumlah_pengadaan_belanja_rutin; ?></div>
                    <div class="detail-text"><strong>DIPA MABES TNI AL:</strong> <?= $jumlah_pengadaan_belanja_modal; ?></div>
                </div>
            </div>
        </div>

        <!-- TOTAL PERENCANAAN -->
        <div class="col-3 position-relative">
            <div class="small-box" style="background-color:#DBB300FF; color:white"
                onmouseover="showDetail('detail-perencanaan')" 
                onmouseout="hideDetail('detail-perencanaan')">
                <div class="inner">
                    <h3>Rp <?= number_format($total_perencanaan, 0, ',', '.'); ?></h3>
                    <p>Total Perencanaan</p>
                </div>
                <div class="icon"><i class="ion ion-bag"></i></div>
                <a class="small-box-footer">Arahkan kursor untuk lihat rincian</a>
                <div id="detail-perencanaan" class="floating-detail-box">
                    <div class="detail-text"><strong>DIPA DISINFOLAHTAL:</strong> Rp <?= number_format($perencanaan_belanja_rutin, 0, ',', '.'); ?></div>
                    <div class="detail-text"><strong>DIPA MABES TNI AL:</strong> Rp <?= number_format($perencanaan_belanja_modal, 0, ',', '.'); ?></div>
                </div>
            </div>
        </div>

        <!-- TOTAL PELAKSANAAN -->
        <div class="col-3 position-relative">
            <div class="small-box bg-danger"
                onmouseover="showDetail('detail-pelaksanaan')" 
                onmouseout="hideDetail('detail-pelaksanaan')">
                <div class="inner">
                    <h3>Rp <?= number_format($total_pelaksanaan, 0, ',', '.'); ?></h3>
                    <p>Total Pelaksanaan</p>
                </div>
                <div class="icon"><i class="ion ion-bag"></i></div>
                <a class="small-box-footer">Arahkan kursor untuk lihat rincian</a>
                <div id="detail-pelaksanaan" class="floating-detail-box">
                    <div class="detail-text"><strong>DIPA DISINFOLAHTAL:</strong> Rp <?= number_format($pelaksanaan_belanja_rutin, 0, ',', '.'); ?></div>
                    <div class="detail-text"><strong>DIPA MABES TNI AL:</strong> Rp <?= number_format($pelaksanaan_belanja_modal, 0, ',', '.'); ?></div>
                </div>
            </div>
        </div>

        <!-- TOTAL PEMBAYARAN -->
        <div class="col-3 position-relative">
            <div class="small-box bg-success"
                onmouseover="showDetail('detail-pembayaran')" 
                onmouseout="hideDetail('detail-pembayaran')">
                <div class="inner">
                    <h3>Rp <?= number_format($total_pembayaran, 0, ',', '.'); ?></h3>
                    <p>Total Pembayaran</p>
                </div>
                <div class="icon"><i class="ion ion-bag"></i></div>
                <a class="small-box-footer">Arahkan kursor untuk lihat rincian</a>
                <div id="detail-pembayaran" class="floating-detail-box">
                    <div class="detail-text"><strong>DIPA DISINFOLAHTAL:</strong> Rp <?= number_format($pembayaran_belanja_rutin, 0, ',', '.'); ?></div>
                    <div class="detail-text"><strong>DIPA MABES TNI AL:</strong> Rp <?= number_format($pembayaran_belanja_modal, 0, ',', '.'); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPT -->
<script>
    const isMobile = window.matchMedia("(pointer: coarse)").matches;

    function showDetail(id) {
        if (!isMobile) {
            document.getElementById(id).style.display = 'block';
        }
    }

    function hideDetail(id) {
        if (!isMobile) {
            document.getElementById(id).style.display = 'none';
        }
    }

    function toggleDetailBox(id) {
        const box = document.getElementById(id);
        if (box.style.display === 'block') {
            box.style.display = 'none';
        } else {
            // Tutup semua box lainnya dulu
            document.querySelectorAll('.floating-detail-box').forEach(el => el.style.display = 'none');
            box.style.display = 'block';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (isMobile) {
            document.querySelectorAll('.small-box').forEach(box => {
                const detailBox = box.querySelector('.floating-detail-box');
                if (detailBox) {
                    const detailId = detailBox.id;
                    box.addEventListener('click', function (e) {
                        toggleDetailBox(detailId);
                        e.stopPropagation();
                    });
                }
            });

            // Tutup box saat klik di luar elemen
            document.addEventListener('click', function () {
                document.querySelectorAll('.floating-detail-box').forEach(box => {
                    box.style.display = 'none';
                });
            });
        }
    });
</script>

<?php $this->endSection(); ?>
