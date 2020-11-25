function showWarning(title, message) {
    Swal.fire({
        title: title,
        html: message,
        type: 'warning',
        showConfirmButton: true,
        showCloseButton: true
    });
}

function showRequestWarning(response) {
    if (response.responseJSON) {
        const resp = response.responseJSON;
        if (resp.errors) {
            let msg = '';

            $.each(resp.errors, function (key, value) {
                // console.log(key, value, value.length);
                if (value.length > 1) {
                    for (let i = 0; i < value.length; i++) {
                        msg += (msg) ? '<br>' + value[i] : value[i];
                    }
                } else {
                    msg += (msg) ? '<br>' + value : value;
                }
            });

            showWarning('Warning!', msg);
        }
    }
}

function saveSuccess() {
    Swal.fire({
        title: 'ดำเนินการสำเร็จ',
        text: "",
        type: 'success',
        timer: 2000,
        showCancelButton: false,
        showConfirmButton: false,
        showCloseButton: true
    });
    // .then((result) => {
    //     window.history.back();
    // });
}

function saveSuccessReload() {
    Swal.fire({
        title: 'ดำเนินการสำเร็จ',
        text: "",
        type: 'success',
        timer: 2000,
        showCancelButton: false,
        showConfirmButton: false,
        showCloseButton: true
    }).then((result) => {
        location.reload();
    });
}

function deleteItem(id, item_name) {
    Swal.fire({
        title: 'ยืนยันการทำรายการ?',
        // text: "You won't be able to revert this!",
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33'
    }).then((result) => {
        if (result.value) {
            callDelete(id, item_name);
        }
    });
}

function callDelete(id, item_name) {
    Swal.fire({
        title: 'Loading..',
        type: 'info',
        onOpen: () => {
            swal.showLoading();
            const formData = new FormData();
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            formData.append('id', id);

            $.ajax({
                url: $('#base_url').val() + '/api/admin/' + item_name + '/delete',
                type: 'POST',
                data: formData,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    // console.log(response);
                    swal.close();

                    if (response.total == 1) {
                        saveSuccessReload();
                    } else {
                        showWarning('Warning!', 'เกิดความผิดพลาดในระบบ กรุณาตรวจสอบอีกครั้ง');
                    }

                }
            });
        }
    });
    return false;
}

function restoreItem(id, item_name) {
    Swal.fire({
        title: 'ยืนยันการทำรายการ?',
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33'
    }).then((result) => {
        if (result.value) {
            callRestore(id, item_name);
        }
    });
}

function callRestore(id, item_name) {
    Swal.fire({
        title: 'Loading..',
        type: 'info',
        onOpen: () => {
            swal.showLoading();
            const formData = new FormData();
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            formData.append('id', id);

            $.ajax({
                url: $('#base_url').val() + '/api/admin/' + item_name + '/restore',
                type: 'POST',
                data: formData,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    // console.log(response);
                    swal.close();

                    if (response.total == 1) {
                        saveSuccessReload();
                    } else {
                        showWarning('Warning!', 'เกิดความผิดพลาดในระบบ กรุณาตรวจสอบอีกครั้ง');
                    }

                }
            });
        }
    });
    return false;
}

function doSomethingWithIt(this_ele, item_name) {
    // console.log($('.chk-box:not(.chk-all):checked').length);
    if ($('.chk-box:not(.chk-all):checked').length > 0) {
        if (this_ele.val()) {
            Swal.fire({
                title: 'ยืนยันการทำรายการ?',
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.value) {
                    callMultipleDelete(item_name, this_ele.val());
                }
            });
        }
    } else {
        showWarning('Warning!', 'กรุณาเลือกอย่างน้อย 1 รายการ');
    }
}

