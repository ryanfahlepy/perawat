<?php $session = \Config\Services::session(); ?>

<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (session()->getFlashdata('pesan')): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= session()->getFlashdata('pesan'); ?>',
            timer: 2000,
            showConfirmButton: false
        });
    </script>
<?php elseif (session()->getFlashdata('error')): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: '<?= session('error') ?>',
            timer: 2000,
            showConfirmButton: false
        });
    </script>
<?php endif; ?>
<style>
    .profile-container {
        position: relative;
        width: 125px;
        margin: 0 auto;
        cursor: pointer;
    }

    .profile-container img {
        width: 125px;
        height: 125px;
        object-fit: cover;
        border-radius: 50%;
    }

    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 125px;
        height: 125px;
        background: rgba(255, 255, 255, 0.5);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: 0.3s ease-in-out;
        color: #000;
        font-weight: bold;
        text-align: center;
        pointer-events: none;
    }

    .profile-container:hover .overlay {
        opacity: 1;
        pointer-events: auto;
    }

    .file-input {
        display: none;
    }

    #btn-simpan-foto {
        display: none;
        margin-top: 10px;
    }
</style>
<div class="card-body">
    <div class="text-center">
        <form action="<?= base_url('profil/update_foto') ?>" method="post" enctype="multipart/form-data"
            id="form-upload-foto">
            <?= csrf_field() ?>
            <div class="profile-container" onclick="document.getElementById('fotoProfil').click()">
                <img id="preview-foto" src="/assets/dist/img/user/<?= $session->photo ?>" alt="Foto Profil">
                <div class="overlay">Ganti<br>Foto Profil</div>
            </div>
            <input type="file" name="foto" id="fotoProfil" class="file-input" accept="image/*">
            <button type="submit" class="btn btn-success" id="btn-simpan-foto">Simpan</button>
        </form>
    </div>

    <h3 class="mt-5 profil-username text-center"><?= $session->nama; ?></h3>


    <div class="text-center mt-4">
        <a href="/admin/manuser/hal_resset_psswrd" class="btn btn-warning text-white">
            Ganti Password
        </a>
    </div>
    <a href="#" class="btn btn-success btn-show-data-diri mt-3">
        <i class="far fa-sticky-note mr-2"></i>Tampilkan Data Diri
    </a>

    <form action="<?= base_url('profil/update') ?>" method="post">
        <?= csrf_field() ?>
        <div class="tampil mt-4" style="display: none;">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control" value="<?= esc($user->nama) ?>" disabled>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Pendidikan Terakhir</label>
                    <input type="text" name="pendidikan_terakhir" class="form-control"
                        value="<?= esc($user->pendidikan_terakhir) ?>" disabled>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" value="<?= esc($user->username) ?>"
                        disabled>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control" disabled>
                        <option value="Laki-laki" <?= $user->jenis_kelamin == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki
                        </option>
                        <option value="Perempuan" <?= $user->jenis_kelamin == 'Perempuan' ? 'selected' : '' ?>>Perempuan
                        </option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Tahun Lulus</label>
                    <input type="text" name="tahun_lulus" class="form-control" value="<?= esc($user->tahun_lulus) ?>"
                        disabled>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="<?= esc($user->email) ?>" disabled>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control"
                        value="<?= esc($user->tanggal_lahir) ?>" disabled>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Status Menikah</label>
                    <select name="status_kawin" class="form-control" disabled>
                        <option value="Laki-laki" <?= $user->status_kawin == 'Belum Menikah' ? 'selected' : '' ?>>Belum
                            Menikah</option>
                        <option value="Perempuan" <?= $user->status_kawin == 'Sudah Menikah' ? 'selected' : '' ?>>Sudah
                            Menikah</option>
                    </select>
                </div>



                <div class="col-md-4 mb-3">
                    <label>No. HP</label>
                    <input type="text" name="no_hp" class="form-control" value="<?= esc($user->no_hp) ?>" disabled>
                </div>
            </div>
            <button type="button" class="btn btn-primary btn-edit">Edit</button>
            <button type="submit" class="btn btn-success btn-simpan d-none">Simpan</button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('.btn-show-data-diri').on('click', function (e) {
            e.preventDefault();
            $('.tampil').slideToggle();
        });

        $('.btn-edit').click(function () {
            $('.tampil input, .tampil select').removeAttr('disabled');
            $(this).addClass('d-none');
            $('.btn-simpan').removeClass('d-none');
        });
    });
</script>
<script>
    document.getElementById('fotoProfil').addEventListener('change', function (e) {
        const file = e.target.files[0];
        const preview = document.getElementById('preview-foto');
        const simpanBtn = document.getElementById('btn-simpan-foto');

        if (file) {
            const reader = new FileReader();
            reader.onload = function (event) {
                preview.src = event.target.result;
                simpanBtn.style.display = 'inline-block';
            };
            reader.readAsDataURL(file);
        }
    });
</script>

<?php $this->endSection(); ?>