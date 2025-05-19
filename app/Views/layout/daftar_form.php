<?php

$session = \Config\Services::session();
?>
<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>


<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Form untuk <?= $userData->nama ?></h3>
                    <div class="card-tools">
                        <a href="<?= base_url('/mentoring/form/' . $userData->id) ?>" class="btn btn-primary"
                            id="btnBuatForm">
                            <i class="fas fa-plus"></i> Buat Form Baru
                        </a>

                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($daftarForms)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Belum ada form yang dibuat
                        </div>
                    <?php else: ?>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 5%">No</th>
                                    <th class="text-center" style="width: 25%">Nama</th>
                                    <th class="text-center" style="width: 15%">Tanggal Mulai</th>
                                    <th class="text-center" style="width: 15%">Tanggal Berakhir</th>
                                    <th class="text-center" style="width: 15%">Countdown</th>
                                    <th class="text-center" style="width: 15%">Progress</th>
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
                                        <td class="text-center">
                                            <div class="progress">
                                                <div class="progress-bar <?= $form['progress'] == 100 ? 'bg-success' : 'bg-primary' ?>"
                                                    role="progressbar" style="width: <?= $form['progress'] ?>%"
                                                    aria-valuenow="<?= $form['progress'] ?>" aria-valuemin="0"
                                                    aria-valuemax="100">
                                                    <?= $form['progress'] ?>%
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= base_url('/mentoring/form/' . $userData->id . '/' . $form['id']) ?>"
                                                class="btn btn-sm btn-info" title="Lihat Detail">
                                                <i class="text-white fas fa-eye"></i>
                                            </a>

                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
                <div class="card-footer">
                    <a href="<?= base_url('/mentoring') ?>" class="btn btn-default">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Mentee
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Buat Form Baru -->
<!-- Modal untuk Buat Form Baru -->
<div class="modal fade" id="buatFormModal" tabindex="-1" aria-labelledby="buatFormModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="buatFormModalLabel">Buat Form Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formBuat" action="<?= base_url('/mentoring/buat_form/' . $userData->id) ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_form">Nama Form</label>
                        <input type="text" class="form-control" id="nama_form" name="nama_form"
                            placeholder="Contoh : Mentoring Minggu 1" required>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_mulai">Tanggal Mulai</label>
                        <input type="datetime-local" class="form-control" id="tanggal_mulai" name="tanggal_mulai"
                            value="<?= date('Y-m-d\TH:i') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_berakhir">Tanggal Berakhir</label>
                        <input type="datetime-local" class="form-control" id="tanggal_berakhir" name="tanggal_berakhir"
                            required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Mulai Form</button>
                </div>
            </form>
        </div>
    </div>
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

<script>

    document.addEventListener('DOMContentLoaded', function () {
        // Hanya tangani klik pada tombol "Buat Form Baru"
        const btnBuatForm = document.getElementById('btnBuatForm');
        if (btnBuatForm) {
            btnBuatForm.addEventListener('click', function (e) {
                e.preventDefault();
                $('#buatFormModal').modal('show');
            });
        }

        // Validasi form sebelum submit
        document.getElementById('formBuat').addEventListener('submit', function (e) {
            const mulai = new Date(document.getElementById('tanggal_mulai').value);
            const berakhir = document.getElementById('tanggal_berakhir').value;

            if (berakhir) {
                const endDate = new Date(berakhir);
                if (endDate <= mulai) {
                    alert('Tanggal berakhir harus setelah tanggal mulai');
                    e.preventDefault();
                }
            }
        });
    });

</script>
<style>
    /* Style untuk datetime picker */
    input[type="datetime-local"] {
        padding: 0.375rem 0.75rem;
        line-height: 1.5;
    }

    /* Modal lebih lebar */
    @media (min-width: 576px) {
        .modal-dialog {
            max-width: 500px;
            margin: 1.75rem auto;
        }
    }
</style>
<?= $this->endSection(); ?>