function callMultipleDelete(item_name, del_restore) {
    var arrChk = [];
    var id_name = '';
    var id = '';

    $('.chk-box:not(.chk-all)').each(function (idx, ele) {
        // console.log(idx, ele.id);
        if (ele.id != 'tick_all') {
            // console.log($(this).prop('checked'));
            if ($(this).prop('checked')) {
                id_name = ele.id;
                id = id_name.split('_')[1];
                arrChk.push(id);
            }
        }
    });

    const arrId = arrChk.join(',');
    // console.log(arrId);

    const formData = new FormData();
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
    formData.append('ids', arrId);

    let urlApi = (del_restore == 'delete') ? 'multidelete' : 'multirestore';

    $.ajax({
        url: $('#base_url').val() + '/api/admin/' + item_name + '/' + urlApi,
        type: 'POST',
        data: formData,
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            // console.log(response);
            swal.close();

            // if (response.total == 1) {
                saveSuccessReload();
            // } else {
            //     showWarning('Warning!', 'เกิดความผิดพลาดในระบบ กรุณาตรวจสอบอีกครั้ง');
            // }

        }
    });
}

function tickCheckbox() {
    // console.log($('.chk-box:not(.chk-all):checked').length);
    if ($('.chk-box:not(.chk-all):checked').length == chk_in_page) {
        $('.chk-box.chk-all').attr('checked', true);
    } else {
        if ($('.chk-box.chk-all').prop('checked')) {
            $('#th_chk_all').html(chk_ele);
        }
    }
}

function removeIt(id, chk) {
    for (let i = 0; i < chk.length; i++) {
        if (chk[i] === id) {
            chk.splice(i, 1);
        }
    }
    return chk;
}

function getKeyNameList() {
    let keyOption = '<option value="">--- เลือกตัวแปร ---</option>';
    $.ajax({
        url: $('#base_url').val() + '/api/admin/match/key-filter',
        type: 'POST',
        dataType: 'json',
        success: function (response) {
            // console.log(response);
            if (response.total > 0) {
                for (let i = 0; i < response.total; i++) {
                    keyOption += '<option value="' + response.records[i].value + '">' + response.records[i].name + '</option>';
                }
                $('.key-option').html(keyOption);
                console.log($('.key-option').html());
            }

            checkShowPageCondition();
        }
    });
}

function checkImageDimensions(width, height, width_recommended, height_recommended) {
    if (width == width_recommended && height == height_recommended)
        $('.dimension-color').css('color', 'green');
    else
        $('.dimension-color').css('color', 'red');
}

function checkReload(respStatus, pageName) {
    if (respStatus == 500) {
        const lc = localStorage.getItem('load_count_' + pageName);
        if (lc) {
            /*
            let loadCount = parseInt(lc);
            if (! isNaN(loadCount)) {
                if (loadCount <= 1) {
                    loadCount++;
                    localStorage.setItem('load_count_' + pageName, '' + loadCount);
                    location.reload();
                } else {
                    // console.log(localStorage.getItem('load_count_' + pageName)); // 2
                    // localStorage.removeItem('load_count_' + pageName);
                }
            }*/

            // console.log(localStorage.getItem('load_count_' + pageName)); // 1
            localStorage.removeItem('load_count_' + pageName);
        } else {
            localStorage.setItem('load_count_' + pageName, '1');
            location.reload();
        }
    }
}

function checkShowPageCondition() {
    var pageCondition = $('#page_condition').val();

    if (pageCondition == 'T') {
        $('.league').css('visibility', 'hidden');
        $('.league').css('height', '0px');
        $('.team').css('visibility', 'visible');
        $('.team').css('height', '64px');

        $('.key-box').show();
        // console.log('--- 123: page condition ---');

        checkPageKey();
    } else {
        $('.team').css('visibility', 'hidden');
        $('.team').css('height', '0px');
        $('.league').css('visibility', 'visible');
        $('.league').css('height', '64px');

        $('.key-box').hide();
    }
}

