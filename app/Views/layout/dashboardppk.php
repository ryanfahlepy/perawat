<?php

$session = \Config\Services::session();
?>
<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>


<div class="card-header">

    <h3 class="card-title">Hallo <b><?= $session->nama; ?></b>, Selamat datang</h3>
</div>
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


</script>
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
    <form method="get">
        <label for="tahun">Tahun Anggaran : </label>
        <select name="tahun" id="tahun" onchange="this.form.submit()">
            <option value="">Semua Tahun</option>
            <?php foreach ($tahun_tersedia as $t): ?>
                <option value="<?= $t['tahun_anggaran'] ?>" <?= $t['tahun_anggaran'] == $tahun_dipilih ? 'selected' : '' ?>>
                    <?= $t['tahun_anggaran'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
    <h2 class="text-center mb-3" style="font-weight:bold">INFORMASI PENGADAAN</h2>
    <div class="row">
        <!-- JUMLAH PENGADAAN -->
        <div class="col-3 position-relative">
            <div class="small-box bg-info" onmouseover="showDetail('detail-pengadaan')"
                onmouseout="hideDetail('detail-pengadaan')">
                <div class="inner">
                    <h3><?= $jumlah_pengadaan; ?></h3>
                    <p>Jumlah Pengadaan</p>
                </div>
                <div class="icon"><i class="ion ion-bag"></i></div>
                <a class="small-box-footer">Arahkan kursor untuk lihat rincian</a>
                <div id="detail-pengadaan" class="floating-detail-box">
                    <div class="detail-text"><strong>DIPA DISINFOLAHTAL:</strong>
                        <?= $jumlah_pengadaan_belanja_rutin; ?></div>
                    <div class="detail-text"><strong>DIPA MABES TNI AL:</strong> <?= $jumlah_pengadaan_belanja_modal; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- TOTAL PERENCANAAN -->
        <div class="col-3 position-relative">
            <div class="small-box" style="background-color:#DBB300FF; color:white"
                onmouseover="showDetail('detail-perencanaan')" onmouseout="hideDetail('detail-perencanaan')">
                <div class="inner">
                    <h3>Rp <?= number_format($total_perencanaan, 0, ',', '.'); ?></h3>
                    <p>Total Perencanaan</p>
                </div>
                <div class="icon"><i class="ion ion-bag"></i></div>
                <a class="small-box-footer">Arahkan kursor untuk lihat rincian</a>
                <div id="detail-perencanaan" class="floating-detail-box">
                    <div class="detail-text"><strong>DIPA DISINFOLAHTAL:</strong> Rp
                        <?= number_format($perencanaan_belanja_rutin, 0, ',', '.'); ?>
                    </div>
                    <div class="detail-text"><strong>DIPA MABES TNI AL:</strong> Rp
                        <?= number_format($perencanaan_belanja_modal, 0, ',', '.'); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- TOTAL PELAKSANAAN -->
        <div class="col-3 position-relative">
            <div class="small-box bg-danger" onmouseover="showDetail('detail-pelaksanaan')"
                onmouseout="hideDetail('detail-pelaksanaan')">
                <div class="inner">
                    <h3>Rp <?= number_format($total_pelaksanaan, 0, ',', '.'); ?></h3>
                    <p>Total Pelaksanaan</p>
                </div>
                <div class="icon"><i class="ion ion-bag"></i></div>
                <a class="small-box-footer">Arahkan kursor untuk lihat rincian</a>
                <div id="detail-pelaksanaan" class="floating-detail-box">
                    <div class="detail-text"><strong>DIPA DISINFOLAHTAL:</strong> Rp
                        <?= number_format($pelaksanaan_belanja_rutin, 0, ',', '.'); ?>
                    </div>
                    <div class="detail-text"><strong>DIPA MABES TNI AL:</strong> Rp
                        <?= number_format($pelaksanaan_belanja_modal, 0, ',', '.'); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- TOTAL PEMBAYARAN -->
        <div class="col-3 position-relative">
            <div class="small-box bg-success" onmouseover="showDetail('detail-pembayaran')"
                onmouseout="hideDetail('detail-pembayaran')">
                <div class="inner">
                    <h3>Rp <?= number_format($total_pembayaran, 0, ',', '.'); ?></h3>
                    <p>Total Pembayaran</p>
                </div>
                <div class="icon"><i class="ion ion-bag"></i></div>
                <a class="small-box-footer">Arahkan kursor untuk lihat rincian</a>
                <div id="detail-pembayaran" class="floating-detail-box">
                    <div class="detail-text"><strong>DIPA DISINFOLAHTAL:</strong> Rp
                        <?= number_format($pembayaran_belanja_rutin, 0, ',', '.'); ?>
                    </div>
                    <div class="detail-text"><strong>DIPA MABES TNI AL:</strong> Rp
                        <?= number_format($pembayaran_belanja_modal, 0, ',', '.'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Judul dan Canvas Chart -->

<div>
    <div class="row">
        <div class="col-6">
            <h5 class="mt-4 text-center">Jumlah Pengadaan per Bulan pada Tahun <?= $tahun_dipilih ?? 'Semua Tahun' ?>
            </h5>
            <canvas id="jumlahPengadaanPerBulanChart" width="200" height="100"></canvas>
        </div>
        <div class="col-6">
            <h5 class="mt-4 text-center">Jumlah Pengadaan Berakhir per Bulan pada Tahun
                <?= $tahun_dipilih ?? 'Semua Tahun' ?>
            </h5>
            <canvas id="pengadaanBerakhirPerBulanChart" width="400" height="200"></canvas>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-6 d-flex justify-content-center">
            <canvas id="donutJenisChart" style="width: 400px; height: 400px;"></canvas>
        </div>
        <div class="col-6 d-flex justify-content-center">
            <canvas id="donutMetodeChart" style="width: 400px; height: 400px;"></canvas>
        </div>
    </div>


    <!-- SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('jumlahPengadaanPerBulanChart').getContext('2d');
        const jumlahPengadaanPerBulanChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= $bulan_mulai; ?>, // contoh: ["January", "February", ...]
                datasets: [{
                    label: 'Jumlah Pengadaan',
                    data: <?= $jumlah_mulai; ?>, // contoh: [2, 4, 3, ...]
                    backgroundColor: '#17a2b8',
                    borderColor: '#17a2b8',
                    borderWidth: 1,
                    hoverBackgroundColor: '#006170FF'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            // hanya tampilkan angka bulat
                            stepSize: 1,
                            callback: function (value) {
                                return Number.isInteger(value) ? value : null;
                            }
                        },
                        title: {
                            display: true,
                            text: 'Jumlah Pengadaan'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Bulan',

                        }
                    }
                }

            }
        });
    </script>
    <script>
        const ctxBerakhir = document.getElementById('pengadaanBerakhirPerBulanChart').getContext('2d');
        const pengadaanBerakhirPerBulanChart = new Chart(ctxBerakhir, {
            type: 'bar',
            data: {
                labels: <?= $bulan_berakhir; ?>,
                datasets: [{
                    label: 'Jumlah Pengadaan Berakhir',
                    data: <?= $jumlah_berakhir; ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1,
                    hoverBackgroundColor: 'rgba(255, 99, 132, 0.8)'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            callback: function (value) {
                                return Number.isInteger(value) ? value : null;
                            }
                        },
                        title: {
                            display: true,
                            text: 'Jumlah Pengadaan Berakhir'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Bulan'
                        }
                    }
                }
            }
        });
    </script>
    <script>
        const ctxJenis = document.getElementById('donutJenisChart').getContext('2d');
        const donutJenisChart = new Chart(ctxJenis, {
            type: 'doughnut',
            data: {
                labels: <?= $label_jenis ?>,
                datasets: [{
                    label: 'Distribusi Pengadaan per Jenis',
                    data: <?= $data_jenis ?>,
                    backgroundColor: [
                        '#17a2b8',
                        '#1cc88a',
                        '#36a2eb',
                        '#f6c23e',
                        '#e74a3b',
                        '#858796'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    title: {
                        display: true,
                        text: 'Distribusi Pengadaan per Jenis',
                        font: {
                            size: 20, weight: 'normal'
                        }
                    }

                },
                maintainAspectRatio: false,


            }
        });
    </script>
    <script>
        const ctxMetode = document.getElementById('donutMetodeChart').getContext('2d');
        const donutMetodeChart = new Chart(ctxMetode, {
            type: 'doughnut',
            data: {
                labels: <?= $label_metode ?>,
                datasets: [{
                    label: 'Distribusi Pengadaan per Metode',
                    data: <?= $data_metode ?>,
                    backgroundColor: [
                        '#17a2b8',
                        '#ff6384',
                        '#ffcd56',
                        '#4bc0c0',
                        '#9966ff',
                        '#c9cbcf'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const label = context.label || '';
                                const value = context.parsed;
                                const total = context.chart._metasets[0].total;
                                const percent = ((value / total) * 100).toFixed(1);
                                return `${label}: ${value} (${percent}%)`;
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Distribusi Pengadaan per Metode',
                        font: {
                            size: 20, weight: 'normal'
                        }
                    }
                }
            }
        });
    </script>


    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <?php $this->endSection(); ?>