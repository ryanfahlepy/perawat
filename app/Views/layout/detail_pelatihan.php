<?= $this->extend('shared_page/template') ?>
<?= $this->section('content') ?>

<script>
    <?php if (session()->getFlashdata('message')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '<?= session('message') ?>',
            timer: 2000,
            showConfirmButton: false
        });
    <?php elseif (session()->getFlashdata('error')): ?>
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: '<?= session('error') ?>',
            timer: 2000,
            showConfirmButton: false
        });
    <?php endif; ?>
</script>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f6f9;
    }

    .card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
    }

    .card-header {
        background-color: #ffffff;
        padding: 24px;
        border-bottom: 1px solid #e3e6f0;
        position: relative;
    }

    .card-header h4 {
        font-weight: 600;
        color: #333;
    }

    .btn-kembali {
        background-color: #6c757d;
        color: #fff;
        font-weight: 500;
        padding: 8px 16px;
        font-size: 14px;
        border-radius: 8px;
        transition: all 0.2s ease-in-out;
    }

    .btn-kembali:hover {
        background-color: #5a6268;
    }

    .card-body {
        padding: 32px;
    }

    .section-title {
        border-bottom: 2px solid #e0e0e0;
        padding-bottom: 10px;
        margin-bottom: 24px;
        font-weight: 600;
        font-size: 20px;
        color: #2c3e50;
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 6px;
    }

    .form-control {
        border-radius: 8px;
        padding: 10px;
        font-size: 14px;
    }

    .btn-primary {
        background-color: #1e88e5;
        border-color: #1e88e5;
        border-radius: 8px;
        font-weight: 500;
        transition: background-color 0.2s ease;
    }

    .btn-primary:hover {
        background-color: #1565c0;
    }

    .btn-outline-success {
        border-radius: 8px;
        transition: all 0.2s;
    }

    .alert-info {
        background-color: #eaf7ff;
        border-color: #bee9ff;
        color: #31708f;
        padding: 12px 16px;
        border-radius: 8px;
    }

    .pelatihan-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
    }

    .pelatihan-box {
        background-color: #ffffff;
        border-left: 5px solid #1e88e5;
        padding: 20px;
        border-radius: 12px;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .pelatihan-box:hover {
        transform: translateY(-4px);
        background-color: #f9fcff;
        box-shadow: 0 6px 14px rgba(0,0,0,0.08);
    }

    .pelatihan-box h6 {
        font-weight: 600;
        font-size: 16px;
        margin-bottom: 6px;
    }

    .pelatihan-box p {
        font-size: 14px;
        color: #555;
        margin-bottom: 6px;
    }

    .pelatihan-box .fw-semibold {
        color: #333;
    }

    .pelatihan-box .btn {
        font-size: 13px;
        border-radius: 6px;
    }

    .text-muted {
        font-size: 13px;
    }

    .fw-semibold {
        font-weight: 600;
    }
</style>


<div class="card shadow-sm border-0">
    <div class="card-header">
        <h4 class="mb-0 fw-semibold text-dark"><?= esc($kategori) ?></h4>
        <button type="button" onclick="window.location='<?= base_url('/pelatihan') ?>'" 
            class="btn btn-sm text-white btn-kembali position-absolute top-50 end-0 translate-middle-y me-3 shadow-sm">
            ← Kembali
        </button>
    </div>
    <div class="card-body">
        <!-- Form Tambah Pelatihan -->
        <div class="tambah-pelatihan-section mb-5">
            <h5 class="section-title">Tambah Pelatihan Baru</h5>
            <form action="<?= base_url('pelatihan/simpan') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="kategori" value="<?= esc($kategori) ?>">

                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Pelatihan</label>
                    <input type="text" name="judul" class="form-control" required>
                </div>

                
                <div class="mb-3">
                    <label for="lokasi" class="form-label">Lokasi</label>
                    <input type="text" name="lokasi" class="form-control" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="tanggal" class="form-label">Tanggal Mulai</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="berlaku" class="form-label">Tanggal Berakhir</label>
                        <input type="date" name="berlaku" class="form-control" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="sertifikat" class="form-label">Upload Sertifikat</label>
                    <input type="file" name="sertifikat" class="form-control" required accept=".jpg,.jpeg,.png,.pdf">
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary shadow-sm px-4 py-2">
                        Simpan Pelatihan
                    </button>
                </div>
            </form>
        </div>

        <hr class="my-4">

        <!-- Riwayat Pelatihan -->
        <h5 class="section-title">Riwayat Pelatihan</h5>

        <?php if (empty($pelatihan_list)): ?>
            <div class="alert alert-info">
                Belum ada pelatihan yang diikuti.
            </div>
        <?php else: ?>
            <div class="pelatihan-container">
            <?php foreach ($pelatihan_list as $p): ?>
    <?php
        $berlaku = new DateTime($p['berlaku']);
        $hari_ini = new DateTime();

        $peringatan = '';
        $box_class = 'pelatihan-box'; // default class
        $border_color = '#1e88e5'; // default biru

        if ($berlaku < $hari_ini) {
            $warna = '#dc3545'; // merah
            $peringatan = '<div style="color: ' . $warna . ';" class="mt-2 fw-semibold">⚠️ Masa berlaku sudah habis</div>';
            $border_color = $warna;
        } elseif ($berlaku <= (clone $hari_ini)->modify('+1 month')) {
            $warna = '#fd7e14'; // oranye
            $peringatan = '<div style="color: ' . $warna . ';" class="mt-2 fw-semibold">⚠️ Masa berlaku hampir habis</div>';
            $border_color = $warna;
        }
    ?>
    <div class="pelatihan-box" style="border-left: 5px solid <?= $border_color ?>;">
        <h6 class="fw-semibold text-primary mb-2"><?= esc($p['judul']) ?></h6>
        <p class="mb-1"><span class="fw-semibold">Lokasi:</span> <?= esc($p['lokasi']) ?></p>
        <p class="mb-2"><span class="fw-semibold">Tanggal Mulai:</span> <?= esc($p['tanggal']) ?></p>
        <p class="mb-2"><span class="fw-semibold">Tanggal Berakhir:</span> <?= esc($p['berlaku']) ?></p>

        <?= $peringatan ?>

        <div class="mt-3 text-center">
            <?php if ($p['sertifikat']): ?>
                <a href="<?= base_url('uploads/sertifikat/' . $p['sertifikat']) ?>" target="_blank" class="btn btn-sm btn-outline-success w-100">
                    <i class="bi bi-file-earmark-pdf"></i> Lihat Sertifikat
                </a>
            <?php else: ?>
                <span class="text-muted d-block">Tidak ada sertifikat</span>
            <?php endif ?>
        </div>
    </div>
<?php endforeach ?>

            </div>
        <?php endif ?>
    </div>
</div>

<?= $this->endSection() ?>
