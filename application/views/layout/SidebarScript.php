<script>
    //========================== Administrator ================================//
    // function linkUsers() {
    //     $.ajax({
    //         url: '<?= site_url('user_controller/index'); ?>',
    //         success: function(data) {
    //             $("#mainApp").html(data);
    //         }
    //     });

    //     return false;
    // }

    // function linkDeptManage() {
    //     $.ajax({
    //         url: '<?= site_url('dept_controller/index'); ?>',
    //         success: function(data) {
    //             $("#mainApp").html(data);
    //         }
    //     });

    //     return false;
    // }

    // function linkSmtpConfig() {
    //     $.ajax({
    //         url: '<?= site_url('service_controller/StmtView'); ?>',
    //         success: function(data) {
    //             $("#mainApp").html(data);
    //         }
    //     });

    //     return false;
    // }


    //========================= HOD =========================//
    function linkPointRequest() {
        $.ajax({
            url: "<?= site_url('HodController/index'); ?>",
            beforeSend: function() {
                blockUI('Loading...');
            }
        }).done(function(data) {
            unblockUI();
            $("#mainApp").html(data);
        });

        return false;
    }



    //========================= HR Approval ==============================//
    function linkWaitingApprove() {
        $.ajax({
            url: "<?= site_url('HrController/WaitingApproveView'); ?>",
            beforeSend: function() {
                blockUI('Loading...');
            }
        }).done(function(data) {
            unblockUI();
            $("#mainApp").html(data);
        });

        return false;
    }


    //============================= HR Admin ==============================//
    function linkPrizeRedeem() {
        $.ajax({
            url: "<?= site_url('HrController/RedeemView'); ?>",
            beforeSend: function() {
                blockUI('Loading...');
            }
        }).done(function(data) {
            unblockUI();
            $("#mainApp").html(data);
        });

        return false;
    }



    //============================== Reports =============================//
    function linkPointSummary() {
        $.ajax({
            url: "<?= site_url('ReportController/PointSummaryView'); ?>",
            beforeSend: function() {
                blockUI('Loading...');
            }
        }).done(function(data) {
            unblockUI();
            $("#mainApp").html(data);
        });

        return false;
    }

    function linkRedeemedList() {
        $.ajax({
            url: '<?= site_url('ReportController/RedeemedView'); ?>',
            beforeSend: function() {
                blockUI('Loading...');
            }
        }).done(function(data) {
            unblockUI();
            $("#mainApp").html(data);
        });

        return false;
    }

    function linkRequestList() {
        $.ajax({
            url: "<?= site_url('ReportController/RequestView'); ?>"
        }).done(function(data) {
            $("#mainApp").html(data);
        });

        return false;
    }

    

    function linkCheckPointEmp() {
        window.open('http://172.16.98.171/srp/AppController/CheckPointView', '_blank');

        return false;
    }



    //================================= change pass =============================//
    $(function() {
        $('#frmChgPass').on('submit', function(e) {
            e.preventDefault();

            if ($(this).smkValidate()) {
                var formData = $(this).serialize();

                $.ajax({
                    method: 'POST',
                    url: "<?= site_url('UserController/InitChangePass') ?>",
                    data: formData,
                    beforeSend: function() {
                        blockUI('Processing...');
                    }
                }).done(function(data) {
                    unblockUI();
                    smkAlert(data.message, data.status);
                    $('#changePwdModal').modal('hide');
                    $('#frmChgPass').smkClear();
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    smkAlert('Something went wrong, please contact IT!', 'danger');
                });
            }
        });
    });
</script>