<section class="mb-2">
    <h5 class="m-0 font-weight-bold text-primary"><i class="fas fa-hourglass-half"></i> <strong>Waiting (HR) Approval</strong></h5>
</section>

<div class="card mb-4 border-top-primary">
    <div class="card-body">
        <div class="table-responsive">
            <div id="toolbar">
                <button id="btnApprove" class="btn btn-success btn-action" disabled>
                    <i class="fas fa-check-circle"></i> Approve
                </button>
                <button id="btnReject" class="btn btn-danger btn-action" disabled>
                    <i class="fas fa-times"></i> Reject
                </button>
            </div>

            <table class="table table-bordered table-sm table-hover table-striped" id="dataTable" data-show-refresh="true" data-toolbar="#toolbar" data-url="<?= site_url('HrController/FetchAllWaitingApprove') ?>" data-export-footer="true" data-search="true" data-show-toggle="true" data-show-fullscreen="true" data-show-columns="true" data-show-columns-toggle-all="true" data-show-export="true" data-minimum-count-columns="2" data-show-pagination-switch="true" data-pagination="true" data-id-field="id" data-page-size="25" data-page-list="[25, 50, 100, all]" data-sort-name="ReqDocNo" data-sort-order="desc">
                <thead class="thead-light">
                    <tr>
                        <th data-field="state" data-halign="center" data-checkbox="true"></th>
                        <th data-field="ReqDocNo" data-halign="center" class="text-nowrap text-center">Doc No.</th>
                        <th data-field="ReqPoints" data-halign="center" class="text-nowrap text-center" data-formatter="badgeVal">Requested Points</th>
                        <th data-field="ReqEmpID" data-halign="center" class="text-nowrap text-center">Emp ID</th>
                        <th data-field="ReqEmpName" data-halign="center" class="text-truncate width-limit">Emp Name</th>
                        <th data-field="ReqPosition" data-halign="center" class="text-truncate width-limit">Position</th>
                        <th data-field="ReqDeptCode" data-halign="center" class="text-nowrap text-center">Dept</th>
                        <th data-field="ReqRequesters" data-halign="center" class="text-nowrap text-center">Requesters</th>
                        <th data-field="ReqRequestedAt" data-halign="center" class="text-nowrap text-center" data-formatter="dateTimeShort">Requested At</th>
                        <th data-field="ReqComment" data-halign="center" class="text-truncate width-limit">Requester Comments</th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>
</div>

<script>
    //===================== initail ==========================//
    var table = $('#dataTable');
    var btnActions = $('.btn-action');
    var btnApprove = $('#btnApprove');
    var btnReject = $('#btnReject');
    var selections = [];


    function initTable() {
        table.bootstrapTable('destroy').bootstrapTable({
            height: 620,
            exportDataType: 'all',
            exportOptions: {
                fileName: 'waiting-approve-list'
            }
        })
    }

    $(function() {
        cellPopover(table, 'td:eq(9)', 'Requester Comment', 'left');
    });


    //=========================== operates =============================//
    function getIdSelections() {
        return $.map(table.bootstrapTable('getSelections'), function(row) {
            return row
        })
    }

    table.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table',
        function() {
            btnApprove.prop('disabled', !table.bootstrapTable('getSelections').length);
            btnReject.prop('disabled', !table.bootstrapTable('getSelections').length);

            selections = getIdSelections();
        });

    btnApprove.click(function() {
        var items = getIdSelections();
        Approve(items);
    });

    btnReject.click(function() {
        var items = getIdSelections();
        Reject(items);
    });

    function Approve(items) {
        Swal.fire({
            title: 'Confirm',
            text: "",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.value) {
                $.each(items, function(i, val) {
                    var params = {
                        docNo: val.ReqDocNo,
                        orgId: val.ReqOrgID
                    }

                    $.ajax({
                        type: 'POST',
                        url: "<?= site_url('HrController/InitApprove') ?>",
                        data: $.param(params),
                        beforeSend: function() {
                            blockUI('Processing...');
                        }
                    }).done(function(data) {
                        unblockUI();
                        initTable();
                    }).fail(function() {
                        smkAlert('Something went wrong, please contact IT', 'danger');
                    });
                });

                smkAlert('Approved', 'success');
                btnActions.prop('disabled', true);
            }
        });
    }

    function Reject(items) {
        Swal.fire({
            title: 'Enter your reason',
            text: "",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            input: 'text',
            inputAttributes: {
                autocapitalize: 'off',
                required: true
            },
        }).then((result) => {
            if (result.value) {
                $.each(items, function(i, val) {
                    var params = {
                        docNo: val.ReqDocNo,
                        orgId: val.ReqOrgID,
                        remark: result.value
                    }

                    $.ajax({
                        type: 'POST',
                        url: "<?= site_url('HrController/InitReject') ?>",
                        data: $.param(params),
                        beforeSend: function() {
                            blockUI('Processing...');
                        }
                    }).done(function(data) {
                        unblockUI();
                        initTable();
                    }).fail(function() {
                        smkAlert('Something went wrong, please contact IT', 'danger');
                    });
                });

                smkAlert('Rejected', 'success');
                btnActions.prop('disabled', true);
            }
        });
    }

    $(function() {
        initTable();
    });
</script>