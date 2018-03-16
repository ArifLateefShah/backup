var XMLHttpRequest = require("xmlhttprequest").XMLHttpRequest;
var default_headers = {
        "Content-Type": "application/json",
        "Accept": "*/*",
        "Access-Control-Allow-Origin": "*"
    }
module.exports = {
get_request: function get_request(url) {
    return new Promise(function (resolve, reject) {
	  var xhr = new XMLHttpRequest();  
	  var self = this;
	  xhr.open('GET', url);
	  xhr.onload = function () {
	    var res = JSON.parse(xhr.responseText);
            
	    resolve(res);		
	  }
	  xhr.send();
    });    
},

post_request: function post_request(url, payload, headers) {
    return new Promise(function (resolve, reject) {
	  var xhr = new XMLHttpRequest();  
	  var self = this;
	  xhr.open('POST', url);
          for (header in default_headers) {
              xhr.setRequestHeader(header, default_headers[header]);
          }
          for (header in headers) {
              xhr.setRequestHeader(header, headers[header]);
          }
	  xhr.onload = function () {
	    var res = JSON.parse(xhr.responseText);
	    resolve(xhr);
	  }
	  xhr.send(JSON.stringify(payload));
    });
    
},

underscore_separated_to_words: function underscore_separated_to_words(key) {
    words = key.split("_")
    display_string = ""
    for(i in words) {
        display_string=display_string+words[i];
        display_string=display_string+" ";
    }
    return display_string.trim();  
},

isEmpty: function isEmpty(value){
  return (value == null || value.length === 0);
},
getText: function getText(text) {
    return this.isEmpty(text) ? "N/A" : text;
},
getNumber: function getText(text) {
    return this.isEmpty(text) || text==0 ? "N/A" : parseFloat(text).toFixed(2);
}
}