function checkPageKey() {
    const numTr = $('#tbody_page_key tr').length;
    // console.log('--- 456: tr.length (' + numTr + ') ---');
    
    if (numTr == 0) {
        genPageKey(0);
    } else {
        // edit mode
        let idName = '';
        let index = '';
        let keyNameVal = '';

        $('#tbody_page_key tr').each(function (idx, ele) {
            idName = $(this).attr('id'); // tr_page_key_1
            index = idName.split('_')[3];
            keyNameVal = $('#key_names_' + index).val();

            configKeyData(index, keyNameVal);
        });

        previewKeyFront();
    }
}

function addKeyRow() {
    const numTr = $('#tbody_page_key tr').length;
    genPageKey(numTr);
}

function genPageKey(idx) {
    // console.log('--- 789: genPageKey ---');
    const keyOption = $('.key-option').html();
    // console.log(keyOption);

    let tr = '<tr id="tr_page_key_' + idx + '">';
        tr +=   '<td>';
        tr +=       '<input type="hidden" name="key_ids[]" id="key_ids_' + idx + '" value="0">';
        tr +=       '<select class="form-control" name="key_names[]" id="key_names_' + idx + '" onchange="changeKeyName($(this))">';
        tr +=           keyOption;
        tr +=       '</select>';
        tr +=   '</td>';
        tr +=   '<td>';
        tr +=       '<input type="text" class="form-control key-val" name="key_values[]" id="key_values_' + idx + '" onkeyup="keyInput($(this))">';
        tr +=   '</td>';
        tr +=   '<td>';
        tr +=       '<button type="button" class="btn btn-sm btn-danger" onclick="delKeyRow($(this))"><i class="fa fa-trash"></i></button>';
        tr +=   '</td>';
        tr += '</tr>';

    $('#tbody_page_key').append(tr);
}

function delKeyRow(obj) {
    var cf = confirm('Do you want to remove?');
    if (cf) {
        const trId = obj.parent().parent().attr('id');
        const idx = trId.split('_')[3];

        $('#tr_page_key_' + idx).remove();
        resetKeyRowIndex();
        previewKeyFront();
    }
}

function resetKeyRowIndex() {
    let id_name = '';
    let old_idx = '';
    let idx = 0;

    $('#tbody_page_key tr').each(function(key){
        id_name = $(this).attr('id');
        // console.log(key, id_name);

        old_idx = id_name.split('_')[3];

        $('#tr_page_key_' + old_idx).attr('id', 'tr_page_key_'+ key);
        $('#key_names_' + old_idx).attr('id', 'key_names_'+ key);
        $('#key_values_' + old_idx).attr('id', 'key_values_'+ key);
    });
}

function changeKeyName(this_obj) {
    const idName = this_obj.attr('id'); // key_names_1
    const idx = idName.split('_')[2];
    const keyNameVal = this_obj.val();

    configKeyData(idx, keyNameVal);
    previewKeyFront();
}

function configKeyData(idx, keyNameVal) {
    const dateData = new Date();
    const fullYear = dateData.getFullYear();
    const month = (dateData.getMonth() + 1);
    const date = dateData.getDate();
    const h = dateData.getHours();
    const m = dateData.getMinutes();
    // const s = dateData.getSeconds();
    let placeholder = '';

    if (keyNameVal == 'key_date') {
        placeholder = twoDigit(date) + '/' + twoDigit(month) + '/' + fullYear;
    } else if (keyNameVal == 'key_time') {
        placeholder = twoDigit(h) + ':' + twoDigit(m);
    } else if (keyNameVal == 'match_time') {
        placeholder = twoDigit(date) + '/' + twoDigit(month) + '/' + fullYear + ' ' + twoDigit(h) + ':' + twoDigit(m);
    } else {
        placeholder = '';
    }

    $('#key_values_' + idx).attr('placeholder', placeholder);
}

