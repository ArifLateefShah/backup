'use strict';
var each = require('async-each-series');
var nlp = require('./nlp/nlp-connector');
var credentials_reader = require('./config/credentials-reader');
var fulfillment = require('./fulfillment/fulfillment');
var company_subscriber = require('./pub-sub/company/subscriber');
var company_publisher = require('./pub-sub/company/publisher');
var ecommerce_publisher = require('./pub-sub/ecommerce/publisher');
var fbconnector = require('./fb-connector');
var fbtemplate = require('./fb-template-helper');
var util = require('./util');

const listenToBot = (bot) => {
    bot.on('message', (payload, chat) => {
    var text = payload.message.text;
    
    if(text.indexOf("Offers") > -1) {
      console.log('In Offers');
        chat.conversation((payload) => {
            ecommerce_publisher.sendPromotions(payload);
        });
    } else if(text.indexOf("hello bot") > -1) {
            console.log('inside hello bot');
            chat.conversation((payload) => {
            helpWithProduct(payload);
        });
    } else if(text.indexOf("Help") > -1) {
            console.log('inside Help in first listen');
            chat.conversation((payload) => {
            helpWithAvailableOptions(payload);
        });
    } else if(text.indexOf("Similar Products") > -1) {
            console.log('inside Similar Products in first line');
            chat.conversation((payload) => {
            startConvo(payload);
        });
    } else  {
          chat.conversation((convo) => {
          chat.getUserProfile().then((user) => {
              console.log('Inside getUserProfile');
              var userId = payload.sender.id;
              console.log(payload);
              convo.set("userId", userId);
          });
  	      startConvo(convo, text);
        });
 }

  });
}

const bootUp = () => {
    credentials_reader.load();
    const bot = fbconnector.load_bot();
    listenToBot(bot);
    company_publisher.listen(bot);
    ecommerce_publisher.listen(bot);
    fulfillment.listen(bot);
    bot.start(3000,'192.168.1.83');
}

bootUp();

const startConvo = (convo, text) => {
       // console.log("Here in startConvo");
       //  return false; 
        nlp.setSessionId(Math.random().toString(36).slice(2));
        nlp.initiateRequest(text).then((resp) => {
            if(resp.result.action === 'sendMeorderDetails'){
                console.log("sendMeorderDetails status is working",resp.result.fulfillment.data);
                var products = resp.result.fulfillment.data; 
                displayOrders(convo, products);
               // convo.end();
            } else if(resp.result.action === 'getOtherProductColors'){
                console.log("getOtherProductColors status is working",resp.result.fulfillment.data);
                var OtherProductColors = resp.result.fulfillment.data;
                displayProducts(convo, OtherProductColors);
               // convo.end();
            } else if (resp.result.action === 'getSimilarProducts'){
              console.log('getting inside the similar products');
                var similarProducts = resp.result.fulfillment.data; 
                displaySimilarProducts(convo, similarProducts);
                // repeatAsk(convo);
            } else if(resp.result.action === 'PayTestParam'){
                 console.log('its PayTestParam');
                  console.log(resp.result.fulfillment.data);
                  var products = resp.result.fulfillment.data; 
                  displayOrders2(convo, products);
                 // convo.end();
            } else if(resp.result.action === 'orderStatus'){
                  console.log('its orderStatus');
                  console.log(resp.result.fulfillment.data);
                  var products = resp.result.fulfillment.data; 
                  displayOrders(convo, products);
                 // convo.end();
            } else {
               informUser(convo, resp.result.fulfillment.messages[0].speech);
              // convo.end();
            }
        }, (err) => {
          console.log('err----------------', err);
        });
       // convo.end();

};

const repeatAsk = (convo) => {
    const question = {
  	text: 'Do you need any other info?',
	  quickReplies: ['Help']
};

    convo.ask(question,  (payload, convo) => {
    	 const text = payload.message.text;
       console.log('coming inside repekadAsk',text);

        if(text.indexOf("info") > -1) {
             startConvo(convo, text);
         } else if(text.indexOf("Help") > -1) {
            console.log('coming inside the help');
             helpWithAvailableOptions(convo);
             // convo.end();
         } else if(text=="Size") {
             helpWithAvailableSize(convo);
            // convo.end();
         } else if(text.indexOf("subscription")>-1) {
              console.log("its in subscription");
              company_subscriber.subscribe(convo.get("userId"), convo.get("domain").split(".")[0]).then((resp) => {
                     informUser(convo, resp);
             });
            // convo.end();
         } else if(text.indexOf("Promos") > -1) {
               console.log('its in promos');
               ecommerce_publisher.sendPromotions(convo);
              // convo.end();
         } else if(text.indexOf("Similar Products") > -1) {
               console.log('its in promos');
               startConvo(convo, text);
              // convo.end();
         } else {
                  console.log('coming here');
                  nlp.followUpRequest(text, convo).then((resp) => {
               
                  if(resp.result.action == 'sendMeorderDetails'){
                      console.log("sendMeorderDetails status is working");
                      var products = resp.result.fulfillment.data; 
                      displayOrders(convo, products);
                  }
                  convo.set("domain", resp.result.parameters.domain);

                 informUser(convo, resp.result.fulfillment.messages[0].speech);
                // convo.end();

             },(err) => {
                console.log('some eror----',err);
               // convo.end();
             });
         }

    });
};





