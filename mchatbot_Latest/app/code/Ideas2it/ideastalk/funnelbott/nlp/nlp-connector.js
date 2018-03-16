var XMLHttpRequest = require("xmlhttprequest").XMLHttpRequest;
var credentials_reader = require('./../config/credentials-reader');
var sessionId="";

function sendGetXHR(query, sessionId) {
  return new Promise(function (resolve, reject) {
	  var API = 'https://api.dialogflow.com/v1/query?v=20150910&lang=en&sessionId='+sessionId;
	  var xhr = new XMLHttpRequest();
	  var self = this;
	  xhr.open('GET', API + "&query="+query);
	  xhr.setRequestHeader("Authorization", "Bearer "+credentials_reader.get("nlp-client-token"));
	  xhr.onload = function () {
	    var res = JSON.parse(xhr.responseText)
	    resolve(res);
	  }
	  xhr.send();
 });
}

 function sendPostXHR(query, data) {
  return new Promise(function (resolve, reject) {
	  var API = 'https://api.dialogflow.com/v1/query?v=20150910';
	  var xhr = new XMLHttpRequest();
	  var self = this;
	  xhr.open('POST', API);
	  xhr.setRequestHeader("Authorization", "Bearer "+credentials_reader.get("nlp-client-token"));
          xhr.setRequestHeader("Content-Type", "application/json");
	  xhr.onload = function () {
	    var res = JSON.parse(xhr.responseText)
	    resolve(res);
	  }
	  xhr.send(JSON.stringify(data));
 });
}


module.exports = {
setSessionId: function setSessionId(id) {
    sessionId = id;
},
initiateRequest: function initiateRequest(query) {
	console.log(query);
    return sendGetXHR(query, sessionId);
},

followUpRequest: function followUpRequest(query, convo) {
    var data = {
	"query": query,
	"sessionId": sessionId,
	"contexts": [{"name":"company-followup","parameters": {"domain": convo.get('domain')}}],
	"lang": "en"
    }
    return sendPostXHR(query, data);
}
}
