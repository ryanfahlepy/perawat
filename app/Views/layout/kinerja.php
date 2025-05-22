<?php $session = \Config\Services::session(); ?>
<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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


<div class="card-header">
    <h3 class="card-title">Hasil Kinerja untuk <b><?= esc($user->nama); ?></b></h3>
</div>

<div class="card-body">
    <form method="GET" action="<?= base_url('kinerja') ?>" class="mb-4">
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
                        if (!in_array($level_akses, $allowed_levels))
                            continue;
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
                                            1 => 'Jan',
                                            2 => 'Feb',
                                            3 => 'Mar',
                                            4 => 'Apr',
                                            5 => 'Mei',
                                            6 => 'Jun',
                                            7 => 'Jul',
                                            8 => 'Agu',
                                            9 => 'Sep',
                                            10 => 'Okt',
                                            11 => 'Nov',
                                            12 => 'Des'
                                        ];
                                        foreach ($item['hasil_bulanan'] as $bulan => $hasil):
                                            ?>
                                            <div class="aktual-badge"
                                                onclick="openModal('<?= $item['id'] ?>', '<?= $bulan ?>', '<?= is_numeric($hasil) ? $hasil : '' ?>', '', '', '', '', '<?= $item['status_bulanan'][$bulan] ?>')">
                                                <span class="bulan"><?= $namaBulan[$bulan] ?></span>
                                                <span class="nilai"><?= esc($hasil) ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="aktual-single"
                                        onclick="openModal('<?= $item['id'] ?>', '', '<?= is_numeric($item['hasil_aktual']) ? $item['hasil_aktual'] : '' ?>', '', '', '', '', '<?= $item['status_tahunan'] ?>')">
                                        <?= esc($item['hasil_aktual']) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="<?= base_url('admin/mankinerja/lihat_hasil/' . $item['id']) ?>"
                                    class="btn btn-sm btn-info" title="Lihat Hasil">
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
    <div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="modalTitle">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow-sm border-0 rounded-3">
                <div class="modal-header bg-light border-bottom">
                    <h5 class="modal-title fw-semibold d-flex align-items-center gap-2">
                        Formulir Kinerja & PICA
                        <span id="statusBadge" class="badge text-bg-secondary text-capitalize fs-6 fw-normal"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body bg-white">
                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs mb-3" id="modalTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="kpi-tab" data-bs-toggle="tab"
                                data-bs-target="#kpi-tab-pane" type="button" role="tab">KPI</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pica-tab" data-bs-toggle="tab" data-bs-target="#pica-tab-pane"
                                type="button" role="tab">PICA</button>
                        </li>
                    </ul>

                    <!-- Tab Contents -->
                    <div class="tab-content" id="modalTabContent">
                        <!-- KPI Form -->
                        <div class="tab-pane fade show active" id="kpi-tab-pane" role="tabpanel">
                            <form id="formAktual" method="POST" enctype="multipart/form-data"
                                action="<?= base_url('kinerja/update_hasil') ?>">
                                <div class="row">
                                    <!-- Left -->
                                    <div class="col-md-8">
                                        <input type="hidden" name="kinerja_id" id="inputKinerjaId">
                                        <input type="hidden" name="bulan" id="inputBulan">
                                        <input type="hidden" name="tahun" id="inputTahun"
                                            value="<?= esc($tahun_terpilih) ?>">

                                        <div class="mb-3">
                                            <label for="inputHasil" class="form-label">Hasil Aktual KPI <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" name="hasil" id="inputHasil" class="form-control"
                                                <?= ($level_akses == 2) ? 'readonly' : '' ?> required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="inputBerkas" class="form-label">Berkas Pendukung <small
                                                    class="text-muted">(opsional)</small></label>
                                            <input type="file" name="berkas" id="inputBerkas" class="form-control"
                                                <?= ($level_akses == 2) ? 'disabled' : '' ?>>
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

                                    <!-- Right -->
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Catatan Karu</label>
                                        <textarea id="inputCatatan" disabled class="form-control bg-light" rows="13"></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- PICA Form -->
                        <div class="tab-pane fade" id="pica-tab-pane" role="tabpanel">
                            <form id="formPica" method="POST" action="<?= base_url('pica/simpan') ?>">
                                <input type="hidden" name="kinerja_id" id="picaKinerjaId">

                                <div class="row">
                                    <!-- Left -->
                                    <div class="col-md-8">
                                        <input type="hidden" id="user_id" value="<?= esc($session->user_id) ?>">
                                        <input type="hidden" id="hasil_id" value="">
                                        <input type="hidden" name="kinerja_id" id="inputKinerjaId">

                                        <div class="mb-3">
                                            <label for="problem_identification" class="form-label">Problem
                                                Identification</label>
                                            <textarea name="problem_identification" id="problem_identification"
                                                class="form-control" <?= ($level_akses == 2) ? 'readonly' : '' ?>
                                                required></textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label for="corrective_action" class="form-label">Corrective Action</label>
                                            <textarea name="corrective_action" id="corrective_action"
                                                class="form-control" <?= ($level_akses == 2) ? 'readonly' : '' ?>
                                                required></textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label for="due_date" class="form-label">Due Date</label>
                                            <input type="date" name="due_date" id="due_date" class="form-control"
                                                <?= ($level_akses == 2) ? 'readonly' : '' ?> required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="pic" class="form-label">PIC</label>
                                            <input type="text" name="pic" id="pic" class="form-control" value="Karu"
                                                readonly>
                                        </div>
                                    </div>

                                    <!-- Right -->
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Catatan Karu</label>
                                        <textarea disabled id="catatanKaruPica" class="form-control bg-light" rows="13"></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top">
                    <?php if ($level_akses != 2): ?>
                        <button id="btnSimpan" type="button" class="btn btn-success" onclick="submitCombinedForm()">
                            <i class="fas fa-save me-1"></i> Simpan
                        </button>

                    <?php endif; ?>


                </div>
            </div>
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

        document.getElementById('statusBadge').textContent = status || 'Belum Dinilai';

        document.getElementById('inputKinerjaId').value = kinerjaId;
        document.getElementById('inputBulan').value = bulan;
        document.getElementById('inputHasil').value = '';
        document.getElementById('inputTarget').value = '';
        document.getElementById('inputNilai').value = '';
        document.getElementById('inputPoint').value = '';
        document.getElementById('inputCatatan').value = '';

        const inputHasil = document.getElementById('inputHasil');
        const inputTarget = document.getElementById('inputTarget');
        const inputNilai = document.getElementById('inputNilai');
        const inputPoint = document.getElementById('inputPoint');
        const hasilIdField = document.getElementById('hasil_id');
        const inputCatatan = document.getElementById('inputCatatan');

        const userId = document.getElementById('user_id').value;
        console.log(userId);

        // Ambil data KPI terlebih dahulu
        fetch(`<?= base_url('kinerja/get_hasil') ?>?kinerja_id=${kinerjaId}&tahun=${tahun}&bulan=${bulan}`)
            .then(res => res.json())
            .then(data => {
                const targetValue = parseFloat(data.target) || 0;
                inputTarget.value = targetValue;


                inputHasil.value = data.hasil || '';
                inputCatatan.value = data.catatan || '';
                // Simpan hasil_id dari server
                const hasilId = data.id || '';
                hasilIdField.value = hasilId;

                // Perhitungan otomatis
                inputHasil.addEventListener('input', () => {
                    const hasil = parseFloat(inputHasil.value);
                    let nilai = 0, point = 0;

                    if (!isNaN(hasil) && !isNaN(targetValue)) {
                        nilai = (targetValue === 0)
                            ? (hasil === 0 ? 100 : 0)
                            : (hasil / targetValue) * 100;
                        point = nilai * 0.1;

                        inputNilai.value = nilai.toFixed(2);
                        inputPoint.value = point.toFixed(2);
                        handlePicaActivation(nilai);
                    } else {
                        inputNilai.value = '';
                        inputPoint.value = '';
                        handlePicaActivation(null);
                    }
                });

                inputHasil.dispatchEvent(new Event('input'));

                // Hanya fetch PICA jika hasil_id tersedia
                if (hasilId) {
                    console.log('DEBUG get_pica_by_kinerja =>', {
                        kinerja_id: kinerjaId,
                        user_id: userId,
                        hasil_id: hasilId
                    });

                    fetch(`<?= base_url('kinerja/get_pica_by_kinerja') ?>?kinerja_id=${kinerjaId}&user_id=${userId}&hasil_id=${hasilId}`)
                        .then(res => res.json())
                        .then(pica => {
                            document.getElementById('problem_identification').value = pica.problem_identification || '';
                            document.getElementById('corrective_action').value = pica.corrective_action || '';
                            document.getElementById('due_date').value = pica.due_date || '';
                            document.getElementById('catatanKaruPica').value = pica.catatan_karu || '';
                        })
                        .catch(err => console.error('Gagal mengambil data PICA:', err));
                } else {
                    console.warn('hasil_id tidak ditemukan, data PICA tidak diambil.');
                }
            })
            .catch(err => {
                console.error('Gagal mengambil data KPI:', err);
                alert('Gagal mengambil data KPI.');
            });
    }
    // Fungsi untuk aktif/nonaktifkan form PICA berdasarkan nilai
    function handlePicaActivation(nilai) {
        const picaTab = document.getElementById('pica-tab');
        const picaForm = document.getElementById('formPica');

        const disable = nilai === null || nilai >= 100;

        picaTab.classList.toggle('disabled', disable);
        picaTab.disabled = disable;

        picaForm.querySelectorAll('input, textarea, button').forEach(el => {
            el.disabled = disable;
        });
    }

    // Fungsi submit form gabungan KPI + PICA
    function submitCombinedForm() {
        const hasil = parseFloat(document.getElementById('inputHasil').value);
        const kpiForm = document.getElementById('formAktual');
        const picaForm = document.getElementById('formPica');

        if (isNaN(hasil)) {
            alert('Nilai KPI tidak valid!');
            return;
        }

        if (hasil < 100) {
            const formData = new FormData(kpiForm);

            // Tambahkan data PICA
            formData.append('problem_identification', document.getElementById('problem_identification').value);
            formData.append('corrective_action', document.getElementById('corrective_action').value);
            formData.append('due_date', document.getElementById('due_date').value);
            formData.append('pic', document.getElementById('pic').value);

            // Submit ke endpoint gabungan
            fetch("<?= base_url('kinerja/update_hasil') ?>", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'  // <--- Tambahkan ini
                }
            })
                .then(res => res.text())
                .then(text => {
                    try {
                        const data = JSON.parse(text);
                        if (data.status === 'ok') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: data.message,
                                timer: 4000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: data.message || 'Terjadi kesalahan saat menyimpan data',
                            });
                        }

                    } catch (e) {
                        console.error('Respon bukan JSON:\n', text);
                        alert('Terjadi kesalahan: Respon server tidak valid');
                    }
                })
                .catch(err => {
                    console.error('Fetch gagal:', err);
                    alert('Terjadi kesalahan saat mengirim data');
                });

        } else {
            // Jika hasil >= 100 hanya kirim KPI
            kpiForm.submit();
        }
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
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.05);
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
        cursor: pointer;
        /* Tambahkan baris ini */
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

    .nav-link.disabled {
        pointer-events: none;
        opacity: 0.5;
    }
</style>

<?php $this->endSection(); ?>