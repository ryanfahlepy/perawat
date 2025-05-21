<?php $session = \Config\Services::session(); ?>
<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>

<div class="card-header">
    <h3 class="card-title">Hallo <b><?= esc($session->nama); ?></b>, Selamat datang</h3>
</div>

<div class="card-body">
<form method="GET" action="<?= base_url('ekinerja') ?>" class="mb-4">
    <div class="filter-bar">
        <label for="tahun"><strong>Filter Tahun:</strong></label>
        <select name="tahun" id="tahun" onchange="this.form.submit()">
            <option value="">-- Semua Tahun --</option>
            <?php
                $tahun_sekarang = date('Y');
                $tahun_awal = 2024;
                $tahun_akhir = $tahun_sekarang + 5;
                for ($th = $tahun_awal; $th <= $tahun_akhir; $th++): ?>
                    <option value="<?= $th ?>" <?= ($th == $tahun_terpilih) ? 'selected' : '' ?>><?= $th ?></option>
            <?php endfor; ?>
        </select>
    </div>
</form>

<div class="table-responsive">
    <table class="kinerja-table">
        <thead>
            <tr>
                <th>Indikator</th>
                <th>Kode KPI</th>
                <th>Formula</th>
                <th>Sumber Data</th>
                <th>Periode</th>
                <th>Bobot</th>
                <th>Target</th>
                <th>Deskripsi Target</th>
                <th>Hasil Aktual</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($data_kinerja)): ?>
                <tr>
                    <td colspan="10" class="text-center">Tidak ada data</td>
                </tr>
            <?php else: ?>
                <?php foreach ($data_kinerja as $item): ?>
                    <?php
                    $allowed_levels = explode(',', $item['level_user']);
                    if (!in_array($level_akses, $allowed_levels)) continue;
                    ?>
                    <tr>
                        <td><?= esc($item['indikator']) ?></td>
                        <td class="text-center"><?= esc($item['kode_kpi']) ?></td>
                        <td class="text-center"><?= esc($item['formula']) ?></td>
                        <td><?= esc($item['sumber_data']) ?></td>
                        <td class="text-center"><?= esc($item['periode_assesment']) ?></td>
                        <td class="text-center"><?= esc($item['bobot']) ?>%</td>
                        <td class="text-center"><?= esc($item['target']) ?></td>
                        <td><?= esc($item['deskripsi_target']) ?></td>
                        <td>
                            <?php if (strtolower($item['periode_assesment']) === 'bulanan'): ?>
                                <div class="aktual-bulanan-grid">
                                    <?php
                                        $namaBulan = [
                                            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                                            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
                                            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
                                        ];
                                        foreach ($item['hasil_bulanan'] as $bulan => $hasil):
                                    ?>
                                        <div class="aktual-badge" onclick="openModal('<?= $item['id'] ?>', '<?= $bulan ?>', '<?= is_numeric($hasil) ? $hasil : '' ?>', '', '', '', '', '<?= $item['status_bulanan'][$bulan] ?>')">
                                            <span class="bulan"><?= $namaBulan[$bulan] ?></span>
                                            <span class="nilai"><?= esc($hasil) ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="aktual-single" onclick="openModal('<?= $item['id'] ?>', '', '<?= is_numeric($item['hasil_aktual']) ? $item['hasil_aktual'] : '' ?>', '', '', '', '', '<?= $item['status_tahunan'] ?>')">
                                    <?= esc($item['hasil_aktual']) ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <a href="<?= base_url('admin/manekinerja/lihat_hasil/' . $item['id']) ?>" class="btn btn-sm btn-info" title="Lihat Hasil">
                                <i class="fas fa-eye text-white"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Form -->
<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-sm border-0 rounded-3">
            <form id="formAktual" method="POST" enctype="multipart/form-data" action="<?= base_url('ekinerja/update_hasil') ?>">
                
                <!-- Header Modal -->
                <div class="modal-header bg-light border-bottom">
                    <div>
                        <h5 class="modal-title fw-semibold d-flex align-items-center gap-2" id="modalTitle">
                            Formulir Hasil KPI
                            <span id="statusBadge" class="badge text-bg-secondary text-capitalize fs-6 fw-normal"></span>
                        </h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <!-- Body Modal -->
                <div class="modal-body bg-white">
                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-md-8">
                            <input type="hidden" name="kinerja_id" id="inputKinerjaId">
                            <input type="hidden" name="bulan" id="inputBulan">
                            <input type="hidden" name="tahun" id="inputTahun" value="<?= esc($tahun_terpilih) ?>">

                            <div class="mb-3">
                                <label for="inputHasil" class="form-label">Hasil Aktual KPI <span class="text-danger">*</span></label>
                                <input type="number" name="hasil" id="inputHasil" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="inputBerkas" class="form-label">Berkas Pendukung <small class="text-muted">(opsional)</small></label>
                                <input type="file" name="berkas" id="inputBerkas" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="inputTarget" class="form-label">Target KPI</label>
                                <input type="text" id="inputTarget" class="form-control" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="inputNilai" class="form-label">Nilai KPI</label>
                                <input type="text" id="inputNilai" class="form-control" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="inputPoint" class="form-label">Point KPI</label>
                                <input type="text" id="inputPoint" class="form-control" readonly>
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-4">
                            <label for="inputCatatan" class="form-label fw-semibold">Catatan Karu</label>
                            <textarea id="inputCatatan" class="form-control bg-light" readonly rows="13"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Footer Modal -->
                <div class="modal-footer bg-light border-top">
                    <?php if ($level_akses != 2): ?>
                        <button type="submit" class="btn btn-outline-success">
                            <i class="fas fa-save me-1"></i> Simpan
                        </button>
                    <?php endif; ?>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CSS -->
