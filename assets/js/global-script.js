//======================== notification ==============================//
function swalAlert(text, icon) {
    Swal.fire({
        icon: icon,
        title: '',
        text: text
    })
}

function smkAlert(msg, type) {
    $.smkAlert({
        text: msg,
        type: type
    });
}

function blockUI(msg) {
    $.blockUI({
        message: '<h3><i class="fas fa-spinner fa-spin fa-1x"></i> ' + msg + '</h3>',
        baseZ: 2000
    });
}

function unblockUI() {
    $.unblockUI();
}



//============================= DataTable =========================//
function runnings(value, row, index) {
    return 1 + index;
}

function badgeVal(data) {
    return '<span class="badge badge-secondary">' + data + '</span>';
}

function dates(data) {
    return (moment(data).isValid() && moment(data).format('YYYY') != '1900') ? moment(data).format('DD-MMM-YY') : '';
}
function times(data) {
    return (moment(data).isValid() && moment(data).format('YYYY') != '1900') ? moment(data).format('h:mm:ss') : '';
}

function dateTime(data) {
    return (moment(data).isValid() && moment(data).format('YYYY') != '1900') ? moment(data).format('DD-MMM-YY h:mm') : '';
}

function dateTimeShort(data) {
    return (moment(data).isValid() && moment(data).format('YYYY') != '1900') ? moment(data).format('DD-MMM-YY h:mm') : '';
}

function numbers(data) {
    return numeral(data).format('0,0');
}

function redeemState(value, row, index) {
    var redeemParentID = parseInt(row.ReqRedeemParentID);
    var redeemState = parseInt(row.ReqRedeemState);

    var isFalse = '<span class="badge badge-success">awaiting redeem</span>';
    var isTrue = '<span class="badge badge-secondary">redeemed</span>';

    return (!isNaN(redeemParentID) && redeemState != 2) ? isTrue : isFalse;
}

function reqState(data) {
    var pending = '<span class="badge badge-primary">Waiting Approval</span>';
    var approve = '<span class="badge badge-success">Approved</span>';
    var reject = '<span class="badge badge-secondary">Rejected</span>';
    var voided = '<span class="badge badge-danger">Voided</span>';

    return (data == 1) ? pending : (data == 2) ? approve : (data == 3) ? reject : voided;
}

function viewDetail(data) {
    return [
        '<a class="btn btn-info btn-sm btn-view" data-emp="', data, '" data-toggle="modal" data-target="#hisModal" href="javascript:void(0)" title="view-detail" data-unique-id="', data, '">',
        '<i class="fas fa-info-circle"></i> Detail',
        '</a>'
    ].join('');
}

function printInv(data) {
    return [
        '<a class="btn btn-warning btn-sm btn-print" href="javascript:void(0)" title="re-print" data-unique-id="', data, '">',
        '<i class="fas fa-print"></i> Re-Print',
        '</a>'
    ].join('');
}


function cellPopover(table, td, title, placement) {
    table.on('all.post-body.bs.table', function(e, name, args) {
        $('[data-toggle="popover"]').popover();

        $(this).find('tr').find(td).each(function() {
            $(this).attr('data-original-title', title);
            $(this).attr('data-toggle', 'popover');
            $(this).attr('data-placement', placement);
            $(this).attr('data-trigger', 'hover');
            $(this).attr('data-content', $(this).text());
        });
    });
}

function operates(value, row, index) {
    if (row.ReqState == 2) {
        return [
            '<a class="btn btn-danger btn-sm btn-void" href="javascript:void(0)" title="Void" data-unique-id="', row.id, '">',
            '<i class="fa fa-times"></i> Void',
            '</a>'
        ].join('');
    } else {
        return '';
    }
}