<?php

$session = \Config\Services::session();
$level = $session->level;

?>

<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<div class="card-body">
    <div class="mb-3">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahKinerja">+ Tambah
            Kinerja</button>
    </div>
    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <thead>
            <tr>

                <th style="width: 15%;">Indikator</th>
                <th style="width: 10%;">Kode KPI</th>
                <th style="width: 12%;">Tingkat Perawat</th>
                <th style="width: 10%;">Formula</th>
                <th style="width: 10%;">Sumber Data</th>
                <th style="width: 8%;">Periode Assessment</th>
                <th style="width: 5%;">Bobot</th>
                <th style="width: 7%;">Target</th>
                <th style="width: 10%;">Deskripsi Target</th>
                <th style="width: 6%;">Aksi</th>

            </tr>
        </thead>
        <tbody>
            <?php if (empty($data_kinerja)): ?>
                <tr>
                    <td colspan="12" class="text-center">Tidak ada data</td>
                </tr>
            <?php else: ?>
                <?php foreach ($data_kinerja as $item): ?>
                    <tr>
                        <td contenteditable="true" class="editable" data-id="<?= $item['id'] ?>" data-field="indikator">
                            <?= esc($item['indikator']) ?>
                        </td>
                        <td contenteditable="true" class="editable" data-id="<?= $item['id'] ?>" data-field="kode_kpi">
                            <?= esc($item['kode_kpi']) ?>
                        </td>
                        <td>
                            <?php foreach ($user_levels as $lvl): ?>
                                <div class="form-check">
                                    <input class="form-check-input level-checkbox" type="checkbox" data-id="<?= $item['id'] ?>"
                                        data-level="<?= $lvl->id ?>" <?= (isset($item['level_user']) && in_array($lvl->id, explode(',', $item['level_user']))) ? 'checked' : '' ?>>
                                    <label class="form-check-label"><?= esc($lvl->nama_level) ?></label>
                                </div>
                            <?php endforeach; ?>
                        </td>
                        <td contenteditable="true" class="editable" data-id="<?= $item['id'] ?>" data-field="formula">
                            <?= esc($item['formula']) ?>
                        </td>
                        <td contenteditable="true" class="editable" data-id="<?= $item['id'] ?>" data-field="sumber_data">
                            <?= esc($item['sumber_data']) ?>
                        </td>
                        <td contenteditable="true" class="editable" data-id="<?= $item['id'] ?>" data-field="periode_assesment">
                            <?= esc($item['periode_assesment']) ?>
                        </td>
                        <td contenteditable="true" class="editable" data-id="<?= $item['id'] ?>" data-field="bobot">
                            <?= esc($item['bobot']) ?>%
                        </td>
                        <td contenteditable="true" class="editable" data-id="<?= $item['id'] ?>" data-field="target">
                            <?= esc($item['target']) ?>
                        </td>
                        <td contenteditable="true" class="editable" data-id="<?= $item['id'] ?>" data-field="deskripsi_target">
                            <?= esc($item['deskripsi_target']) ?>
                        </td>
                        <td class="text-center">
                            <a href="<?= base_url('admin/mankinerja/lihat_hasil/' . $item['id']) ?>"
                                class="btn btn-sm btn-info" title="Lihat Hasil">
                                <i class="fas fa-eye text-white"></i>
                            </a>
                            <?php if ($level_akses == 1): ?>
                                <button type="button" class="btn btn-sm btn-danger btn-hapus" data-id="<?= $item['id'] ?>"
                                    title="Hapus Data">
                                    <i class="fas fa-trash-alt"></i>
                                </button>

                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>


