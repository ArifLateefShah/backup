json = require('json-update');


module.exports = {

subscribe: function subscribe(userId, company) {
    return new Promise(function (resolve, reject) {
        json.load('pub-sub/company/subscriptions.json', function(err, subscriptions) {
            var company_subscribers = [];
            if(subscriptions.hasOwnProperty(company)) {
                company_subscribers=subscriptions[company];
                if(company_subscribers.indexOf(userId)!=-1) {
                    resolve("Seems you are already subscribed to "+company+"! You will receive alerts as usual.");
                }
            }              
            company_subscribers.push(userId);
            json.update('pub-sub/company/subscriptions.json',{[company]:company_subscribers}).then(function(dat) {
                    resolve("You are successfully subscribed to "+company+", you will receive any recent buzz that happens around instantly!");
            });
        });
    });
},

getAllSubscribers: function getAllSubscribers(company) {
    return new Promise(function (resolve, reject) {
        json.load('pub-sub/company/subscriptions.json', function(err, subscriptions) {
            if(subscriptions.hasOwnProperty(company)) {               
                resolve(subscriptions[company]);
            } else {
                resolve([]);
            }
        });
    });
}
}