function validateDateTime() {
    const regxDate = /^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$/;
    const regxTime = /^(0[0-9]|1[0-9]|2[0-3]|[0-9]):[0-5][0-9]$/;
    const regxDateTime = /^([0][1-9]|[1][0-9]|[2][0-9]|[3][0-1])\/([0][1-9]|[1][0-2])\/([1][9][0-9]{2}|[2][0-9]{3})( ([0-1][0-9]|[2][0-3]):[0-5][0-9])$/;
    let idName = '';
    let index = '';
    let keyNameVal = '';
    let keyValueVal = '';
    let realKeyVal = '';
    let realDate = '';
    let realTheTime = '';
    let realDateTime = '';
    let countError = 0;
    let focusInput = null;

    $('#tbody_page_key tr').each(function (idx, ele) {
        idName = $(this).attr('id'); // tr_page_key_1
        index = idName.split('_')[3];
        keyNameVal = $('#key_names_' + index).val();
        keyValueVal = $('#key_values_' + index).val();
        realKeyVal = keyValueVal.trim();

        if (realKeyVal) {
            if (keyNameVal == 'key_date') {
                realDate = realKeyVal.substring(0, 10);
                if (! regxDate.test(realDate)) {
                    $('#key_values_' + index).addClass('input-warning');
                    countError++;
                } else {
                    $('#key_values_' + index).removeClass('input-warning');
                }
            } else if (keyNameVal == 'key_time') {
                realTheTime = realKeyVal.substring(0, 5);
                if (! regxTime.test(realTheTime)) {
                    $('#key_values_' + index).addClass('input-warning');
                    countError++;
                } else {
                    $('#key_values_' + index).removeClass('input-warning');
                }
            } else if (keyNameVal == 'match_time') {
                realDateTime = realKeyVal.substring(0, 16);
                if (! regxDateTime.test(realDateTime)) {
                    $('#key_values_' + index).addClass('input-warning');
                    focusInput = (focusInput) ? focusInput : $('#key_values_' + index);
                    countError++;
                } else {
                    $('#key_values_' + index).removeClass('input-warning');
                }
            }
        }
    });

    return {'focusInput': focusInput, 'error': countError};
}

function keyInput(this_obj) {
    this_obj.removeClass('input-warning');
    previewKeyFront();
}

function previewKeyFront() {
    let html = '';
    let kName = '';
    let keyDateTimeList = [];
    let keyDate = '';
    let keyTime = '';

    $('.key-val').each(function (idx, ele) {
        kName = $('#key_names_' + idx).val();
        html += '<p><span class="text-bold">' + kName + '</span> | ' + ele.value + '</p>';

        // if (kName == 'match_time') {
        //     if (ele.value) {
        //         keyDateTimeList = ele.value.split(' ');
        //         keyDate = keyDateTimeList[0];
        //         keyTime = keyDateTimeList[1];
        //         html += '<p><span class="text-bold">key_date</span> | ' + keyDate + ', <span class="text-bold">key_time</span> | ' + keyTime + '</p>';
        //     }
        // } else {
        //     html += '<p><span class="text-bold">' + kName + '</span> | ' + ele.value + '</p>';
        // }
        
    });

    $('.page-key-preview').html(html);
}

function percentFormat(num) {
    return num.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
}

function numberFormat(num) {
    return num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
}

function percentColor(percentDiff) {
    var color = 'text-white';
    
    if (percentDiff > 0) {
        color = 'text-success';
    } else if (percentDiff < 0) {
        color = 'text-danger';
    }

    return color;
}

function currentTimeStamp() {
    return Math.round(new Date().getTime() / 1000);
}

function checkShowTime(row) {
    var showTime = '';
    var time = showDateTimeFromTimeStamp(row.event_timestamp);

    if (row.statusShort == 'TBD' || row.statusShort == 'NS') {
        showTime = '<span class="text-danger">' + time + '</span>';
    } else if (row.statusShort == '1H' || row.statusShort == '2H') {
        showTime = '<span class="btn btn-live active">LIVE</span><span class="ml-2 text-danger">' + row.statusShort + ' ' + row.elapsed + '<span class="live">\'</span></span>';
    } else {
        showTime = row.statusShort;
    }

    return showTime;
}

