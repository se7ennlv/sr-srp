<section class="mb-2">
    <h5 class="m-0 font-weight-bold text-primary"><i class="fas fa-star"></i> <strong>Staff Available Points Summary</strong></h5>
</section>

<div class="card mb-4 border-top-primary">
    <div class="card-body">
        <div class="table-responsive">
            <table data-classes="table table-bordered table-sm table-hover table-striped" id="dataTable" data-show-refresh="true" data-url="<?= site_url('ReportController/FetchAllPointSummary'); ?>" data-toolbar="#toolbar" data-export-footer="true" data-search="true" data-show-toggle="true" data-show-fullscreen="true" data-show-columns="true" data-show-columns-toggle-all="true" data-show-export="true" data-minimum-count-columns="2" data-show-pagination-switch="true" data-pagination="true" data-id-field="id" data-page-size="25" data-page-list="[25, 50, 100, all]" data-sort-name="ReqDocNo" data-sort-order="desc">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center" data-formatter="runnings">#</th>
                        <th data-field="EmpID" class="text-nowrap text-center">Emp ID</th>
                        <th data-field="EmpName" data-halign="center" class="text-nowrap">Emp Name</th>
                        <th data-field="Position" data-halign="center" class="text-nowrap">Position</th>
                        <th data-field="DeptCode" class="text-nowrap text-center">Dept</th>
                        <th data-field="TotalPoints" class="text-nowrap text-center" data-formatter="badgeVal">Point Summary</th>
                        <th data-field="EmpID" class="text-nowrap text-center" data-formatter="viewDetail">Detail</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div id="hisModal" class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary"><i class="fas fa-info-circle"></i> Detail for: <span id="emp"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-hover table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center text-nowrap">Point</th>
                                <th class="text-center text-nowrap">Requester</th>
                                <th class="text-center text-nowrap">Requested At</th>
                                <th class="text-nowrap text-center">Requester Comment</th>
                                <th class="text-center text-nowrap">HR Approved At</th>
                                <th class="text-center text-nowrap">HR Approved By</th>
                            </tr>
                        </thead>
                        <tbody id="dynamicRows">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
            </div>
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
        })
    });

    function initTable(data) {
        table.bootstrapTable('destroy').bootstrapTable({
            height: 620,
            exportDataType: 'all',
            exportOptions: {
                ignoreColumn: [6],
                fileName: 'staff-points-summary'
            }
        })
    }

    $('#hisModal').on('show.bs.modal', function(e) {
        var related = $(e.relatedTarget);
        var modal = $(this);
        var empId = related.data('emp');

        modal.find('#emp').text(empId);

        $.ajax({
            type: 'GET',
            url: "<?= site_url('ReportController/PointRouteView') ?>/" + empId,
            dataType: 'JSON'
        }).done(function(data) {
            if (!$.isEmptyObject(data)) {
                $('#dynamicRows').html('');

                $.each(data, function(i, val) {
                    rowsAppend(val);
                });
            }

        });
    });

    function rowsAppend(data) {
        var html = Mustache.render(rowsTempl, data);
        $('#dynamicRows').append(html);

        $(function() {
            $('[data-toggle="popover"]').popover();
        })
    }

    var rowsTempl = ['<tr><td class="text-center"><span class="badge badge-secondary">{{ReqPointAfterDeduct}}</span></td>',
        '<td class="text-center">{{ReqRequesters}}</td>',
        '<td class="text-nowrap text-center">{{ReqRequestedAt}}</td>',
        '<td class="text-truncate width-limit" data-toggle="popover" data-trigger="hover" data-content="{{ReqComment}}">{{ReqComment}}</td>',
        '<td class="text-center">{{ReqHRActionAt}}</td>',
        '<td class="text-center">{{ReqHRActionBy}}</td></tr>'
    ].join('');

    $(function() {
        initTable();
    });
</script>