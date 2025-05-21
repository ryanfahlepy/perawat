<?php
$session = \Config\Services::session();
?>
<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>

<div class="card-header">
    <h3 class="card-title">Hallo <b><?= $session->nama; ?></b>, Selamat datang</h3>
</div>

<!-- Grafik Jumlah Perawat per Level -->
<div class="row">
    <div class="col-6 mt-2">
        <div class="card">
            <div class="card-header">
                <h4>Grafik Jumlah Perawat</h4>
            </div>
            <div class="card-body">
                <canvas id="perawatChart"></canvas>
            </div>
        </div>
    </div>
    <!-- Grafik Aktivitas Mentoring per Hari -->
    <div class="col-6 mt-2">
        <div class="card">
            <div class="card-header">
                <h4>Aktivitas Mentoring per Bulan</h4>
            </div>
            <div class="card-body">
                <canvas id="mentoringChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Daftar Perawat -->
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h4>Daftar Perawat</h4>
        </div>
        <div class="card-body" style="max-height: 400px; overflow-y: auto;">
            <table class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Foto</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">PK</th>
                        <th class="text-center">Kinerja</th>
                        <th class="text-center">Mentoring</th>
                        <th class="text-center">Pelatihan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($users as $user): ?>
                        <?php if ($user->level_user != 1): ?>
                            <tr>
                                <td class="text-center"><?= $no++; ?></td>
                                <td class="text-center">
                                    <img src="<?= base_url('assets/dist/img/user/' . $user->photo); ?>" alt="Foto" width="50"
                                        height="50">
                                </td>
                                <td><?= esc($user->nama); ?></td>
                                <td><?= esc($user->nama_level); ?></td>
                                <td class="text-center">
                                    <a href="<?= base_url('/dashboardkaru/resume_kinerja/' . $user->id . '/' . $user->id) ?>"
                                        class="btn btn-sm btn-primary" title="Lihat Kinerja Perawat">
                                        <i class="text-white fas fa-chalkboard-teacher"></i>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a href="<?= base_url('/dashboardkaru/resume_mentoring/' . $user->id) ?>"
                                        class="btn btn-sm btn-success" title="Lihat Mentoring Perawat">
                                        <i class="text-white fas fa-calendar"></i>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a href="<?= base_url('/pelatihan/lihat_pelatihan/' . $user->id) ?>"
                                        class="btn btn-sm btn-info" title="Lihat Pelatihan Perawat">
                                        <i class="text-white fas fa-info-circle"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('perawatChart').getContext('2d');

    const chartData = {
        labels: <?= json_encode(array_keys($countByLevel)) ?>,
        datasets: [{
            label: 'Jumlah Perawat',
            data: <?= json_encode(array_values($countByLevel)) ?>,
            backgroundColor: [
                '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'
            ],
            borderColor: '#ccc',
            borderWidth: 1
        }]
    };

    const chartOptions = {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: { precision: 0 }
            }
        }
    };

    new Chart(ctx, {
        type: 'bar',
        data: chartData,
        options: chartOptions
    });
</script>
<script>
    // Grafik aktivitas mentoring per hari
    const mentoringCtx = document.getElementById('mentoringChart').getContext('2d');
    const mentoringChart = new Chart(mentoringCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($mentoringLabels) ?>,
            datasets: [{
                label: 'Jumlah Aktivitas Mentoring',
                data: <?= json_encode($mentoringCounts) ?>,
                fill: true,
                borderColor: '#36A2EB',
                backgroundColor: 'rgba(54,162,235,0.1)',
                tension: 0,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,      // hanya angka bulat
                        precision: 0      // tidak pakai koma
                    },

                    title: {
                        display: true,
                        text: 'Jumlah'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Tanggal'
                    }
                }
            }
        }
    });
</script>

<?php $this->endSection(); ?>