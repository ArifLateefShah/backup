var PropertiesReader = require('properties-reader');
  var properties;
  module.exports = {
  load: function load() {
      properties = PropertiesReader('config/credentials.properties').getAllProperties();
  },
  get: function get(property) {
    return properties[property];
  }
}
