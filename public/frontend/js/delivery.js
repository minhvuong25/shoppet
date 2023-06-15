$(document).ready(function () {
    $(document).on('change','.choose', function() {
        var action = $(this).attr('id');
        var ma_id = $(this).val();
        var result = '';
        if (action == 'city') {
            result = 'province';
        } else {
            result = 'wards';
        }
        $.ajax({
            url: urls.fetchDelivery,
            method: 'GET',
            data: {
                _token:_token,
                action: action,
                ma_id: ma_id,
            },
            success: function(res) {
                var html = '';
                var htmlW = '';
                var provinces = res.provinces;
                var wards = res.wards;
                if(res.provinces){
                    html = '<option value="">--- Choose a district ---</option>';
                    for (let i = 0; i < provinces.length; i++) {
                        html += '<option value="' + provinces[i]['maqh'] + '">' + provinces[i]['name_quanhuyen'] + '</option>';
                    }
                    $('#province').html(html);
                }
                if(res.wards){
                    for (let i = 0; i < wards.length; i++) {
                        htmlW += '<option value="' + wards[i]['xaid'] + '">' + wards[i]['name_xaphuong'] + '</option>';
                    }
                    $('#wards').html(htmlW);
                }
            }
        });
    });

    $(document).on('click', '.caculate_delivery', function () {
        CaculateFee();
    });
    var CaculateFee = function () {
        var city = $('.city').val();
        var province = $('.province').val();
        var wards = $('.wards').val();
        $.ajax({
            url: urls.caculateFee,
            method: 'POST',
            data: {
                city:city,
                province:province,
                wards:wards
            },
            success: function () {
                location.reload();
            }
        });
    }
});
