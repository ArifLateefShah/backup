    var subscriber = require('./subscriber');
    module.exports = {
    listen: function listen(bot) {
        bot.app.post("/product_update", (req, res) => {
    	var product_id = req.body['product_id'];
              var message = req.body['message'];

    	subscriber.getAllSubscribers(product_id).then((subscriptions)=>{
                var content = "Update on the product that you bought recently, "+subscriptions["name"];
                var subscribers = subscriptions["user_ids"];
    	    for(var i in subscribers) {
                    bot.sendTextMessage(subscribers[i], content);
    		bot.sendTextMessage(subscribers[i], message);

    	    }
    	});

        });

        bot.app.post("/order_update", (request, res) => {
            console.log(request.body);
            var message = "Hi "+request.body.customerName+", thanks for shopping with us! Your order of "+request.body.productName+" is confirmed. "
            bot.sendTextMessage(request.body.id, message).then(()=>{
    	var payload = {
            "template_type":"receipt",
            "recipient_name":request.body.customerName,
            "order_number":request.body.orderID,
            "currency":"INR",
            "payment_method":"Cheque",
            "order_url":"https://chatbot.ideas2it.com/order?order_id="+request.body.orderID,
            "timestamp":"1428444852",
            "address":{
              "street_1":"Ideas2IT, RR 5",
              "street_2":"TVK Estate",
              "city":"Chennai",
              "postal_code":"600032",
              "state":"TN",
              "country":"IN"
            },
            "summary":{
              "subtotal":1800.57,
              "shipping_cost":0.0,
              "total_tax":0.00,
              "total_cost":1800.57
            },
            "elements":[
              {
                "title":request.body.productName,
                "subtitle":"",
                "quantity":request.body.productQty,
                "price":request.body.productPrice,
                "currency":"INR",
                "image_url":"https://magentobot.ideas2it.com/pub/media/catalog/product/m/t/mt07-gray_main.jpg"
              }
            ]
          }
        bot.sendTemplate(request.body.id, payload);
        });
        });

        bot.app.post("/promotion_alert", (request, res) => {
          console.log(request.body);
            var message = "Hi "+request.body.customerName+", here is another hot offer just for you! "
            bot.sendTextMessage(request.body.id, message).then(()=>{
            var payload = {
            "template_type":"generic",
            "elements":[
              {
                "title":request.body.title,
                "subtitle":request.body.sub_title,
                "image_url": request.body.image_url,
          			"default_action": {
          				"type": "web_url",
          				"url": request.body.callback_url,
          				"messenger_extensions": false,
          				"webview_height_ratio": "tall"
          			}
              }
            ]
          };
        bot.sendTemplate(request.body.id, payload);
        });
        });



},
    sendPromotions: function sendPromotions(convo) {
        var buttons= [
              {
                "title": "View More",
                "type": "postback",
                "payload": "payload"
              }
            ]
            convo.say("Here are the current hot promotions exclusively for you!").then(()=> {
                subscriber.getAllPromotions().then((promotions)=>{
                    convo.sendListTemplate(promotions, buttons).then(()=> {
                        convo.end();
                    });

    	    });
            });

    }
    }
