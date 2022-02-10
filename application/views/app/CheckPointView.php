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
    <script src="<?= base_url() . "assets/"; ?>vendor/mustache.js/mustache.min.js"></script>
    <script src="<?= base_url() . "assets/"; ?>vendor/smoke/js/smoke.min.js"></script>
    <script src="<?= base_url() . "assets/"; ?>vendor/sweetalert2/sweetalert2.js"></script>
    <script src="<?= base_url() . "assets/"; ?>js/global-script.js?v=<?= date('His') ?>"></script>

    <style>
        /* width */
        ::-webkit-scrollbar {
            width: 22px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            box-shadow: inset 0 0 5px grey;
            border-radius: 10px;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #e74a3b;
            border-radius: 10px;
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #e02d1b;
        }

        .table-money th,
        .table-money td {
            padding: 0rem;
        }
    </style>
</head>

<body id="check-point-page">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-7"></div>
            <div class="col-md-5">
                <div class="card shadow-lg border-0 my-5" style="background-image: linear-gradient(-225deg, #FFFEFF 0%, #D7FFFE 100%);">
                    <div class="card-body">
                        <div class="text-center">
                            <h1 class="h4 text-primary mb-4"><strong>SRP (Check Your Point)</strong></h1>
                        </div>
                        <form class="user mt-2" method="POST" autocomplete="off" novalidate="off">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-user" name="empId" id="empId" placeholder="Enter Your Employee ID (20xxxx)" required>
                            </div>
                        </form>

                        <hr>

                        <p class="text-center">
                            <i class="fas fa-gifts fa-10x text-info"></i>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="pointModal" class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-body m-auto">
                    <div class="table-responsive overflow-auto" style="max-height: 430px;">
                        <table class="table table-responsive table-border table-sm table-hover table-striped">
                            <thead>
                                <tr>
                                    <th colspan="2" class="text-center" style="vertical-align: middle">
                                        <img id="profile" class="img img-thumbnail" style="width: 130px; height: 140px">
                                    </th>
                                    <th colspan="3" style="vertical-align: middle">
                                        <h6><strong>Name:</strong> <span id="empName"></span></h6>
                                        <h6><strong>Position:</strong> <span id="position"></span></h6>
                                        <h5><strong>Your Point:</strong> <span id="points" class="badge badge-success"></span></h5>
                                    </th>
                                </tr>
                                <tr class="text-center bg-gray-400">
                                    <th>#</th>
                                    <th class="text-nowrap">Prize Description</th>
                                    <th class="text-nowrap">Unit</th>
                                    <th class="text-nowrap">Points</th>
                                    <th class="text-nowrap">Status</th>
                                </tr>
                            </thead>
                            <tbody id="prizeList">
                                <!-- dynamic -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            $('#empId').trigger('focus');
        });

        $(function() {
            $('form').on('submit', function(e) {
                e.preventDefault();

                if ($(this).smkValidate()) {
                    var rawStr = $('input[name=empId]').val();
                    var empCode = DataFilter(rawStr);

                    $.ajax({
                        type: 'POST',
                        url: "<?= site_url('ReportController/CheckPointSummary'); ?>",
                        data: {
                            empCode: empCode
                        }
                    }).done(function(data) {
                        var imgUrl = 'http://172.16.98.171/srp/img/no-pic.png';

                        if (!$.isEmptyObject(data)) {
                            imgUrl = 'http://172.16.98.81:8090/psa/files/' + data.PhotoFile;

                            $('#profile').attr('src', imgUrl);
                            $('#empName').text(data.EmpName);
                            $('#position').text(data.Position);
                            $('#points').text(data.TotalPoints);

                            $('#pointModal').modal('show');
                            fetchPrizeList(data.TotalPoints);
                        } else {
                            swalAlert('Your point is (0), Please let your boss give you the points', 'warning');
                            $('#empId').val('');
                        }
                    });
                }
            });
        });

        $(function() {
            setTimeout(function() {
                $('#pointModal').modal('hide');
            }, 60000);
        });

        $(function() {
            $('#pointModal').on('hidden.bs.modal', function() {
                $('#empId').trigger('focus');
                $('#empId').val('');
            });
        });
    </script>

</body>

</html>