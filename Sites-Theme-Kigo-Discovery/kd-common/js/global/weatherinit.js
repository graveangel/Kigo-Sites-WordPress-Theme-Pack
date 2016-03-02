/* 
 * Array List of the 47 status code of the Yahoo weather
 */
var codeToClassname=["wi-tornado","wi-day-thunderstorm","wi-hurricane","wi-thunderstorm","wi-storm-showers","wi-rain-mix","wi-rain-mix","wi-rain-mix","wi-rain-mix","wi-snow","wi-rain-mix","wi-showers","wi-showers","wi-snow","wi-snow","wi-rain-mix","wi-snow","wi-rain-mix","wi-hail","wi-fog","wi-fog","wi-fog","wi-fog","wi-fog","wi-cloudy-gusts","wi-cloudy-gusts","wi-cloudy","wi-night-cloudy","wi-day-cloudy","wi-night-partly-cloudy","wi-day-cloudy","wi-night-clear","wi-day-sunny","wi-night-clear","wi-day-sunny","wi-rain-mix","wi-hot","wi-storm-showers","wi-storm-showers","wi-storm-showers","wi-showers","wi-snow","wi-showers","wi-rain-mix","wi-cloudy","wi-storm-showers","wi-hail","wi-storm-showers"];
function getClassnameForCode(code){
    if(code < 0 || code > 47){
        return 'wi-na';
    }
    return codeToClassname[code];
}
