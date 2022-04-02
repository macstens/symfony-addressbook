const $ = require('jquery');
//require('bootstrap');
//require('canvas-confetti');

var party = require('./party');

if($('.app-flash--success').length) {
    party();
}