<style>
    .modal-title {
        font-size: 1.25rem;
        font-weight: 600;
    }

    .form-label {
        font-weight: 500;
        font-size: 14px;
        color: #333;
    }

    .form-control {
        font-size: 14px;
        border-radius: 6px;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.6em;
    }

    .text-bg-secondary {
        background-color: #dee2e6 !important;
        color: #495057 !important;
    }

    .btn-outline-success {
        font-weight: 500;
        font-size: 14px;
    }

    textarea.form-control {
        resize: none;
        font-size: 14px;
    }
</style>

<!-- JavaScript -->
<script>
    function openModal(kinerjaId, bulan = '', nilai = '', target = '', nilaiKpi = '', point = '', catatan = '', status = '') {
    const modal = new bootstrap.Modal(document.getElementById('modalForm'));
    modal.show();

    const tahun = document.getElementById('inputTahun').value;

    // Isi input hidden
    document.getElementById('inputKinerjaId').value = kinerjaId;
    document.getElementById('inputBulan').value = bulan;

    // Kosongkan sementara nilai input
    document.getElementById('inputHasil').value = '';
    document.getElementById('inputTarget').value = '';
    document.getElementById('inputNilai').value = '';
    document.getElementById('inputPoint').value = '';
    document.getElementById('inputCatatan').value = '';
    document.getElementById('statusBadge').textContent = status || 'Belum Dinilai';

    // Ambil data dari endpoint
    const url = `<?= base_url('ekinerja/get_hasil') ?>?kinerja_id=${kinerjaId}&tahun=${tahun}&bulan=${bulan}`;
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Gagal memuat data hasil');
            }
            return response.json();
        })
        .then(data => {
            if (data) {
                document.getElementById('inputHasil').value = data.hasil ?? '';
                document.getElementById('inputTarget').value = data.target ?? '';
                document.getElementById('inputCatatan').value = data.catatan ?? '';
                // Nilai dan point dikosongkan sementara seperti permintaan
                document.getElementById('inputNilai').value = '';
                document.getElementById('inputPoint').value = '';
            }
        })
        .catch(error => {
            console.error('Terjadi kesalahan:', error);
            alert('Gagal mengambil data hasil kinerja.');
        });
}
</script>



<style>
    .filter-bar {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    font-family: 'Segoe UI', sans-serif;
}

.filter-bar select {
    padding: 6px 12px;
    border-radius: 6px;
    border: 1px solid #ccc;
    min-width: 160px;
    font-size: 14px;
}

.kinerja-table {
    width: 100%;
    border-collapse: collapse;
    font-family: 'Segoe UI', Tahoma, sans-serif;
    font-size: 14px;
    background-color: #fff;
    box-shadow: 0 0 5px rgba(0,0,0,0.05);
}

.kinerja-table thead {
    background-color: #f1f5f9;
    text-align: center;
    color: #333;
}

.kinerja-table th,
.kinerja-table td {
    padding: 10px 12px;
    border: 1px solid #dee2e6;
    vertical-align: top;
}

.kinerja-table tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

.kinerja-table tbody tr:hover {
    background-color: #eef5ff;
}

.aktual-bulanan-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.aktual-badge {
    background-color: #e8f0fe;
    border-radius: 6px;
    padding: 4px 6px;
    text-align: center;
    min-width: 48px;
    font-size: 12px;
    font-weight: 500;
    color: #1a73e8;
    display: flex;
    flex-direction: column;
    align-items: center;
    cursor: pointer; /* Tambahkan baris ini */
}


.aktual-badge .bulan {
    font-size: 11px;
    color: #555;
}

.aktual-badge .nilai {
    font-weight: bold;
}

.aktual-single {
    background-color: #d1e7dd;
    padding: 6px 10px;
    border-radius: 5px;
    font-weight: bold;
    text-align: center;
    color: #0f5132;
    display: inline-block;
}

</style>

<?php $this->endSection(); ?>
