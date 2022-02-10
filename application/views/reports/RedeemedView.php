<section class="mb-2">
    <h5 class="m-0 font-weight-bold text-primary"><i class="fas fa-star-half-alt"></i> <strong>Redeemed List</strong></h5>
</section>
<div class="card mb-4 border-top-primary">
    <div class="card-body">
        <div class="table-responsive">
            <div id="toolbar" class="mb-2">
                <form class="form-inline" action="#" autocomplete="off" method="POST">
                    <div class="input-group ml-1 mr-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Redeemed Period</span>
                        </div>
                        <input type="text" name="fromDate" id="fromDate" class="form-control date-picker" value="<?= date('Y-m-d'); ?>" readonly style="width: 120px;">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-calendar-alt mr-1"></i> To <i class="fas fa-calendar-alt ml-1"></i></span>
                        </div>
                        <input type="text" name="toDate" id="toDate" class="form-control date-picker" value="<?= date('Y-m-d'); ?>" readonly style="width: 120px;">
                    </div>
                    <button type="button" class="btn btn-success" onclick="initData();"><i class="fas fa-sync-alt"></i> Refresh Data</button>
                </form>
            </div>

            <table data-classes="table table-bordered table-sm table-hover table-striped" id="dataTable" data-toolbar="#toolbar" data-export-footer="true" data-search="true" data-show-toggle="true" data-show-fullscreen="true" data-show-columns="true" data-show-columns-toggle-all="true" data-show-export="true" data-minimum-count-columns="2" data-show-pagination-switch="true" data-pagination="true" data-id-field="id" data-page-size="25" data-page-list="[25, 50, 100, all]" data-sort-name="RedID" data-sort-order="desc">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center" data-formatter="runnings">#</th>
                        <th data-field="RedID" class="text-nowrap text-center">Redeem No</th>
                        <th data-field="RedEmpID" class="text-nowrap text-center">Emp ID</th>
                        <th data-field="RedEmpName" data-halign="center" class="text-nowrap">Emp Name</th>
                        <th data-field="RedPosition" data-halign="center" class="text-nowrap">Position</th>
                        <th data-field="RedDeptCode" class="text-nowrap text-center">Dept</th>
                        <th data-field="RedPrizeDetail" data-halign="center" class="text-truncate">Prize</th>
                        <th data-field="RedPrizeUnit" class="text-nowrap text-center">Unit</th>
                        <th data-field="RedPrizePpNo" class="text-nowrap text-center" data-formatter="badgeVal">Points</th>
                        <th data-field="RedCreatedAt" class="text-nowrap text-center" data-formatter="dateTime">Redeemed At</th>
                        <th class="text-nowrap text-center" data-formatter="printInv" data-events="print">Operates</th>
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

    $(function() {
        cellPopover(table, 'td:eq(6)', 'Prize', 'left');
    });


    function initData(fDate, tDate) {
        var formData = $('form').serialize();

        $.ajax({
            type: 'POST',
            url: "<?= site_url('ReportController/FetchAllRedeemed') ?>",
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
                ignoreColumn: [10],
                fileName: 'redeemed-list'
            }
        });
    }

    $(function() {
        initData();
    });

    window.print = {
        'click .btn-print': function(e, value, row, index) {
            getRedeemData(row.RedID);
        }
    }
</script>