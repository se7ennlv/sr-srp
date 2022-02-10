<section>
    <h3 class="text-primary"><i class="fas fa-star-half-alt"></i> Points Request</h3>
</section>

<div class="card border-top-primary shadow h-100">
    <div class="card-body">
        <div class="col-xl-6 col-md-9 mb-4">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-lg font-weight-bold text-primary mb-1">Please choose your staff and enter the comments</div>
                </div>

            </div>
            <hr>
            <div class="row">
                <div class="col">
                    <div class="table-responsive">
                        <form action="#" method="POST" novalidate="off">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td colspan="2" class="text-nowrap" colspan="2">Select staff</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <select name="ReqEmpID" class="form-control custom-select-lg select2" required onchange="getPersonData($(this).val());">
                                                <option value=""></option>
                                                <?php
                                                $i = 0;
                                                foreach ($emps as $emp) :
                                                    $i = $i + 1;
                                                ?>
                                                    <option value="<?= $emp->EmpCode; ?>">(<?= $i; ?>)-<?= $emp->EmpCode; ?>-<?= $emp->EmpFname; ?>-[<?= $emp->Positions; ?>]-[<?= $emp->DeptCode; ?>]</option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Select Points</td>
                                    <td>Select Type of Recognized Behavior</td>
                                </tr>
                                <tr>
                                    <td class="text-nowrap">
                                        <div class="form-group">
                                            <select name="ReqPoints" class="form-control" required>
                                                <option value="0.5">0.5 Point</option>
                                                <option value="1">1 Point</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td class="text-nowrap">
                                        <div class="form-group">
                                            <select name="ReqBehID" class="form-control" required>
                                                <option value=""></option>
                                                <?php
                                                foreach ($types as $type) : ?>
                                                    <option value="<?= $type->BehID; ?>"><?= $type->BehDesc; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <textarea name="ReqComment" cols="30" rows="5" class="form-control" placeholder="Enter your comment here" required></textarea>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <button type="submit" class="btn btn-primary mt-4"><i class="fas fa-share"></i> Submit</button>
                                    </td>
                                </tr>
                            </table>

                            <input type="hidden" name="ReqEmpName">
                            <input type="hidden" name="ReqPosition">
                            <input type="hidden" name="ReqOrgID">
                            <input type="hidden" name="ReqDeptCode">
                            <input type="hidden" name="ReqDeptName">
                        </form>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <hr>
                    <h5>The system will submit your request to HR approvers below: </h5>
                    <?php
                    foreach ($mails as $mail) : ?>
                        <label id="apprMail" style="color: red"><?= $mail->ApprEmail; ?></label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(function() {
        $('.select2').select2();
    });

    function getPersonData(empId) {
        $.ajax({
            type: 'GET',
            url: "<?= site_url('EmpController/FetchOneEmp'); ?>/" + empId,
            dataType: 'json',
            beforeSend: function() {
                blockUI('Processing...');
            }
        }).done(function(data) {
            unblockUI();

            if (!$.isEmptyObject(data)) {
                $('input[name=ReqEmpName]').val(data.EmpFname + ' ' + data.EmpLname);
                $('input[name=ReqPosition]').val(data.Positions);
                $('input[name=ReqOrgID]').val(data.OrgID);
                $('input[name=ReqDeptCode]').val(data.DeptCode);
                $('input[name=ReqDeptName]').val(data.DeptName);
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            smkAlert('Something went wrong, please contact IT', 'danger');
        });
    }

    $('form').submit(function(e) {
        e.preventDefault();

        if ($(this).smkValidate()) {
            var formData = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: "<?= site_url('HodController/InitInsertRequest') ?>",
                data: formData,
                beforeSend: function() {
                    blockUI('Submitting...');
                }
            }).done(function(data) {
                unblockUI();

                smkAlert(data.message, data.status);
                $('select').prop('selectedIndex', 0);
                $('.select2').select2().select2('val', $('.select2 option:eq(0)').val());
                $('textarea').smkClear();
            }).fail(function(jqXHR, textStatus, errorThrown) {
                smkAlert('Something went wrong, please contact IT', 'danger');
            });
        }

    });
</script>