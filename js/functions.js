var dateTs;
var timestamp;
var yesterdayTs;
var yesterdayDateTs;
var tomorrowDateTs;
var tomorrowTs;
var JSONArr;

function processJSON(jsonArray,preDateTs,preTimestamp) {
	JSONArr = jsonArray;
	dateTs = preDateTs;
    timestamp = preTimestamp;

	yesterdayTs = getYesterdayTS(timestamp);
	tomorrowTs = getTomorrowTS(timestamp);
	yesterdayDateTs = formatDateTs(yesterdayTs);
	tomorrowDateTs = formatDateTs(tomorrowTs);

    var date = getDate(timestamp);
    $("#date").html(date);
	$("#rooster_body").html('');

    var yesterday = getYesterday(timestamp);
    var tomorrow = getTomorrow(timestamp);
    $(".day-previous").html(yesterday);
    $(".day-next").html(tomorrow);

	var pattern = '^'+dateTs;

    for(var i = 0; i < JSONArr.length; i++) {
	    if(doRegCheck(pattern,JSONArr[i].date)) {
		    var s = 1;
		    for(var countr = 0; countr < JSONArr[i].items.length; countr++) {
			    $("#rooster_body").append(
				    '<tr class="row'+s+'">' +
					    '<td class="time col1">'+JSONArr[i].items[countr].t+'</td>' +
					    '<td class="class col2">'+JSONArr[i].items[countr].v+'</td>' +
					    '<td class="room col3">'+JSONArr[i].items[countr].r+'</td>' +
					    '<td class="teacher col4">'+JSONArr[i].items[countr].l+'</td>' +
				    '</tr>'
			    );
			    s++;
		    }
	    }
    }

	if($("#rooster_body").html() === '') {
		$("#rooster_body").append(
			'<tr>' +
				'<td colspan="4" class="center">Er zijn geen lessen voor vandaag gevonden.</td>' +
			'</tr>'
		);
	}
}

function goToNextDay() {
	timestamp = tomorrowTs;
	dateTs = tomorrowDateTs;
	processJSON(JSONArr,dateTs,timestamp);
}

function goToPreviousDay() {
	timestamp = yesterdayTs;
	dateTs = yesterdayDateTs;
	processJSON(JSONArr,dateTs,timestamp);
}

function getYesterday(timestamp) {
    var fulldate = new Date(timestamp);

    fulldate.setDate(fulldate.getDate() - 1);
    var day = arrDays[fulldate.getDay()];
    return day;
}

function getYesterdayTS(timestamp) {
	var fulldate = new Date(timestamp);

	fulldate.setDate(fulldate.getDate() - 1);
	var yesterdayTS = fulldate.getTime();
	return yesterdayTS;
}

function getTomorrow(timestamp) {
    var fulldate = new Date(timestamp);

    fulldate.setDate(fulldate.getDate() + 1);
    var day = arrDays[fulldate.getDay()];
    return day;
}

function getTomorrowTS(timestamp) {
	var fulldate = new Date(timestamp);

	fulldate.setDate(fulldate.getDate() + 1);
	var tomorrowTS = fulldate.getTime();
	return tomorrowTS;
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

function addZero(i) {
    if(i < 10) {
        i = '0' + i;
    }
    return i;
}

function doRegCheck(pattern,subject) {
	var regex = new RegExp(pattern);
	return regex.test(subject);
}

function formatDateTs(ts) {
	var fullDate = new Date(ts);

	var date = addZero(fullDate.getDate());
	var month = addZero(fullDate.getMonth()+1);
	var year = fullDate.getFullYear();

	return year + '-' + month + '-' + date;
}