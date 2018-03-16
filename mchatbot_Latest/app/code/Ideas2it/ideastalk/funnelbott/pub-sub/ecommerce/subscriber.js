json = require('json-update');


module.exports = {

getAllPromotions: function getAllPromotions() {
    console.log("Its comming here in Subscriber.js");
return new Promise(function (resolve, reject) {
        json.load('pub-sub/ecommerce/promotions.json', function(err, promotions) {             
                resolve(promotions["elements"]);
        });
    });
},

getAllSubscribers: function getAllSubscribers(product_id) {
    return new Promise(function (resolve, reject) {
        json.load('pub-sub/ecommerce/subscriptions.json', function(err, subscriptions) {
            if(subscriptions.hasOwnProperty(product_id)) {               
                resolve(subscriptions[product_id]);
            } else {
                resolve([]);
            }
        });
    });
}
}
