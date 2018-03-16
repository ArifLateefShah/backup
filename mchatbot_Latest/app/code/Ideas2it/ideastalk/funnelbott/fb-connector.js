const BootBot = require('bootbot');
var credentials_reader = require('./config/credentials-reader');
var fbbot="";
module.exports = {

load_bot: function bot() {
  if(fbbot=="")
    fbbot = new BootBot({
      accessToken: credentials_reader.get("fb_messenger.accessToken"),
      verifyToken: credentials_reader.get("fb_messenger.verifyToken"),
      appSecret: credentials_reader.get("fb_messenger.appSecret")
    });
  return fbbot;
}

}
