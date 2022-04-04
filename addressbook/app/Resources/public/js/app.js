const $ = require('jquery');
//require('bootstrap');

var party = require('./party');

if($('.app-flash--success').length) {
    party();
}