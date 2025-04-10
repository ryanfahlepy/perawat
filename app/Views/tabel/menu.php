<table class="table table-bordered table-striped" id="example1">
    <thead>
        <tr class="text-center">
            <th>Nama Menu</th>
            <th>url</th>
            <!-- <th>Icon</th> -->
            <th>Level Pengguna</th>
            <th>Urutan</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $dt) : ?>
            <tr>
                <td><?= $dt->nama; ?></td>
                <td><?= $dt->url; ?></td>
                <!-- <td><?= $dt->icon; ?></td> -->
                <td><?= $dt->nama_level; ?></td>
                <td class="text-center"><?= $dt->urutan; ?></td>
                <?php
                $color = ($dt->aktif == '1') ? 'bg-info' : 'bg-danger' ?>
                <td class="text-center">
                    <badge class="badge <?= $color; ?>"><?php echo ($dt->aktif == '1') ? 'Aktif' : 'Nonaktif'; ?></badge>
                </td>
                <?php
                if ($dt->aktif == '1') {
                    $tooltip = 'Nonaktifkan';
                    $aksi = 'btn-nonaktifkan';
                    $icon = 'fas fa-exclamation-triangle';
                    $color = 'btn-warning';
                } else {
                    $tooltip = 'Aktifkan';
                    $aksi = 'btn-aktifkan';
                    $icon = 'fas fa-check-circle';
                    $color = 'btn-success';
                }
                ?>
                <td class="text-center">
                    <a href="#" type="button" class="btn btn-sm btn-primary btn-editmenu" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit" data-id="<?= $dt->id ?>">
                        <i class="far fa-fw fa-edit"></i>
                    </a>
                    <a href="#" type="button" class="btn btn-sm ml-2 <?= $color; ?> <?= $aksi; ?>" data-bs-toggle="tooltip" data-bs-placement="top" title=<?= $tooltip; ?> data-id="<?= $dt->id ?>">
                        <i class="<?= $icon; ?>"></i>
                    </a>
                    <a href="#" type="button" class="btn btn-sm ml-2 btn-danger btn-hapusmenu" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus" data-id="<?= $dt->id ?>">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<!-- Data Table -->
<script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "autoWidth": true,
        });
    });
</script>