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
    if(text.indexOf("promotions") > -1) {
        chat.conversation((convo) => {
            ecommerce_publisher.sendPromotions(convo);
        });
    } else  {
        chat.conversation((convo) => {
          chat.getUserProfile().then((user) => {
              convo.set("userId", user.id);
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
        nlp.setSessionId(Math.random().toString(36).slice(2));
        nlp.initiateRequest(text).then((resp) => {
        console.log(resp);		
            informUser(convo, resp.result.fulfillment.speech);
        });

};

const repeatAsk = (convo) => {
    const question = {
	text: 'Do you need any other info?',
	quickReplies: ['Help']
    };

    convo.ask(question,  (payload, convo) => {
	 const text = payload.message.text;
         if(text.indexOf("info") > -1) {
             //convo.end();
             startConvo(convo, text);
         }else if(text=="Help") {
             helpWithAvailableOptions(convo);
         } else if(text.indexOf("subscribe")>-1) {
             company_subscriber.subscribe(convo.get("userId"), convo.get("domain").split(".")[0]).then((resp) => {
                     informUser(convo, resp);
             });

         } else if(text.indexOf("promotions") > -1) {
               ecommerce_publisher.sendPromotions(convo);

         } else {
             nlp.followUpRequest(text, convo).then((resp) => {
                 convo.set("domain", resp.result.parameters.domain);
                 informUser(convo, resp.result.fulfillment.speech);

             });
         }

    });
}

const helpWithAvailableOptions = (convo) => {
    convo.ask({
                  text: "I can help you retrieve a variety of information about "+convo.get("domain")+", Here are some of the things I can do ",
	          quickReplies: ['Subscription', 'Feedback', 'Order Issues', 'Deals']
              }, (payload, convo) => {
                   const text = payload.message.text;
                   nlp.followUpRequest(text, convo).then((resp) => {
                       informUser(convo, resp.result.fulfillment.speech);
                   });
    });
}

const informUser = (convo, speech) => {

    var messages = speech.split("\n");
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
