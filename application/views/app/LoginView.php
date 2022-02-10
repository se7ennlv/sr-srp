<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SRP</title>

    <!-- css core -->
    <link href="<?= base_url() . "assets/"; ?>vendor/fontawesome/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url() . "assets/"; ?>css/sb-admin-2.css?v=<?= date('H:i:s') ?>" rel="stylesheet">
    <link href="<?= base_url() . "assets/"; ?>vendor/smoke/css/smoke.min.css" rel="stylesheet">
    <link href="<?= base_url() . "assets/"; ?>css/global-style.css?v=<?= date('His') ?>" rel="stylesheet">

    <!-- js core -->
    <script src="<?= base_url() . "assets/"; ?>vendor/jquery/jquery.min.js"></script>
    <script src="<?= base_url() . "assets/"; ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url() . "assets/"; ?>vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="<?= base_url() . "assets/"; ?>js/sb-admin-2.min.js"></script>
    <script src="<?= base_url() . "assets/"; ?>vendor/smoke/js/smoke.min.js"></script>

    <script src="<?= base_url() . "assets/"; ?>js/global-script.js?v=<?= date('His') ?>"></script>
</head>

<body class="init-page">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9 mt-5">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row" style="background-image: linear-gradient(to top, #dfe9f3 0%, white 100%);">
                            <!-- <div class="col-lg-6 d-none d-lg-block bg-login-image my-auto"></div> -->
                            <div class="col-lg-6 d-none d-lg-block m-auto">
                                <p class="text-center">
                                    <i class="fas fa-gifts fa-10x text-info"></i>
                                </p>
                            </div>

                            <div class="col-lg-6">
                                <div class="p-5">
                                    <img src="<?= base_url(); ?>/files/logo.png" alt="Logo" class="rounded mx-auto d-block img-logo" />
                                    <div class="text-center">
                                        <h1 class="h4 mb-4 text-primary"><strong>SRP</strong></h1>
                                    </div>
                                    <form class="user" method="POST" autocomplete="off" novalidate="off">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" name="Username" id="Username" placeholder="Enter Your Empolyee ID (20xxxx)" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" name="Password" id="Password" placeholder="Enter Password" required>
                                        </div>
                                        <hr>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">Login</button>

                                        <hr>

                                        <div class="text-center">
                                            <a class="small">Version 1.0.2 | Last update (03, Jan 2020) </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>


    <script>
        $(document).ready(function() {
            $('form').on('submit', function(e) {
                e.preventDefault();

                if ($(this).smkValidate()) {
                    var formData = $(this).serialize();

                    $.ajax({
                        type: 'POST',
                        url: '<?= base_url('AppController/ExecuteLogin'); ?>',
                        data: formData
                    }).done(function(data) {
                        if (data.status == 'success') {
                            smkAlert(data.message, data.status);
                            window.location = '<?= site_url('InitController'); ?>';
                        } else {
                            smkAlert(data.message, data.status);
                        }
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        smkAlert('Something went wrong, please contact IT', 'danger');
                    });
                }
            });
        });
    </script>

</body>

</html>