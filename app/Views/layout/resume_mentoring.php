<?php

$session = \Config\Services::session();
?>
<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>


<div class="card-header">

    <h3 class="card-title">Hallo <b><?= $session->nama; ?></b>, Selamat datang</h3>
</div>
<div class="card-body">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th class="text-center" style="width: 5%">No</th>
                <th class="text-center" style="width: 25%">Nama</th>
                <th class="text-center" style="width: 15%">Tanggal Mulai</th>
                <th class="text-center" style="width: 15%">Tanggal Berakhir</th>
                <th class="text-center" style="width: 15%">Countdown</th>
                <!-- <th class="text-center" style="width: 15%">Progress</th> -->
                <th class="text-center" style="width: 10%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            usort($daftarForms, function ($a, $b) {
                return $a['id'] <=> $b['id'];
            });
            foreach ($daftarForms as $index => $form): ?>
                <tr>
                    <td class="text-center"><?= $index + 1 ?></td>
                    <td class="text-center"><?= $form['nama'] ?></td>
                    <td class="text-center"><?= $form['tanggal_mulai_formatted'] ?></td>
                    <td class="text-center"><?= $form['tanggal_berakhir_formatted'] ?></td>
                    <td class="text-center">
                        <span class="countdown" data-endtime="<?= $form['tanggal_berakhir'] ?>"
                            id="countdown-<?= $form['id'] ?>">
                            Loading...
                        </span>
                    </td>
                    <!-- <td class="text-center">
                        <div class="progress">
                            <div class="progress-bar <?= $form['progress'] == 100 ? 'bg-success' : 'bg-primary' ?>"
                                role="progressbar" style="width: <?= $form['progress'] ?>%"
                                aria-valuenow="<?= $form['progress'] ?>" aria-valuemin="0" aria-valuemax="100">
                                <?= $form['progress'] ?>%
                            </div>
                        </div>
                    </td> -->
                    <td class="text-center">
                        <a href="<?= base_url('/mentoring/form_hasil/' . $userData->id . '/' . $form['id']) ?>"
                            class="btn btn-sm btn-info" title="Lihat Detail">
                            <i class="text-white fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        function updateCountdown() {
            const countdownElements = document.querySelectorAll('.countdown');

            countdownElements.forEach(function (el) {
                const endTime = new Date(el.dataset.endtime).getTime();
                const now = new Date().getTime();
                const distance = endTime - now;

                if (distance < 0) {
                    el.innerHTML = 'Selesai';
                    el.classList.add('text-danger');
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                el.innerHTML = `${days}h ${hours}j ${minutes}m ${seconds}s`;
            });
        }

        // Jalankan pertama kali
        updateCountdown();

        // Jalankan setiap detik
        setInterval(updateCountdown, 1000);
    });
</script>

<?php $this->endSection(); ?>