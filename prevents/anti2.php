<?php
$ua = $_SERVER['HTTP_USER_AGENT'];

// List of bot user agent keywords or patterns
$words = array(
    "bot",
    "crawler",
    "spider",
    "googlebot",
    "google",
    "bingbot",
    "yandexbot",
    "duckduckbot",
    "baiduspider",
    "slurp",
    "teoma",
    "sogou",
    "exabot",
    "facebot",
    "ia_archiver",
    "facebookexternalhit",
    "twitterbot",
    "discordbot",
    "AhrefsBot",
    "MJ12bot",
    "SemrushBot",
    "rogerbot",
    "DotBot",
    "Yeti",
    "Blekkobot",
    "adidxbot",
    "Dataprovider.com",
    "linkdexbot",
    "TurnitinBot",
    "archive.org_bot",
    "Gigabot",
    "voilabot",
    "Uptimebot",
    "Ezooms",
    "ltx71",
    "PaperLiBot",
    "Wotbox",
    "Nimbostratus-Bot",
    "ExaleadCloudview",
    "Qwantify",
    "Expanse",
    "MojeekBot",
    "CCBot",
    "Magpie-Crawler",
    "BLEXBot",
    "BUbiNG",
    "Cliqzbot",
    "coccoc",
    "rogerbot",
    "proximic",
    "CareerBot",
    "Nutch",
    "linkfluence",
    "Screaming Frog",
    "SiteExplorer",
    "BUbiNG",
    "YisouSpider",
    "meanpathbot",
    "InterfaxScanBot",
    "Barkrowler",
    "ZoominfoBot",
    "oBot",
    "cXensebot",
    "PiplBot",
    "archive.org_bot",
    "aiHitBot",
    "panscient.com",
    "SeznamBot",
    "HubSpot",
    "DeuSu",
    "SMTBot",
    "DeuSu",
    "Aboundex",
    "Cliqzbot",
    "aiHitBot",
    "TurnitinBot",
    "AddThis",
    "TweetmemeBot",
    "CareerBot",
    "DomainAppender",
    "Genieo",
    "Majestic12",
    "oBot",
    "PaperLiBot",
    "woriobot",
    "Xenu",
    "Xenu_Link_Sleuth",
    "ZumBot",
    "magpie-crawler",
    "rogerbot",
    "com Crawler",
    "ltx71",
    "okhttp",
    "R6_CommentReader",
    "R6_FeedFetcher",
    "R6_Spider",
    "scribdbot",
    "Zeus",
    "niki-bot",
    "SemrushBot",
    "bitlybot",
    "LinkpadBot",
    "blekkobot",
    "Qwantify",
    "Slackbot",
    "careerbot",
    "linkfluence",
    "Nimbostratus-Bot",
    "BUbiNG",
    "AdIdxBot",
    "barkrowler",
    "ccbot",
    "Cliqzbot",
    "DeuSu",
    "FyberSpider",
    "HubSpot",
    "IstellaBot",
    "linkdexbot",
    "Neevabot",
    "oBot",
    "Proximic",
    "QuerySeekerSpider",
    "rogerbot",
    "seznambot",
    "Twitterbot",
    "uMBot",
    "voilabot",
    "XoviBot",
    "BUbiNG",
    "careerbot",
    "Cliqzbot",
    "dotbot",
    "expanse",
    "infohelfer",
    "Instart",
    "integromedb",
    "Microsoft",
    "mj12bot",
    "Nimbostratus-Bot",
    "okhttp",
    "oBot",
    "prospectb2b",
    "SearchmetricsBot",
    "seznambot",
    "smtbot",
    "TurnitinBot",
    "voilabot",
    "ZoomBot",
    "ZoominfoBot",
    "ZumBot",
    "BUbiNG",
    "CareerBot",
    "Cliqzbot",
    "Infohelfer",
    "Integromedb",
    "integromedb",
    "Neevabot",
    "oBot",
    "prospectb2b",
    "R6_CommentReader",
    "R6_FeedFetcher",
    "R6_Spider",
    "Slackbot",
    "SMTBot",
    "Slackbot",
    "TurnitinBot",
    "voilabot",
    "woriobot",
    "XoviBot",
    "ZoomBot",
    "BUbiNG",
    "CareerBot",
    "Cliqzbot",
    "Infohelfer",
    "Integromedb",
    "Neevabot",
    "oBot",
    "prospectb2b",
    "R6_CommentReader",
    "R6_FeedFetcher",
    "R6_Spider",
    "Slackbot",
    "SMTBot",
    "Slackbot",
    "TurnitinBot",
    "voilabot",
    "woriobot",
    "XoviBot",
    "ZoomBot",
    "archive.org_bot",
    "Mediapartners-Google",
    "AdsBot-Google-Mobile",
    "AdsBot-Google",
    "FeedFetcher-Google",
    "bingbot",
    "msnbot-media",
    "msnbot",
    "Baiduspider",
    "YandexBot",
    "DuckDuckBot",
    "Sogou",
    "Exabot",
    "facebot",
    "ia_archiver",
    "facebookexternalhit",
    "Twitterbot",
    "discordbot",
    "firefoxbot",
    // Add more bot keywords or patterns as needed
);

// Loop through the list of bots and check if the user agent contains a keyword or pattern
foreach ($words as $word) {
    if (stripos(strtolower($ua), strtolower($word)) !== false) {
      // Block the bot by returning a 403 Forbidden response
      die("HTTP/1.0 403 Forbidden");
      exit;
    }
  }