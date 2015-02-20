var timestamp;

function processJSON(jsonArray,parTimestamp) {
    timestamp = parTimestamp;

    var date = getDate(timestamp);
    $("#date").html(date);

    jsonArray.data.forEach(checkDate);
}

function checkDate(value, index, ar) {
    var i;
    var s = 1;
    if(value.date_ts == timestamp) {
        for(i = 0; i < value.items.length; i++) {
            $("#rooster_body").append(
                '<tr class="row'+s+'">' +
                    '<td class="time col1">'+value.items[i].t+'</td>' +
                    '<td class="class col2">'+value.items[i].v+'</td>' +
                    '<td class="room col3">'+value.items[i].r+'</td>' +
                    '<td class="teacher col4">'+value.items[i].l+'</td>' +
                '</tr>'
            );
            s++;
        }
    };
}

function getDate(timestamp) {
    var fulldate = new Date(timestamp);

    var day = arrDays[fulldate.getDay()];
    var date = addZero(fulldate.getDate());
    var month = arrMonths[fulldate.getMonth()];
    var year = fulldate.getFullYear();

    var formattedDate = day + ' ' + date + ' ' + month + ' ' + year;
    return formattedDate;
}

function getDatetime(timestamp) {
    var fulldate = new Date(timestamp);

    var day = arrDays[fulldate.getDay()];
    var date = addZero(fulldate.getDate());
    var month = arrMonths[fulldate.getMonth()];
    var year = fulldate.getFullYear();
    var hours = addZero(fulldate.getHours());
    var minutes = addZero(fulldate.getMinutes());
    var seconds = addZero(fulldate.getSeconds());

    var formattedDate = day + ' ' + date + ' ' + month + ' ' + year + ' | ' + hours + ':' + minutes + ':' + seconds;
    return formattedDate;
}

function addZero(i) {
    if(i < 10) {
        i = '0' + i;
    }
    return i;
}