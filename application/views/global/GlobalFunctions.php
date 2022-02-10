<script>
    function DataFilter(rawStr) {
        var newStr = rawStr.replace('%', '').replace('?', '').replace('+', '').replace(';', '').replace('E', '');
        var pid;

        if (newStr.length == 8) {
            pid = newStr
        } else if (newStr.length > 6) {
            pid = newStr.substr(0, 6);
        } else {
            pid = newStr;
        }

        return pid;
    }

    //========================== using for pages (redeem, check point) ======================//
    function fetchPrizeList(totalPoint) {
        $('#prizeList').html('');

        $.getJSON("<?= site_url('PrizeController/FetchAllPrizes') ?>", function(data) {
            if (!$.isEmptyObject(data)) {
                var rowNums = 0;

                $.each(data, function(i, val) {
                    var btnClass = '';
                    rowNums += 1;

                    if (parseFloat(val.PrizePpNo) > parseFloat(totalPoint)) {
                        btnClass = 'd-none';
                    }

                    var objs = {
                        rowNum: rowNums,
                        prizeID: val.PrizeID,
                        prizePpNo: val.PrizePpNo,
                        prizeDetail: val.PrizeDetail,
                        prizeUnit: val.PrizeUnit,
                        prizePpDesc: val.PrizePpDesc,
                        btnClass: btnClass
                    }

                    prizeAppend(objs);
                });
            }
        });
    }

    function prizeAppend(data) {
        var html = Mustache.render(prizeTempl, data);
        $('#prizeList').append(html);

        $(function() {
            $('[data-toggle="popover"]').popover();
        })
    }

    var prizeTempl = ['<tr><td class="text-center">{{rowNum}}</td>',
        '<td class="text-truncate width-limit" data-toggle="popover" data-trigger="hover" data-content="{{prizeDetail}}">{{prizeDetail}}</td>',
        '<td class="text-truncate width-limit" data-toggle="popover" data-trigger="hover" data-content="{{prizeUnit}}">{{prizeUnit}}</td>',
        '<td class="text-center"><span class="badge badge-secondary">{{prizePpDesc}}</span></td>',
        '<td class="text-center"><button class="btn btn-success btn-sm {{btnClass}}" onclick="onRedeem({{prizeID}}, {{prizePpNo}}, \`{{prizeUnit}}\`, \`{{prizeDetail}}\`);">OK</button></td></tr>'
    ].join('');
    //========================== end ======================//


    //============================== redeem operates ===============================//
    function getRedeemData(redId) {
        $.ajax({
            type: 'GET',
            url: "<?= site_url('HrController/FetchRedeemedData') ?>/" + redId,
            dataType: 'JSON'
        }).done(function(data) {
            $('#docNo').text(('00000' + data.RedID).slice(-6));
            $('#chkEmpId').text(data.RedEmpID);
            $('#chkQty').text(data.RedPrizeUnit);
            $('#chkDept').text(data.RedDeptCode);
            $('#chkPrize').text(data.RedPrizeDetail);
            $('#chkRedPoint').text(data.RedPrizePpNo);
            $('#chkRedDate').text(moment(data.RedCreatedAt).format('DD-MMM-YYYY h:mm:ss A'));

            var dateExpry = moment(data.RedCreatedAt).add(7, 'd').format('YYYY-MM-DD');
            $('#chkExpiryDate').text(dateExpry);

            PrintBill();
        });
    }

    function PrintBill() {
        $('#docBody').printThis({
            importCSS: true,
            header: null,
            footer: null,
        });
    }
</script>