 const CONFIG = require('./settings');
  const CalendarAPI = require('node-google-calendar');
  let cal = new CalendarAPI(CONFIG);  
var cron = require('node-cron');
var fulfillment = require('.././fulfillment/fulfillment');
var fbconnector = require('.././fb-connector');

const bot = fbconnector.bot();

function getEvents() {
var timeNow = new Date();
var timeFormat = timeNow.toISOString().replace(/\..+/, '')+"+00:00";        
var timeNext = new Date(timeNow.getTime() + 30*60000);
var timeNextFormat = timeNext.toISOString().replace(/\..+/, '')+"+00:00";         
let params = {
	timeMin: timeFormat,
	timeMax: timeNextFormat,
	singleEvents: true,
	orderBy: 'startTime'
}; 	

cal.Events.list('madan.innovative@gmail.com', params)
  .then(json => {
	//Success
        var timeToMeet = Math.floor((new Date(json[0].start.dateTime).getTime()-new Date().getTime())/60000);
        var attendee = json[0].attendees[1].email;
        var person = capitalize(attendee.split("@")[0]);
        var domain = attendee.split("@")[1];
        var message = "Hello, are you ready for '"+json[0].summary+"' scheduled with "+person+" from "+domain +" in another "+timeToMeet+" mins";
        
        fulfillment.fulfill_intents('company', domain, '').then((speech)=>{
            bot.sendTextMessage('1482679921851225', message).then(()=>{
                bot.sendTextMessage('1482679921851225', speech);
            });
        });
  }).catch(err => {
	//Error
	console.log('Error: listSingleEvents -' + err.message);
  });

}


function capitalize(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

module.exports = {

start: function start(company) {
    cron.schedule('*/10 * * * * *', function(){
        getEvents();
    });
}
}
