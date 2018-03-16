json = require('json-update');
var subscriber = require('./subscriber');

module.exports = {

listen: function listen(bot) {
    bot.app.post("/publish_company_alert", (req, res) => {
	var company = req.body['title'];
        var items = req.body['items'];
        var messages = []; 
        for(var i=0; i< items.length; i++) {
            messages.push(items[i]['title']+"-"+items[i]['permalinkUrl']);
        }
	subscriber.getAllSubscribers(company).then((subscribers)=>{
	    for(var i in subscribers) {
                var content = "Hi, you’re subscribed to "+company+", and I just found some news about them that might interest you";
		bot.sendTextMessage(subscribers[i], content);
                for(var j =0; j<messages.length;j++) {
                    bot.sendTextMessage(subscribers[i], messages[j]);
                }
	    }
	});

});
}
}