function showDateTimeFromEventDate(iso_date, withDate = 0) {
    var date = new Date(iso_date);
    // var fullLocalFormat = date.toString(); // 'Sun Jul 19 2020 20:00:00 GMT+0700 (เวลาอินโดจีน)';
    return showRightTime(date, withDate);
}

function showDateTimeFromTimeStamp(iso_timestamp, withDate = 0) {
    var date = new Date(iso_timestamp * 1000);
    return showRightTime(date, withDate);
}

function showRightTime(date, withDate = 0) {
    var hours = date.getHours();
    var minutes = date.getMinutes();

    var formattedTime = padd(hours) + ':' + padd(minutes);

    if (withDate == 1) {
        var day = date.getDate();
        var month = date.getMonth();
        var enMonths = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        var dEn = enMonths[month] + ' ' + day;

        formattedTime = dEn + '<br>' + formattedTime;
    }

    return formattedTime;
}

function showDateYearFromTimeStamp(iso_timestamp) {
    var date = new Date(iso_timestamp * 1000);

    var day = date.getDate();
    var month = date.getMonth();

    var enMonths = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    var formattedTime = day + ' ' + enMonths[month] + ' ' + date.getFullYear();

    return formattedTime;
}

function dateShortFormat(day, month, year = 0) {
    var enMonths = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    var thMonths = ["ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค."];
    var y = (year + 543);
    var ystr = '' + y;

    var retFormat = day + ' ' + thMonths[month - 1] + ' ' + ystr.substr(2, 2);

    return retFormat;
}

function dateFullFormat(date, month, year = 0) {
    var enMonths = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    var thMonths = [ "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
    var y = (year + 543);
    var ystr = '' + y;

    var dayStr = new Date(year + '-' + padd(month) + '-' + padd(date));

    var retFormat = 'วัน' + getDayText(dayStr.getDay()) + 'ที่ ' + date + ' ' + thMonths[month - 1] + ' ' + ystr;

    return retFormat;
}

function getDayText(dayNum = 0) {
    // var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    var days = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];
    return days[dayNum];
}

function winStatusText(stand, compare) {
    var winText = '';

    if (stand > compare) {
        winText = '<div class="match-cube win flex-center">W</div>';
    } else if (stand == compare) {
        winText = '<div class="match-cube draw flex-center">D</div>';
    } else {
        winText = '<div class="match-cube lose flex-center">L</div>';
    }

    return winText;
}

function loadPlayerTransfers(fixtures, ret, name = '') {
    var teamIds = [];
    var teams = [];
    var teamSearch = {};

    if (fixtures.length > 0) {
        var homeName = '';
        var awayName = '';
        var hTeamId = 0;
        var aTeamId = 0;

        for (var i = 0; i < fixtures.length; i++) {
            hTeamId = fixtures[i].homeTeam.team_id;
            aTeamId = fixtures[i].awayTeam.team_id;
            homeName = fixtures[i].homeTeam.team_name;
            awayName = fixtures[i].awayTeam.team_name;

            if ($.inArray(hTeamId, teamIds) === -1) {
                teamIds.push(hTeamId);
                teams.push(fixtures[i].homeTeam);
            }

            if ($.inArray(aTeamId, teamIds) === -1) {
                teamIds.push(aTeamId);
                teams.push(fixtures[i].awayTeam);
            }

            if (homeName == name) {
                teamSearch = fixtures[i].homeTeam;
            }

            if (awayName == name) {
                teamSearch = fixtures[i].awayTeam;
            }
        }
    }

    if (ret == 'id') {
        return teamIds;
    } else if (ret == 'structure') {
        return teams;
    } else {
        return teamSearch;
    }
}

function padd(num) {
    return (parseInt(num) < 10) ? 0 + '' + num : num;
}

function twoDigit(number) {
    var formattedNumber = ('0' + number).slice(-2);
    return formattedNumber;
}