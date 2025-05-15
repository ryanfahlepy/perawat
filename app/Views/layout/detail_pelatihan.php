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
        background-color: #f8f9fc;
    }

    .hover-shadow-lg:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    .card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .card-header {
        background-color: #f8f9fa;
        padding: 20px 24px;
        border-bottom: 1px solid #dee2e6;
        position: relative;
    }

    .card-body {
        padding: 24px;
    }

    .pelatihan-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 20px;
    }

    .pelatihan-box {
        background-color: #ffffff;
        border-left: 5px solid #1e88e5;
        padding: 20px;
        border-radius: 8px;
        transition: all 0.2s ease;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }

    .pelatihan-box:hover {
        background-color: #f9fcff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .form-label {
        font-weight: 600;
    }

    .btn-primary {
        background-color: #1e88e5;
        border-color: #1e88e5;
        border-radius: 6px;
    }

    .btn-outline-success {
        border-radius: 6px;
    }

    .alert-info {
        background-color: #e9f6ff;
        border-color: #b6e0fe;
        color: #31708f;
    }

    .section-title {
        border-bottom: 2px solid #e0e0e0;
        padding-bottom: 8px;
        margin-bottom: 16px;
        font-weight: 600;
        font-size: 18px;
        color: #444;
    }

    .btn-kembali {
        background-color: #6c757d;
        font-weight: 500;
        padding: 6px 16px;
        font-size: 14px;
        border-radius: 6px;
    }

    .form-control {
        border-radius: 6px;
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

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="lokasi" class="form-label">Lokasi</label>
                        <input type="text" name="lokasi" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" required>
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
                    <div class="pelatihan-box">
                        <h6 class="fw-semibold text-primary mb-2"><?= esc($p['judul']) ?></h6>
                        <p class="mb-1"><span class="fw-semibold">Lokasi:</span> <?= esc($p['lokasi']) ?></p>
                        <p class="mb-2"><span class="fw-semibold">Tanggal:</span> <?= esc($p['tanggal']) ?></p>
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
