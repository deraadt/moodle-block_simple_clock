var serverClockShown = false;
var userClockShown = false;
var showSeconds = false;
var timeDifference = 0;
var refreshPeriod = 5000;

function initSimpleClock(YUIObject, server, user, seconds, y,mo,d,h,mi,s) {
    serverClockShown = server;
    userClockShown = user;
    showSeconds = seconds;
    var serverTimeStart = new Date(y,mo,d,h,mi,s);
    var currentTime = new Date();
    timeDifference = currentTime.getTime() - serverTimeStart.getTime();

    updateTime();
}

function updateTime() {

    // Update the server clock if shown
    if(serverClockShown) {
        var serverTime = new Date();
        serverTime.setTime(serverTime.getTime() - timeDifference);
        document.getElementById('serverTime').value = getClockString(serverTime);
    }

    // Update the user clock if shown
    if(userClockShown) {
        youTime = new Date();
        document.getElementById('youTime').value = getClockString(youTime);
    }

    // Refresh in 1 second
    timer = setTimeout('updateTime()',refreshPeriod);
}

function getClockString(clockTime) {
    var clockString = '';
    var hours = clockTime.getHours();
    var minutes = clockTime.getMinutes();
    var seconds = clockTime.getSeconds();

    // Add the hours
    if(hours>12) {
        clockString += hours-12;
    }
    else if (hours==0) {
        clockString += 12;
    }
    else {
        clockString += hours;
    }

    // Append a separator
    clockString += M.str.block_simple_clock.clock_separator;

    // Add the minutes
    if(minutes<10) {
        clockString += '0';
    }
    clockString += minutes;

    // Add the seconds if desired
    if(showSeconds) {
        clockString += M.str.block_simple_clock.clock_separator;
        if(seconds<10) {
            clockString += '0';
        }
        clockString += seconds;
    }

    // Add the am/pm suffix
    if(hours<12) {
        clockString += M.str.block_simple_clock.before_noon;
    }
    else {
        clockString += M.str.block_simple_clock.after_noon;
    }

    return clockString;
}