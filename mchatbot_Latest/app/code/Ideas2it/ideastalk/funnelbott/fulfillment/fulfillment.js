var company_helper = require('./company-helper');
var util = require('./../util');
var strformat = require('strformat');

function get_company_info_formatted(company_domain) {
    return new Promise(function (resolve, reject) {
	    company_helper.get_company_info(company_domain).then((company_info)=>{
			var founded_year=company_info['founded_year']
			var employee_range=company_info['employees']
			var company_category=company_info['product_category']
			var holding_type=company_info['holding_type']
			var revenue_million = parseFloat(company_info['revenue_million_dollars'])
			var info =""
			var info_available = 0
			if(founded_year) {
			    info_available++;
			    info = info +"\n"+strformat("{name} was founded in {founded_year}",company_info);
			}
			if(company_category) {
			    info_available=info_available+1
			    info = info +"\n"+strformat("{name} falls under the '{product_category}' category", company_info)
			}
			if(employee_range) {
			    info_available=info_available+1
			    info = info+"\n"+strformat("It has {employees}.", company_info)
			}
			if(revenue_million > 0) {
			    info = info + "\n"+strformat("They make ${revenue_million_dollars} in revenue", company_info)
			}
            info = info + "\n"+get_company_social_handles_formatted(company_info);
			if(info_available > 0) {
			    info = strformat("Here is what you may need about {name}", company_info)+info
			} else {
			   info = strformat('Sorry, we do not have any info about {name} for now.', company_info)
			}
			resolve(info);
    	});
    });
}

function get_company_info(company_domain) {
    return new Promise(function (resolve, reject) {
    company_helper.get_company_info(company_domain).then((company_info)=>{
        resolve(JSON.stringify(company_info));
    });
    });
}

function get_company_social_handles(company) {
    var company_info = company_helper.get_company_social_handle_info(company);
    if(company_info['handles']) {

        for (key in company_info['handles']) {
            info = info +"\n"+strformat("{0} - {1}", [key.toUpperCase(), url=company_info['handles'][key]])
        }
        return info
    } else {
        return "The company does not seem to have any social handles right now!";
    }
}

function get_company_social_handles_formatted(company) {
    var company_info = company_helper.get_company_social_handle_info(company);
    if(company_info['handles']) {
        var info = strformat("{name} is present on, ",company_info);
        console.log(company_info['handles']);
        for (key in company_info['handles']) {
            info = info +"\n"+strformat("{0} - {1}", [key.toUpperCase(), url=company_info['handles'][key]])
        }
        return info
    } else {
        return "The company does not seem to have any social handles right now!";
    }
}

function get_company_revenue_formatted(company_domain) {
    var company_info = company_helper.get_company_revenue(company_domain);
    var revenue_million = float(company_info['revenue_range'])
    if(revenue_million > 50.0) {
        return "Whoa! {company} makes ${revenue_range} in revenue. Juicy!".format(company=company_info['name'], revenue_range=company_info['revenue_range'])
    } else if(revenue_million <=50.0 && revenue_million>0.0) {
        return "Cha-ching! Looks like {company} makes ${revenue_range} in revenue!".format(company=company_info['name'], revenue_range=company_info['revenue_range'])
    } else {
        return "Uh oh! We dont seem to have that on our records."
    }
}

function get_company_omnichannels_formatted(company_domain) {
    return new Promise(function (resolve, reject) {
    company_helper.get_company_omnichannel_presence(company_domain).then((company_info)=>{
    var omnichannels = company_info['omnichannels'];
    if(omnichannels.length == 3) {
        resolve(strformat("Wow, {name} is everywhere! They have an online store, a physical store and a mobile app.", company_info));
    } else if(omnichannels.length== 2) {
        resolve("Looks like the company has only "+util.underscore_separated_to_words(omnichannels[0])+" and "+util.underscore_separated_to_words(omnichannels[1]));
    } else {
        resolve("Aw, looks like the company has only "+omnichannels[0].split("_")[0]);
    }
    });
    });
}

