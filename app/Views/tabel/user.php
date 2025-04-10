<table class="table table-bordered table-striped" id="example1">
    <thead>
        <tr class="text-center">
            <th>No</th>
            <th>Photo</th>
            <th>Nama Pengguna</th>
            <th>Username</th>
            <th>Level Pengguna</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1;
        foreach ($data as $dt) : ?>
            <tr>
                <td><?= $no++; ?></td>
                <td>
                    <div class="image">
                        <img src="/assets/dist/img/user/<?= $dt->photo; ?>" class="img-circle" width="50" alt="User Image">
                    </div>
                </td>
                <td><?= $dt->nama; ?></td>
                <td><?= $dt->username; ?></td>
                <td><?= $dt->nama_level; ?></td>
                <?php
                $color = ($dt->status == 'Aktif') ? 'bg-info' : 'bg-danger' ?>
                <td>
                    <badge class="badge <?= $color; ?>"><?= $dt->status; ?></badge>
                </td>
                <?php
                if ($dt->status == 'Aktif') {
                    $tooltip = 'nonaktifkan';
                    $aksi = 'nonaktif';
                    $icon = 'fas fa-exclamation-triangle';
                    $color = 'btn-warning';
                } else {
                    $tooltip = 'aktifkan';
                    $aksi = 'aktif';
                    $icon = 'fas fa-check-circle';
                    $color = 'btn-success';
                }
                ?>
                <td>
                    <a href="#" type="button" class="btn btn-sm btn-primary btn-edituser" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit" data-id="<?= $dt->id; ?>">
                        <i class="far fa-fw fa-edit"></i>
                    </a>
                    <a href="#" class="btn btn-sm ml-2 <?= $color; ?> btn-user<?= $tooltip ?>" data-bs-toggle="tooltip" data-bs-placement="top" title=<?= $tooltip; ?> data-id="<?= $dt->id ?>">
                        <i class="<?= $icon; ?>"></i>
                    </a>
                    <a href="#" class="btn btn-sm ml-2 btn-danger btn-hapususer" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus" data-id="<?= $dt->id ?>">
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