<?php
// var_dump($dataHasil);
// exit();
$session = \Config\Services::session();
$level = $session->level; // Pastikan session ini menyimpan level
?>
<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>

<div class="card-header">
    <h3 class="card-title">Hallo <b><?= $session->nama; ?></b>, Selamat datang</h3>
</div>

<div class="card-body">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="text-center" style="width: 50px;">No</th>
                <th class="text-center">Kompetensi</th>
                <th class="text-center">Mampu</th>
                <th class="text-center">Didampingi</th>
                <th class="text-center">Tidak Mampu</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $lastKategori = null;
            foreach ($dataPrapk as $row):
                $kategori = $row['kategori'];
                $no = $row['no'];
                $kompetensi = $row['kompetensi'];
                $canCheck = in_array($level, [1, 2, 3]);

                if (!empty($kategori) && $kategori !== $lastKategori):
                    ?>
                    <tr>
                        <td colspan="5" style="background-color: #f0f0f0; font-weight: bold;">
                            <?= esc($kategori); ?>
                        </td>
                    </tr>
                    <?php
                    $lastKategori = $kategori;
                endif;
                ?>
                <tr>
                    <td><?= esc($no); ?></td>
                    <td><?= esc($kompetensi); ?></td>
                    <td class="text-center">
                        <input type="checkbox" name="nilai_<?= $row['id']; ?>[]" value="1" class="nilai-checkbox"
                            data-id="<?= $row['id']; ?>" <?= isset($dataHasil[$row['id']]) && $dataHasil[$row['id']]['nilai_id'] == 1 ? 'checked' : ''; ?>>
                    </td>
                    <td class="text-center">
                        <input type="checkbox" name="nilai_<?= $row['id']; ?>[]" value="2" class="nilai-checkbox"
                            data-id="<?= $row['id']; ?>" <?= isset($dataHasil[$row['id']]) && $dataHasil[$row['id']]['nilai_id'] == 2 ? 'checked' : ''; ?>>
                    </td>
                    <td class="text-center">
                        <input type="checkbox" name="nilai_<?= $row['id']; ?>[]" value="3" class="nilai-checkbox"
                            data-id="<?= $row['id']; ?>" <?= isset($dataHasil[$row['id']]) && $dataHasil[$row['id']]['nilai_id'] == 3 ? 'checked' : ''; ?>>
                    </td>


                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('.nilai-checkbox');

        checkboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                const prapkId = this.getAttribute('data-id');
                const nilai = this.value;

                // Deselect semua checkbox lain untuk kompetensi ini
                document.querySelectorAll(`.nilai-checkbox[data-id="${prapkId}"]`).forEach(cb => {
                    if (cb !== this) {
                        cb.checked = false;
                    }
                });

                let nilaiToSend = this.checked ? nilai : 0;

                fetch("<?= site_url('mentoring/simpan') ?>", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: `prapk_id=${prapkId}&nilai_id=${nilaiToSend}`
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'ok') {
                            console.log('Disimpan');
                        } else {
                            alert('Gagal menyimpan');
                        }
                    });
            });
        });
    });
</script>


<?php $this->endSection(); ?>