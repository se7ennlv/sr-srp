<section class="mb-2">
    <h5 class="m-0 font-weight-bold text-primary"><i class="fas fa-exchange-alt"></i> <strong>Redeem Prize</strong></h5>
</section>
<hr>
<div class="row">
    <div class="col-xl-4 col-md-12 mb-4">
        <div class="card border-lr-info shadow h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-lg font-weight-bold text-primary text-uppercase mb-1">Only staff has points</div>
                        <hr>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-info"></i>
                    </div>
                </div>
                <div class="row">
                    <div class="col mr-2">
                        <form action="#" id="frmChkPoint" method="POST" autocomplete="off" novalidate>
                            <div class="input-group form-group mb-3">
                                <div class="input-group-prepend" onclick="onCheckPoint();" style="cursor: pointer">
                                    <span class="input-group-text font-weight-bold btn btn-info active">Click to Check Points</span>
                                </div>
                                <input type="text" class="form-control" id="empId" placeholder="Enter Emp ID" required>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text font-weight-bold">ID</span>
                                </div>
                                <input type="text" class="form-control" id="id" readonly>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text font-weight-bold">Name</span>
                                </div>
                                <input type="text" class="form-control" id="name" readonly>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text font-weight-bold">Position</span>
                                </div>
                                <input type="text" class="form-control" id="position" readonly>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text font-weight-bold">Department</span>
                                </div>
                                <input type="hidden" id="orgId">
                                <input type="text" class="form-control" id="dept" readonly>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text font-weight-bold">Total Point</span>
                                </div>
                                <input type="text" class="form-control" id="totalPoint" value="0" readonly>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8 col-md-12 mb-4">
        <div class="card border-lr-warning shadow h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-lg font-weight-bold text-primary text-uppercase mb-1">Prize List</div>
                        <hr>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-gifts fa-2x text-info"></i>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive overflow-auto" style="max-height: 38rem;">
                            <table class="table table-sm table-border">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Prize Description</th>
                                        <th class="text-nowrap text-center">Unit</th>
                                        <th class="text-nowrap text-center">Points</th>
                                        <th class="text-nowrap text-center">Available Redeem</th>
                                    </tr>
                                </thead>
                                <tbody id="prizeList">
                                    <!-- dynamic -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    //=================== inittail ================//
    $(document).ready(function() {
        fetchPrizeList(0);
    });

    function onCheckPoint() {
        if ($('#frmChkPoint').smkValidate()) {
            var empId = $.trim($('#empId').val());

            $.ajax({
                type: 'GET',
                url: "<?= site_url('HrController/FetchPointEmp') ?>/" + empId
            }).done(function(data) {
                if (!$.isEmptyObject(data)) {
                    $('#id').val(data.EmpID);
                    $('#name').val(data.EmpName);
                    $('#position').val(data.Position);
                    $('#orgId').val(data.OrgID);
                    $('#dept').val(data.DeptCode);
                    $('#totalPoint').val(data.TotalPoints);
                } else {
                    $('#id').val('');
                    $('#name').val('');
                    $('#position').val('');
                    $('#orgId').val('');
                    $('#dept').val('');
                    $('#totalPoint').val(0);
                }

                var totalPoint = $('#totalPoint').val();
                fetchPrizeList(totalPoint);
            }).fail(function() {
                smkAlert('Something went wrong, please contact IT', 'danger');
            });
        }
    }

    function onRedeem(priId, points, priUnit, priDetail) {
        Swal.fire({
            title: 'Confirm',
            text: "",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.value) {
                var jobs = {
                    empId: $('#id').val(),
                    empName: $('#name').val(),
                    position: $('#position').val(),
                    orgId: $('#orgId').val(),
                    deptCode: $('#dept').val(),
                    prizeId: priId,
                    points: points,
                    prizeUnit: priUnit,
                    prizeDetail: priDetail
                }

                $.ajax({
                    type: 'POST',
                    url: "<?= site_url('HrController/InitRedeem') ?>",
                    data: $.param(jobs),
                    beforeSend: function() {
                        blockUI('Processing...');
                    }
                }).done(function(redeemId) {
                    unblockUI();
                    pointDeduction(jobs.empId, points, redeemId);
                    onCheckPoint();
                    getRedeemData(redeemId);
                }).fail(function() {
                    smkAlert('Something went wrong, please contact IT', 'danger');
                });
            }
        });
    }

    function pointDeduction(empId, redeemPoint, redeemId) {
        $.ajax({
            type: 'POST',
            url: "<?= site_url('HrController/InitDeductPoint') ?>",
            data: {
                empId: empId,
                redPoint: redeemPoint,
                redId: redeemId
            }
        }).fail(function() {
            smkAlert('Something went wrong, please contact IT', 'danger');
        });
    }
</script>