<!-- Modal Tambah Kinerja -->
<div class="modal fade" id="modalTambahKinerja" tabindex="-1" aria-labelledby="modalTambahKinerjaLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formTambahKinerja" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahKinerjaLabel">Tambah Kinerja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Indikator</label>
                        <input type="text" name="indikator" class="form-control" placeholder="Indikator" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kode KPI</label>
                        <input type="text" name="kode_kpi" class="form-control" placeholder="Kode KPI" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Formula</label>
                        <input type="text" name="formula" class="form-control" placeholder="Formula" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sumber Data</label>
                        <input type="text" name="sumber_data" class="form-control" placeholder="Sumber Data" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Periode Assessment</label>
                        <select name="periode_assesment" class="form-select" required>
                            <option value="">-- Pilih Periode --</option>
                            <option value="Bulanan">Bulanan</option>
                            <option value="Tahunan">Tahunan</option>
                        </select>
                    </div>


                    <div class="mb-3">
                        <label class="form-label">Bobot (%)</label>
                        <div class="input-group">
                            <input type="number" step="0.01" min="0" max="100" name="bobot" class="form-control"
                                placeholder="Contoh: 25" required>
                            <span class="input-group-text">%</span>
                        </div>
                    </div>


                    <div class="mb-3">
                        <label class="form-label">Target (Desimal)</label>
                        <input type="number" step="0.01" name="target" class="form-control" placeholder="Contoh: 0.75"
                            required>
                    </div>


                    <div class="mb-3">
                        <label class="form-label">Deskripsi Target</label>
                        <input type="text" name="deskripsi_target" class="form-control" placeholder="Deskripsi Target"
                            required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>

    // Inline edit save via blur
    document.querySelectorAll('.editable').forEach(function (cell) {
        cell.addEventListener('blur', function () {
            const id = this.dataset.id;
            const field = this.dataset.field;
            const value = this.innerText.trim();

            const formData = new FormData();
            formData.append('id', id);
            formData.append('field', field);
            formData.append('value', value);

            fetch("<?= base_url('admin/mankinerja/ajax_update_data_kinerja') ?>", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) {
                        Swal.fire('Gagal', data.message || 'Gagal menyimpan', 'error');
                    }
                })
                .catch(err => {
                    console.error('Update error:', err);
                    Swal.fire('Error', 'Terjadi kesalahan jaringan', 'error');
                });
        });
    });

    // Checkbox perawat update
    document.querySelectorAll('.level-checkbox').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            const id = this.dataset.id;
            const level = this.dataset.level;
            const checked = this.checked;

            const formData = new FormData();
            formData.append('id', id);
            formData.append('level_id', level);
            formData.append('checked', checked ? '1' : '0');

            fetch("<?= base_url('admin/mankinerja/ajax_update_level') ?>", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) {
                        Swal.fire('Gagal', data.message || 'Terjadi kesalahan', 'error');
                    }
                })
                .catch(err => {
                    console.error('AJAX Error:', err);
                    Swal.fire('Error', 'Terjadi kesalahan jaringan', 'error');
                });
        });
    });
</script>
<script>
    document.querySelectorAll('.btn-hapus').forEach(function (button) {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: 'Data yang dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`<?= base_url('admin/mankinerja/delete/') ?>${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: '_method=DELETE'
                    })

                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! Status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success').then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Gagal', data.message || 'Gagal menghapus data.', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Fetch Error:', error);
                            Swal.fire('Error', 'Terjadi kesalahan saat menghapus data.', 'error');
                        });
                }
            });
        });
    });
</script>



<script>
    function showCreateForm() {
        document.getElementById('createForm').style.display = 'block';
    }

    document.getElementById('formTambahKinerja').addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        // Jika bobot masih dalam format string "%", hilangkan %
        let bobot = formData.get('bobot');
        if (typeof bobot === 'string' && bobot.includes('%')) {
            formData.set('bobot', bobot.replace('%', '').trim());
        }

        fetch("<?= base_url('admin/mankinerja/create') ?>", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Sukses', 'Data berhasil ditambahkan', 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Gagal', data.message || 'Gagal menambahkan data', 'error');
                }
            })
            .catch(err => {
                console.error('AJAX Error:', err);
                Swal.fire('Error', 'Terjadi kesalahan jaringan', 'error');
            });
    });

</script>

<style>
    table {
        border-collapse: collapse;
        font-family: Arial, sans-serif;
    }

    th,
    td {
        padding: 10px;
        border: 1px solid #ddd;
        vertical-align: top;
    }

    th {

        font-weight: bold;
        text-align: center;
    }

    .form-check {
        margin-bottom: 4px;
    }

    td.editable {

        cursor: text;
    }

    td.editable:focus {
        outline: 2px solid #00aaff;
    }
</style>

<?php $this->endSection(); ?>