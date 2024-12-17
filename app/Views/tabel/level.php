<table class="table table-bordered table-striped" id="example1">
    <thead>
        <tr class="text-center">
            <th>No</th>
            <th>Nama Level</th>
            <th>Keterangan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1;
        foreach ($data as $dt) : ?>
            <tr>
                <td><?= $no; ?></td>
                <td><?= $dt->nama_level; ?></td>
                <td><?= $dt->keterangan; ?></td>
                <td>
                    <a href="#" type="button" class="btn btn-sm btn-primary btn-editlevel" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit" data-id="<?= $dt->id ?>">
                        <i class="far fa-fw fa-edit"></i>
                    </a>
                    <a href="#" type="button" class="btn btn-sm ml-2 btn-danger btn-hapuslevel" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus" data-id="<?= $dt->id ?>">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
        <?php $no++;
        endforeach; ?>
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