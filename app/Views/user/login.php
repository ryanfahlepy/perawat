<?= $this->include('shared_page/header'); ?>

<head>
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            background-image: url('/assets/dist/img/bg.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
        }


        .wrapper-center {
            flex-direction: column; /* ⬅️ Biar elemen tersusun dari atas ke bawah */
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
    </style>

</head>

<body>
    <style>
        .title {
            color: white;
            font-size: 2rem;
            /* Ukuran default */
            text-align: center;
            font-weight: bold;
        }

        /* Jika layar lebih kecil dari 768px, ukuran font akan mengecil */
        @media (max-width: 768px) {
            .title {
                font-size: 1.2rem;
            }
        }

        /* Jika layar lebih kecil dari 480px, ukuran font akan lebih kecil lagi */
        @media (max-width: 480px) {
            .title {
                font-size: 1rem;
            }
        }
    </style>

    
    <div class="wrapper-center">
        <div class="login-box">
            <div class="login-logo">
            </div>
            <!-- /.login-logo -->
            <div class="card">
                <div class="card-body login-card-body">
                    <div class="text-center mb-4">
                        <img src="/assets/dist/img/bg.png" width="200" class="img" alt="User Image">
                    </div>
                    <form action="/login/ceklogin" method="POST">
                        <?= csrf_field(); ?>
                        <div class="input-group mb-3">
                            <input type="text" name="username"
                                class="form-control <?= ($validation->hasError('username')) ? 'is-invalid' : ''; ?>"
                                value="<?= old('username'); ?>" placeholder="Masukan username" autofocus required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                            <div class="invalid-feedback">
                                <?= $validation->getError('username'); ?>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" name="password"
                                class="form-control <?= ($validation->hasError('password')) ? 'is-invalid' : ''; ?>"
                                placeholder="Masukan Password" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            <div class="invalid-feedback">
                                <?= $validation->getError('password'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mt-3">
                                <button type="submit" class="btn btn-primary btn-block">Login</button>
                            </div>
                    </form>
                </div>
                <div class="social-auth-links text-center">
                    <small class="text-danger">Belum punya akun ? <a href="/login/register">Registrasi</a></small>
                </div>
                <!-- /.login-card-body -->
            </div>
        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="/assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/assets/dist/js/adminlte.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="/assets/plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- Toastr -->
    <script src="/assets/plugins/toastr/toastr.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if (session()->getFlashdata('pesan')): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?= session()->getFlashdata('pesan'); ?>',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        </script>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '<?= session()->getFlashdata('error'); ?>',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        </script>
    <?php endif; ?>
</body>

</html>