var util = require('./../util');
var config = require('./config.json');
var credentials_reader = require('./../config/credentials-reader');
var technologies_details = require('./technologies.json');
var social_handles = ['facebook', 'twitter', 'linkedin'];

var titles;
function get_title_map() {
    util.get_request(config["title_api"]["url"]).then((resp) => {
        titles = JSON.parse('{}');
        for(i in resp) {
            var title = resp[i];
            titles[title['title_standardized'].toUpperCase()] = title['title_id'];
        }
    });
}

get_title_map();

function authenticate_and_fetch_company_info(company_domain) {
    return new Promise(function (resolve, reject) {
        login().then((accessToken)=>{
            resolve(fetch_company_info(company_domain, accessToken));
        });
    });
}

function fetch_company_info(company_domain, accessToken) {
    payload = {
        "keyword": company_domain,
        "origin": 2,
        "page": 1,
        "pageSize": 10
    };
    headers = {
        "accessToken": accessToken
    };
    return new Promise(function (resolve, reject) {
        util.post_request(config["company_api"]["url"], payload, headers).then((resp)=>{
            resolve(JSON.parse(resp.responseText)['companies'][0]);
        });
    });

}

function login() {
    var credentials = credentials_reader.get("login_api");
    payload = {
        "email": credentials_reader.get("login_api.username"),
        "password": credentials_reader.get("login_api.password")
    }
    headers = {
        "tenantName": "data"
    }
    return new Promise(function (resolve, reject) {
        util.post_request(config["login_api"]["url"], payload, headers).then((r)=>{
            resolve(r.getResponseHeader('accessToken'));
        });
    });
}

function get_stakeholder_names(company_id, token, stakeholder_id) {
    return new Promise(function (resolve, reject) {
    payload = {
        "company_id": company_id,
        "origin": 2,
        "page": 1,
        "pageSize": 40,
        "title_ids": [stakeholder_id]
    }
    headers = {
        "accessToken": token
    }
    util.post_request(config["people_api"]["url"], payload, headers).then((r)=>{
        names = [];
        var people = JSON.parse(r.responseText)['people'];
        for(i in people) {
            names.push(people[i]['full_name']);
            console.log(people[i]['full_name']);
        }
        resolve(names);
    });
    });

}

module.exports = {

get_company_info: function get_company_info(company_domain){
    return authenticate_and_fetch_company_info(company_domain);
},

get_company_news: function get_company_news(company_domain) {
    var company_name = company_domain.split(".")[0];
    var url = config["news_api"]["url"].replace("{name}",company_name);
    return new Promise(function (resolve, reject) {
        util.get_request(url).then((resp) => {
        var info = {
            "name": company_name,
            "news": resp['articles']
        }
        resolve(info);
        });
    });
},

get_company_revenue: function get_company_revenue(company_domain) {
    company = authenticate_and_fetch_company_info(company_domain);
    info = {
        "name": company['name'],
        "revenue_range": company['revenue_million_dollars']
    }
    return info;
},

get_company_social_handle_info: function get_company_social_handle_info(company) {
    var handles = {};
    for(var i in social_handles) {
        social_handle_key="company_"+social_handles[i]+"_url";
        if(company[social_handle_key]) {
            handles[social_handles[i]] = company[social_handle_key];
        }
    }
    info = {
        "name": company['name'],
        "handles": handles
    }
    return info;
},

get_company_stakeholders: function get_company_stakeholders(company_domain, stakeholder) {
    return new Promise(function (resolve, reject) {
        stakeholder_id = titles[stakeholder.toUpperCase()];
        login().then((accessToken)=>{
            fetch_company_info(company_domain, accessToken).then((company_info) => {
                company_name = company_info['name'];
                company_id = company_info['id'];
                get_stakeholder_names(company_id, accessToken, stakeholder_id).then((stake_holder_names) => {
                    var info = {
                    "name": company_name,
                    "stakeholders": stake_holder_names
                    }
                    resolve(info);
                });


           });
       });
    });
},

get_company_technologies: function get_company_technologies(company_domain) {
    return new Promise(function (resolve, reject) {
    authenticate_and_fetch_company_info(company_domain).then((company)=> {
    technologies=JSON.parse('{}');
    for(key in technologies_details) {
        if(company[key]) {
            technologies[technologies_details[key]] = company[key].join();
        }
    }
    var info = {
        "name": company['name'],
        "technologies": technologies
    }

    resolve(info);
    });
    });
},

get_company_web_traffic: function get_company_web_traffic(company_domain) {
    return new Promise(function (resolve, reject) {
	    authenticate_and_fetch_company_info(company_domain).then((company)=> {
		    info = {
			"name": company['name'],
			"monthly_visits": company['monthly_visits']
		    }
		    resolve(info);
	    });
    });
},

get_company_omnichannel_presence: function get_company_omnichannel_presence(company_domain) {
    return new Promise(function (resolve, reject) {
    authenticate_and_fetch_company_info(company_domain).then((company)=> {
    var info = {
        "name": company['name'],
        "omnichannels": company['omnichannel_presence']
    }
    resolve(info);
    });
    });
},

get_company_shipping: function get_company_shipping(company_domain) {
    return new Promise(function (resolve, reject) {
        authenticate_and_fetch_company_info(company_domain).then((company)=>{
            info = {
                "name": company['name'],
                "shipping_volume": company['shipping_volume_range']
            }
            resolve(info);
        });
    });
},


get_social_handles : function get_social_handles() {
    return social_handles;
}

}
