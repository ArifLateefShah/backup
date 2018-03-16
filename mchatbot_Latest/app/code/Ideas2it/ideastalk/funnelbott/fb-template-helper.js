var company_helper = require('./fulfillment/company-helper');
var util = require('./util');
const getSocialHandlesAsButtons = (company) => {
    var buttons = [];
    var social_handles = company_helper.get_social_handles();
    for(var i in social_handles) {
        var social_handle_key="company_"+social_handles[i]+"_url";
        if(!util.isEmpty(company[social_handle_key])) {
            var button = {
                "type":"web_url",
                "url":company[social_handle_key],
                "title":social_handles[i]
              }
             buttons.push(button);
        }
    }
    return buttons;
}

module.exports = {

getCompanyDetailsAsGenericTemplate: function getCompanyDetailsAsGenericTemplate(company) {
    var elements=[
     {
      "title": "Here is what you may need about "+company['name'],
      "image_url":"https://logo.clearbit.com/"+company['domain'],
      "subtitle":util.getText(company['product_category'])+"\n"+util.getText(company['employees'])+"\nFounded: "+util.getText(company['founded_year'])+"\nRevenue($M): "+util.getNumber(company['revenue_million_dollars']),

      "buttons":getSocialHandlesAsButtons(company)
    }
    ];

    return elements;
}

}