const helpWithAvailableOptions = (convo) => {
    convo.ask({
                  text: "I can help you retrieve a variety of information! Here are some of the things I can do ",
	          quickReplies: ['Similar Products','Subscribe', 'Feedback', 'Order status']
              }, (payload, convo) => {
                   const text = payload.message.text;
                   nlp.followUpRequest(text, convo).then((resp) => {
                       informUser(convo, resp.result.fulfillment.messages[0].speech);
                   });
    });
    // convo.end();
}

const informUser = (convo, speech) => {

    var messages = speech.split("\n");
    console.log(messages);
    // return false;

    each(messages, function(message, next){
        convo.say(message).then(()=> {
          setTimeout(function() {
              next();
          }, 1000);

        });

    }, function(err){
           repeatAsk(convo);
    }
    );

}

const sendCompanyInfoToUser = (convo, resp) => {
    return convo.sendGenericTemplate(fbtemplate.getCompanyDetailsAsGenericTemplate(resp));

}

const displayOrders = (convo, product) => {
      var product = JSON.parse(product);
      // console.log(product);
      // return false;
      // console.log('its comming to displayOrders',product.productImage);

      // return false;

      convo.say({
        cards: [
          {    title: product.productName,
               image_url: product.productImage,
               "subtitle":product.productStatus,
               default_action: {
                 "type": "web_url",
                 "url": "www.magentobot.ideas2it.com",
                 "messenger_extensions": false,
                 "webview_height_ratio": "tall"
                } 
          }]
      });
     convo.end();    
}
const displaySimilarProducts = (convo, product) => {
      var products = JSON.parse(product);
      var oSimilarproducts = [];

      for(var key in products){
          var item = {};
          item ["title"] = products[key].productName;
          item ["image_url"] = products[key].productImage;
          
          var daction = {};
          daction ["type"] = "web_url";
          daction ["url"] = products[key].productUrl;
          daction ["messenger_extensions"] = false;
          daction ["webview_height_ratio"] = "tall";
          
          item ["default_action"] =  daction;
          if(key > 9)
            continue;
          oSimilarproducts.push(item);
          
      }
      console.log(oSimilarproducts);
      convo.say({ cards: oSimilarproducts }).then((resp) => {
                       console.log('coming hereeeeeeeeeeeee',resp);
                       informUser(convo, "Here are similar products for you");
                       
                });  
    convo.end();
}

const displayProducts = (convo, product) => {
      var product = JSON.parse(product);
      convo.say({
        cards: [
          {    title: product.productName,
               image_url: product.productImage,
               "subtitle":product.productStatus,
               default_action: {
                 "type": "web_url",
                 "url": "www.magentobot.ideas2it.com",
                 "messenger_extensions": false,
                 "webview_height_ratio": "tall"
                } 
          }]
      });
     convo.end();
}

const displayOrders2 = (convo, product) => {
      var product = JSON.parse(product);
      console.log('its comming to displayOrders',product);
      convo.say({
        cards: [
          {    title: product.productName,
               image_url: product.productImage,
              default_action: {
              "type": "web_url",
              "url": product.productLink,
              "messenger_extensions": false,
              "webview_height_ratio": "tall"
            } },
          { 
              title: product.productName,
              image_url: product.productImage,
              default_action: {
              "type": "web_url",
              "url": product.productLink,
              "messenger_extensions": false,
              "webview_height_ratio": "tall"
          } }
        ]
      });
     convo.end();
}

const helpWithProduct = (convo) => {
    convo.ask({
                  text: "You are on this product page from long time,looks you need help. Do you need help in ",
            quickReplies: ['Color', 'Price', 'Size']
              }, (payload, convo) => {
                   const text = payload.message.text;
                   nlp.followUpRequest(text, convo).then((resp) => {
                    console.log(resp);
                    // return false;
                       informUser(convo, resp.result.fulfillment.messages[0].speech);
                   });
    });
   convo.end();
}

const helpWithAvailableSize = (convo) => {
    convo.ask({
                  text: "Which size you are looking for ",
            quickReplies: ['Small', 'Medium', 'Large']
              }, (payload, convo) => {
                   const text = payload.message.text;
                   nlp.followUpRequest(text, convo).then((resp) => {
                    console.log(resp);
                    // return false;
                       informUser(convo, resp.result.fulfillment.messages[0].speech);
                   });
    });
   convo.end();
}
