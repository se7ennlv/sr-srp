<section class="mb-2">
    <h5 class="m-0 font-weight-bold text-primary"><i class="fas fa-star"></i> <strong>Requests List</strong></h5>
</section>

<div class="card mb-4 border-top-primary">
    <div class="card-body">
        <div class="table-responsive">
            <div id="toolbar" class="mb-2">
                <form class="form-inline" action="#" autocomplete="off" method="POST">
                    <div class="input-group mr-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Requested Period</span>
                        </div>
                        <input type="text" name="fromDate" id="fromDate" class="form-control date-picker" value="<?= date('Y-m-d'); ?>" readonly style="width: 120px">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-calendar-alt mr-1"></i> To <i class="fas fa-calendar-alt ml-1"></i></span>
                        </div>
                        <input type="text" name="toDate" id="toDate" class="form-control date-picker" value="<?= date('Y-m-d'); ?>" readonly style="width: 120px">
                    </div>
                    <div class="form-group mr-1">
                        <select name="state" id="state" class="form-control">
                            <option value="0">All</option>
                            <option value="1">Waiting Approval</option>
                            <option value="2">Approved</option>
                            <option value="3">Rejected</option>
                            <option value="4">Voided</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-success" onclick="initData();"><i class="fas fa-sync-alt"></i> Refresh Data</button>
                </form>
            </div>

            <table data-classes="table table-bordered table-sm table-hover table-striped" id="dataTable" data-toolbar="#toolbar" data-export-footer="true" data-search="true" data-show-toggle="true" data-show-fullscreen="true" data-show-columns="true" data-show-columns-toggle-all="true" data-show-export="true" data-minimum-count-columns="2" data-show-pagination-switch="true" data-pagination="true" data-id-field="id" data-page-size="25" data-page-list="[25, 50, 100, all]" data-sort-name="ReqDocNo" data-sort-order="desc">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center" data-formatter="runnings">#</th>
                        <th data-field="ReqDocNo" class="text-nowrap text-center">Doc No</th>
                        <th data-field="ReqPoints" class="text-nowrap text-center" data-formatter="badgeVal">Requested Points</th>
                        <th data-field="ReqState" class="text-nowrap text-center" data-formatter="reqState">Req Status</th>
                        <th data-field="ReqEmpID" class="text-nowrap text-center">Emp ID</th>
                        <th data-field="ReqEmpName" data-halign="center" class="text-truncate width-limit">Emp Name</th>
                        <th data-field="ReqPosition" data-halign="center" class="text-truncate width-limit">Position</th>
                        <th data-field="ReqDeptCode" class="text-center">Dept</th>
                        <th data-field="ReqRequesters" class="text-nowrap text-center">Requesters</th>
                        <th data-field="ReqComment" data-halign="center" class="text-truncate width-limit">Comments</th>
                        <th data-field="ReqRequestedAt" class="text-nowrap text-center" data-formatter="dateTimeShort">Requested At</th>
                        <th data-field="ReqHRActionBy" data-halign="center" class="text-nowrap text-center">HR Action By</th>
                        <th data-field="ReqHRActionAt" class="text-nowrap text-center" data-formatter="dateTime">HR Action At</th>
                        <th data-field="ReqExpiryDate" class="text-nowrap text-center" data-formatter="dates">Expiry Date</th>
                        <th data-field="ReqExpiryExtendDate" class="text-nowrap text-center" data-formatter="dates">Expiry Extend Date</th>
                        <th data-field="ReqRejectRemark" data-halign="center" class="text-truncate width-limit">Rejected Reasons</th>

                        <?php if ($this->session->userdata('userEmpId') === 'admin' || $this->session->userdata('userOrgId') == 10) : ?>
                            <th data-formatter="operates" class="text-center" data-events="operateEvents">Operates</th>
                        <?php endif; ?>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>


<script>
    //===================== initail ==========================//
    var table = $('#dataTable');

    $(function() {
        $('.date-picker').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        });
    });

    function initData() {
        var formData = $('form').serialize();

        $.ajax({
            type: 'POST',
            url: "<?= site_url('ReportController/FetchAllRequests') ?>",
            data: formData,
            dataType: 'JSON',
            beforeSend: function() {
                blockUI('Processing...');
            }
        }).done(function(data) {
            unblockUI();
            initTable(data);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            smkAlert('Something went wrong, please contact IT', 'danger');
        });

        return false;
    }

    function initTable(data) {
        table.bootstrapTable('destroy').bootstrapTable({
            height: 620,
            data: data,
            exportDataType: 'all',
            exportOptions: {
                ignoreColumn: [15],
                fileName: 'approver-denier-list'
            }
        });
    }

    $(function() {
        cellPopover(table, 'td:eq(5)', 'Emp Name', 'left');
        cellPopover(table, 'td:eq(9)', 'Requester Comment', 'left');
        cellPopover(table, 'td:eq(14)', 'Rejected Reason', 'left');
    });


    //====================== operates ===============================//
    window.operateEvents = {
        'click .btn-void': function(e, value, row, index) {
            onVoid(row.ReqID);
        }
    }

    function onVoid(itemId) {
        Swal.fire({
            title: 'Confirm',
            text: "",
            icon: 'question',
            showCancelButton: true,
            cancelButtonText: 'Cancel',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "GET",
                    url: "<?= site_url('ReportController/InitVoid') ?>/" + itemId
                }).done(function(data) {
                    initData($('#fromDate').val(), $('#toDate').val());
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    smkAlert('Something went wrong, pleaae contact IT', 'danger');
                });
            }
        });
    }

    $(function() {
        initData();
    });
</script>