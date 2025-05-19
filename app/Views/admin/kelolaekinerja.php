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

<div class="card-body">
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
                <th style="width: 7%;">Hasil Aktual</th>
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
                            <?= esc($item['bobot']) ?>
                        </td>

                        <td contenteditable="true" class="editable" data-id="<?= $item['id'] ?>" data-field="target">
                            <?= esc($item['target']) ?>
                        </td>

                        <td contenteditable="true" class="editable" data-id="<?= $item['id'] ?>" data-field="deskripsi_target">
                            <?= esc($item['deskripsi_target']) ?>
                        </td>

                        <td><?= esc($item['hasil_aktual']) ?></td>

                        <td class="text-center">
                            <a href="<?= base_url('admin/manekinerja/lihat_hasil/' . $item['id']) ?>" class="btn btn-sm btn-info" title="Lihat Hasil">
    <i class="fas fa-eye text-white"></i>
</a>

                            <?php if ($level_akses == 1): ?>
                                <!-- <a href="<?= base_url('admin/manekinerja/delete/' . $item['id']) ?>" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Yakin ingin menghapus data ini?')">
                                    <i class="fas fa-trash-alt"></i>
                                </a> -->
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    // Check all checkboxes
    document.getElementById('checkAll').addEventListener('change', function () {
        let checkboxes = document.querySelectorAll('.row-check');
        for (let checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    });

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

            fetch("<?= base_url('admin/manekinerja/ajax_update_field') ?>", {
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

            fetch("<?= base_url('admin/manekinerja/ajax_update_level') ?>", {
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