<?php $session = \Config\Services::session(); ?>
<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>

<div class="card-header">
    <h3 class="card-title">Hallo <b><?= esc($session->nama); ?></b>, Selamat datang</h3>
</div>

<div class="card-body">
    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th style="width: 15%;">Indikator</th>
                <th style="width: 10%;">Kode KPI</th>
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
                    <td colspan="10" class="text-center">Tidak ada data</td>
                </tr>
            <?php else: ?>
                <?php foreach ($data_kinerja as $item): ?>
                    <?php
                    $allowed_levels = explode(',', $item['level_user']);
                    if (!in_array($level_akses, $allowed_levels))
                        continue;

                    $hasilAktual = $item['hasil_aktual'] ?? ''; // data dari hasil_kinerja
                    ?>
                    <tr>
                        <td><?= esc($item['indikator']) ?></td>
                        <td><?= esc($item['kode_kpi']) ?></td>
                        <td><?= esc($item['formula']) ?></td>
                        <td><?= esc($item['sumber_data']) ?></td>
                        <td><?= esc($item['periode_assesment']) ?></td>
                        <td><?= esc($item['bobot']) ?></td>
                        <td><?= esc($item['target']) ?></td>
                        <td><?= esc($item['deskripsi_target']) ?></td>

                        <td contenteditable="true" class="editable" data-id="<?= $item['id'] ?>" data-field="hasil">
                            <?= esc($hasilAktual) ?>
                        </td>

                        <td class="text-center">
                            <a href="<?= base_url('admin/manekinerja/lihat_hasil/' . $item['id']) ?>"
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

            fetch("<?= base_url('ekinerja/ajax_update_field') ?>", {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
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

    td.editable {
        cursor: text;
    }

    td.editable:focus {
        outline: 2px solid #00aaff;
    }
</style>

<?php $this->endSection(); ?>