function get_company_web_traffic_formatted(company_domain) {
    return new Promise(function (resolve, reject) {
		company_helper.get_company_web_traffic(company_domain).then((company_info)=>{
			if (util.isEmpty(company_info['monthly_visits'])) {
				resolve("Uh oh! We don’t seem to have that on our records");
			} else {
				resolve(strformat("{name} gets a whopping {monthly_visits} visits per month",company_info));
			}
		});
    });
}

function get_company_shipping_formatted(company_domain) {
    return new Promise(function (resolve, reject) {
		company_helper.get_company_shipping(company_domain).then((company_info)=>{
			if(util.isEmpty(company_info['shipping_volume'])) {
				resolve("Uh oh! We don’t seem to have that on our records");
			} else {
				resolve(strformat("{name} ships {shipping_volume} units per month", company_info));
			}
		});
    });
}

function get_company_technologies_formatted(company_domain) {
    return new Promise(function (resolve, reject) {
    company_helper.get_company_technologies(company_domain).then((company_info)=>{
    var company_name = company_info['name']
    var technologies_used = company_info['technologies']

    if(technologies_used.length==0) {
        resove("Uh oh! We don’t seem to have that on our records");
    } else {
        info=""
        for (key in technologies_used) {
            info=info+"For "+key+", the company uses "+technologies_used[key]+"\n";
        }
        resolve("Here are few technologies used by "+company_name+" and their key areas\n"+info);;
    }
    });
    });
}

function get_formatted_news_article(article) {
    var info = strformat("{title}-{url}", article);
    return info
}

function get_company_news_formatted(company_domain) {
    return new Promise(function (resolve, reject) {
        company_helper.get_company_news(company_domain).then((company_news)=>{
        var articles = company_news['news']
        var total_articles = articles.length;
        var count = 5
        if(total_articles < 5) {
            count = total_articles;
        }
        var info = strformat("Here are the top news about {name} for you",company_news);
        for (var x=0; x<count; x++) {
            article = articles[x]
            info=info+"\n"+get_formatted_news_article(article)
        }
        resolve(info);
        });
    });

}

function get_company_stakeholder_formatted(company_domain, stakeholder_title) {
    return new Promise(function (resolve, reject) {
    company_helper.get_company_stakeholders(company_domain, stakeholder_title).then((company_stakeholders_info)=>{
    var company_name = company_stakeholders_info['name']
    var stake_holder_names = company_stakeholders_info['stakeholders']
    if(stake_holder_names.length == 0) {
        resolve("Uh oh! We don’t seem to have that on our records");
    }
    var info = {
        stakeholder: stakeholder_title.toUpperCase(),
        people: stake_holder_names.join(),
        company: company_name
    }
    if(stake_holder_names.length>1) {
        resolve( strformat("The {stakeholder} role is fulfilled by {people}", info ));
    } else {
        resolve( strformat("Thats easy! The {stakeholder} of {company} is {people}", info));
    }
    });
    });
}

function fulfill_intents(intent_name, company_domain, stakeholder) {
    return new Promise(function (resolve, reject) {
    if(intent_name == 'company') {
        resolve(get_company_info(company_domain));
    } else if(intent_name == 'company-stakeholder' || intent_name == 'stakeholder of company') {
        resolve(get_company_stakeholder_formatted(company_domain, stakeholder.toUpperCase()));
    } else if( intent_name == 'company-omni-presence') {
         resolve(get_company_omnichannels_formatted(company_domain));
    } else if( intent_name == 'company-technologies') {
         resolve(get_company_technologies_formatted(company_domain));
    } else if( intent_name == 'company-web-traffic') {
         resolve(get_company_web_traffic_formatted(company_domain));
    } else if( intent_name == 'company-shipping') {
         resolve(get_company_shipping_formatted(company_domain));
    } else if( intent_name == 'company-news') {
        resolve(get_company_news_formatted(company_domain));
    } else {
        return {'speech': 'Sorry, we could not get you!'}
    }
});
}

module.exports = {

listen: function listen(bot) {
    bot.app.post("/fulfill", (req, res) => {
        var company_domain = req.body['result']['parameters']['domain'];
        var intent_name = req.body['result']['metadata']['intentName'];
        var stakeholder = req.body['result']['parameters']['stakeholder'];
	    fulfill_intents(intent_name, company_domain, stakeholder).then((speech)=>{

            res.send({'speech':speech});
        });

    });
}

}
