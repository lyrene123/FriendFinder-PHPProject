var $startTime = $('#start');
var $endTime = $('#end');

var hours = ['0800', '0830', '0900', '0930', '1000', '1030', '1100', '1130'
    , '1200', '1230', '1300', '1330', '1400', '1430', '1500', '1530', '1600'
    , '1630', '1700', '1730', '1800', '1830', '1900', '1930', '2000', '2030'
    , '2100', '2130'];

hours.forEach(function (hour) {
    var hourStr = hour.substr(0, 2) + ':' + hour.substr(2);
    $startTime.append($('<option value="' + hour + '">' + hourStr + '</option>'));
    $endTime.append($('<option value="' + hour + '">' + hourStr + '</option>'));
})