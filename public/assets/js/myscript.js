    function tampil_tabel_level() {
        $.ajax({
            url: "/admin/manlevel/dtlevel",
            dataType: "json",
            beforeSend: function(f) {
                $(".tabel-level").html('Load tabel ....... !');
            },
            success: function(responds) {
                $(".tabel-level").html(responds)
            }
        });
    }

    function tampil_tabel_user() {
        $.ajax({
            url: "/admin/manuser/dtuser",
            dataType: "json",
            beforeSend: function(f) {
                $(".tampil").html('Load tabel ....... !');
            },
            success: function(responds) {
                $(".tampil").html(responds)
            }
        });
    }

    function tampil_tabel_menu() {
        $.ajax({
            url: "/admin/manmenu/dtmenu",
            dataType: "json",
            beforeSend: function(f) {
                $(".tabel-menu").html('Load tabel ....... !');
            },
            success: function(responds) {
                $(".tampil").html(responds)
            }
        });
    }

    function form_reset_password() {
        $.ajax({
            url: "/admin/manuser/freset_password",
            dataType: "json",
            success: function(responds) {
                $(".form-resset").html(responds)
            }
        });
    }

    function tampil_notif_pss() {
        $.ajax({
            url: "/admin/manuser/notif_reset_password",
            dataType: "json",
            success: function(responds) {
                $(".form-resset").html(responds)
            }
        });
    }

    $(document).ready(function() {
        tampil_tabel_level();

        //level
        $(document).on("click", ".btn-addlevel", function() {
            $.ajax({
                url: "/admin/manlevel/form_tambah",
                dataType: "json",
                success: function(responds) {
                    $(".form-modal").html(responds)
                    $(".modal").modal("toggle")
                }
            });
        })
        $(document).on("submit", "#form_add", function(e) {
            e.preventDefault()
            $.ajax({
                url: $(this).attr("action"),
                data: $(this).serialize(),
                dataType: "json",
                success: function(responds) {
                    if (responds.status) {
                        toastr.success(responds.psn)
                        $(".modal").modal("toggle")
                        tampil_tabel_level()
                    } else {
                        toastr.error('Data belum valid, ulangi lagi')
                        $.each(responds.errors, function(key, value) {
                            $('[name="' + key + '"]').addClass('is-invalid')
                            $('[name="' + key + '"]').next().text(value)
                            if (value == "") {
                                $('[name="' + key + '"]').removeClass('is-invalid')
                                $('[name="' + key + '"]').addClass('is-valid')
                            }
                        })
                    }
                }
            });
        })
        $(document).on("click", ".btn-editlevel", function() {
            var id_level = $(this).data("id")
            var konfirm = confirm('Yakin akan mengubah data level ini ?')
            if (konfirm) {
                $.ajax({
                    url: "/admin/manlevel/form_edit",
                    data: {
                        id: id_level
                    },
                    dataType: "json",
                    success: function(responds) {
                        $(".form-modal").html(responds)
                        $(".modal").modal("toggle")
                    }
                });
            }
        })
        $(document).on("submit", "#form_edit", function(e) {
            e.preventDefault()
            $.ajax({
                url: $(this).attr("action"),
                data: $(this).serialize(),
                dataType: "json",
                success: function(responds) {
                    if (responds.status) {
                        toastr.success(responds.psn)
                        $(".modal").modal("toggle")
                        tampil_tabel_level()
                    } else {
                        toastr.error('Data belum valid, ulangi lagi')
                        $.each(responds.errors, function(key, value) {
                            $('[name="' + key + '"]').addClass('is-invalid')
                            $('[name="' + key + '"]').next().text(value)
                            if (value == "") {
                                $('[name="' + key + '"]').removeClass('is-invalid')
                                $('[name="' + key + '"]').addClass('is-valid')
                            }
                        })
                    }
                }
            });
        })
        $(document).on("click", ".btn-hapuslevel", function() {
            var id_level = $(this).data("id")
            var konfirm = confirm('Pastikan sudah tidak ada user/menu dengan level ini, Yakin akan menghapus level ini ?')
            $.ajax({
                url: "/admin/manlevel/hapus",
                data: {
                    id: id_level
                },
                dataType: "json",
                success: function(responds) {
                    if (responds.status) {
                        toastr.success(responds.psn)
                        tampil_tabel_level()
                    } else {
                        alert('Level gagal dihapus')
                    }
                }
            });
        })
        $("#form_add input").on("keyup", function() {
            alert('ok')
            // $(this).removeClass('is-invalid is-valid')
        })
        $("#form_add input").on("click", function() {
            alert('ok')
            //$(this).removeClass('is-invalid is-valid')
        })

        //user
        $(document).on("click", ".tbl-user", function() {
            tampil_tabel_user()
        })
        $(document).on("click", ".btn-adduser", function() {
            $.ajax({
                url: "/admin/manuser/form_tambah",
                dataType: "json",
                success: function(responds) {
                    $('.tampil').html(responds)
                }
            });
        })
        $(document).on("submit", "#adduser", function(e) {
            e.preventDefault()
            var data_user = new FormData(this);
            $.ajax({
                method: "post",
                url: $(this).attr("action"),
                data: data_user,
                enctype: "multipart/form-data",
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(responds) {
                    if (responds.status) {
                        toastr.success(responds.psn)
                        tampil_tabel_user()
                    } else {
                        toastr.error('Data belum valid, ulangi lagi')
                        $.each(responds.errors, function(key, value) {
                            $('[name="' + key + '"]').addClass('is-invalid')
                            $('[name="' + key + '"]').next().text(value)
                            if (value == "") {
                                $('[name="' + key + '"]').removeClass('is-invalid')
                                $('[name="' + key + '"]').addClass('is-valid')
                            }
                        })
                    }
                }
            });
        })
        $(document).on("click", ".btn-edituser", function() {
            var id_user = $(this).data("id")
            var konfirm = confirm('Yakin akan mengubah data user ini ?')
            if (konfirm) {
                $.ajax({
                    url: "/admin/manuser/form_edit",
                    data: {
                        id: id_user
                    },
                    dataType: "json",
                    success: function(responds) {
                        $('.tampil').html(responds)
                    }
                });
            }
        })
        $(document).on("submit", "#edituser", function(e) {
            e.preventDefault()
            var data_user = new FormData(this);
            $.ajax({
                method: "post",
                url: $(this).attr("action"),
                data: data_user,
                enctype: "multipart/form-data",
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(responds) {
                    if (responds.status) {
                        toastr.success(responds.psn)
                        tampil_tabel_user()
                    } else {
                        toastr.error('Data belum valid, ulangi lagi')
                        $.each(responds.errors, function(key, value) {
                            $('[name="' + key + '"]').addClass('is-invalid')
                            $('[name="' + key + '"]').next().text(value)
                            if (value == "") {
                                $('[name="' + key + '"]').removeClass('is-invalid')
                                $('[name="' + key + '"]').addClass('is-valid')
                            }
                        })
                    }
                }
            });
        })
        $(document).on("click", ".btn-usernonaktifkan", function() {
            var id_user = $(this).data("id")
            var konfirm = confirm('Yakin akan mengubah status user ini ?')
            if (konfirm) {
                $.ajax({
                    url: "/admin/manuser/nonaktif",
                    data: {
                        id: id_user
                    },
                    dataType: "json",
                    success: function(responds) {
                        if (responds.status) {
                            toastr.success(responds.psn)
                            tampil_tabel_user()
                        } else {
                            alert('Data gagal dinonaktifkan')
                        }
                    }
                });
            }
        })
        $(document).on("click", ".btn-useraktifkan", function() {
            var id_user = $(this).data("id")
            var konfirm = confirm('Yakin akan mengubah status user ini ?')
            if (konfirm) {
                $.ajax({
                    url: "/admin/manuser/aktif",
                    data: {
                        id: id_user
                    },
                    dataType: "json",
                    success: function(responds) {
                        if (responds.status) {
                            toastr.success(responds.psn)
                            tampil_tabel_user()
                        } else {
                            alert('Data gagal diaktifkan')
                        }
                    }
                });
            }
        })
        $(document).on("click", ".btn-hapususer", function() {
            //toastr.success('Maaf untuk demo tidak bisa jalankan fungsi delete')
            var id_user = $(this).data("id")
            var konfirm = confirm('Yakin akan menghapus user ini ?')
            if (konfirm) {
                $.ajax({
                    url: "/admin/manuser/hapus",
                    data: {
                        id: id_user
                    },
                    dataType: "json",
                    success: function(responds) {
                        if (responds.status) {
                            toastr.success(responds.psn)
                            tampil_tabel_user()
                        } else {
                            alert('Data gagal dihapus')
                        }
                    }
                });
            }
        })

        // menu
        $(document).on("click", ".tbl-menu", function() {
            tampil_tabel_menu()
        })
        $(document).on("click", ".btn-addmenu", function() {
            $.ajax({
                url: "/admin/manmenu/form_tambah",
                dataType: "json",
                success: function(responds) {
                    $('.tampil').html(responds)
                }
            });
        })
        $(document).on("submit", "#menu", function(e) {
            e.preventDefault()
            $.ajax({
                url: $(this).attr("action"),
                data: $(this).serialize(),
                dataType: "json",
                success: function(responds) {
                    if (responds.status) {
                        toastr.success(responds.psn)
                        tampil_tabel_menu()
                    } else {
                        toastr.error('Data belum valid, ulangi lagi')
                        $.each(responds.errors, function(key, value) {
                            $('[name="' + key + '"]').addClass('is-invalid')
                            $('[name="' + key + '"]').next().text(value)
                            if (value == "") {
                                $('[name="' + key + '"]').removeClass('is-invalid')
                                $('[name="' + key + '"]').addClass('is-valid')
                            }
                        })
                    }
                }
            });
        })
        $(document).on("click", ".btn-editmenu", function() {
            var id_menu = $(this).data("id")
            var konfirm = confirm('Yakin akan mengubah menu ini ?')
            if (konfirm) {
                $.ajax({
                    url: "/admin/manmenu/form_edit",
                    data: {
                        id: id_menu
                    },
                    dataType: "json",
                    success: function(responds) {
                        $('.tampil').html(responds)
                    }
                });
            }
        })
        $(document).on("submit", "#editmenu", function(e) {
            e.preventDefault()
            $.ajax({
                url: $(this).attr("action"),
                data: $(this).serialize(),
                dataType: "json",
                success: function(responds) {
                    if (responds.status) {
                        toastr.success(responds.psn)
                        tampil_tabel_menu()
                    } else {
                        toastr.error('Data belum valid, ulangi lagi')
                        $.each(responds.errors, function(key, value) {
                            $('[name="' + key + '"]').addClass('is-invalid')
                            $('[name="' + key + '"]').next().text(value)
                            if (value == "") {
                                $('[name="' + key + '"]').removeClass('is-invalid')
                                $('[name="' + key + '"]').addClass('is-valid')
                            }
                        })
                    }
                }
            });
        })
        $(document).on("click", ".btn-hapusmenu", function() {
            //toastr.success('Maaf untuk demo tidak bisa jalankan fungsi delete')
            var id_menu = $(this).data("id")
            var konfirm = confirm('Yakin akan menghapus menu ini ?')
            if (konfirm) {
                $.ajax({
                    url: "/admin/manmenu/hapus",
                    data: {
                        id: id_menu
                    },
                    dataType: "json",
                    success: function(responds) {
                        if (responds.status) {
                            toastr.success(responds.psn)
                            tampil_tabel_menu()
                        } else {
                            alert('Data gagal dihapus')
                        }
                    }
                });
            }
        })
        $(document).on("click", ".btn-nonaktifkan", function() {
            var id_menu = $(this).data("id")
            var konfirm = confirm('Yakin akan menonaktifkan menu ini ?')
            if (konfirm) {
                $.ajax({
                    url: "/admin/manmenu/nonaktif",
                    data: {
                        id: id_menu
                    },
                    dataType: "json",
                    success: function(responds) {
                        if (responds.status) {
                            toastr.success(responds.psn)
                            tampil_tabel_menu()
                        } else {
                            alert('Data gagal dinonaktifkan')
                        }
                    }
                });
            }
        })
        $(document).on("click", ".btn-aktifkan", function() {
            var id_menu = $(this).data("id")
            var konfirm = confirm('Yakin akan mengaktifkan menu ini ?')
            if (konfirm) {
                $.ajax({
                    url: "/admin/manmenu/aktif",
                    data: {
                        id: id_menu
                    },
                    dataType: "json",
                    success: function(responds) {
                        if (responds.status) {
                            toastr.success(responds.psn)
                            tampil_tabel_menu()
                        } else {
                            alert('Data gagal diaktifkan')
                        }
                    }
                });
            }
        })

        //reset password
        form_reset_password()
        $(document).on("submit", "#rpassword", function(e) {
            // e.preventDefault()
            // alert("Maaf untuk demo tidak bisa jalankan fitur reset password")
            e.preventDefault()
            $.ajax({
                url: $(this).attr("action"),
                data: $(this).serialize(),
                dataType: "json",
                success: function(responds) {
                    if (responds.status) {
                        if (responds.res_pss) {
                            toastr.success(responds.psn)
                            tampil_notif_pss()
                        } else {
                            toastr.error(responds.psn)
                        }
                    } else {
                        toastr.error('Data belum valid , ualngi lagi')
                        $.each(responds.errors, function(key, value) {
                            $('[name="' + key + '"]').addClass('is-invalid')
                            $('[name="' + key + '"]').next().text(value)
                            if (value == "") {
                                $('[name="' + key + '"]').removeClass('is-invalid')
                                $('[name="' + key + '"]').addClass('is-valid')
                            }
                        })
                    }
                }
            });
        })
    });
