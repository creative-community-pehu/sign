<?php

date_default_timezone_set('Asia/Tokyo');

function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

$today = date("Ymd");
$source_file = $today . ".csv";

$symbol = (string)filter_input(INPUT_POST, 'symbol');
$color = (string)filter_input(INPUT_POST, 'color');
$timestamp = date("j.M.y.D g:i:s A");

$forwardedFor = $_SERVER["REMOTE_ADDR"];
$ips = explode(",", $forwardedFor);
$ip = $ips[0];

$fp = fopen($source_file, 'a+b');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    flock($fp, LOCK_EX);
    fputcsv($fp, [$symbol, $color, $timestamp, $ip,]);
    rewind($fp);
}
flock($fp, LOCK_SH);
while ($row = fgetcsv($fp)) {
    $rows[] = $row;
}
flock($fp, LOCK_UN);
fclose($fp);

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <title>πΏππ π­ππ¬ πΏππππ</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="refresh" content="60; URL=">
    <style>
        body,
        #sign {
            padding: 0;
            margin: 0;
        }
        
        #mod {
            position: relative;
            top: 0;
            width: 100%;
            height: auto;
            background-color: #fff;
            border-bottom: solid 1px #000;
            padding: 1rem 0 0;
            margin: 0;
            color: #000;
            font-family: 'Times New Roman', serif;
            text-align: center;
        }
        
        #mod b {
            display: block;
            margin: 0;
            padding: 0;
        }
        
        #mod #ed {
            padding: 0 0.25rem;
            font-size: 3.33rem;
            transform: scale(1, 1.5);
        }
        
        #mod #credit,
        #mod #today {
            position: absolute;
            display: block;
        }
        
        #mod #today {
            top: 0;
            left: 0;
            width: 12.5rem;
            height: 5rem;
            margin: 0.25rem 1rem;
            padding: 0;
            border: solid 1px #000;
            font-weight: 500;
            font-stretch: condensed;
            font-variant: common-ligatures tabular-nums;
            display: inline-block;
            transform: scale(1, 1.1);
            word-spacing: -.25ch;
        }
        
        #mod sup {
            font-size: 0.75rem;
            line-height: 200%;
            width: 90%;
            position: absolute;
            top: 50%;
            left: 50%;
            -webkit-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
        }
        
        #mod #credit {
            font-size: 0.55rem;
            text-align: right;
            height: 5rem;
            top: 0;
            right: 0;
            margin: 0.25rem 1rem;
            width: 12.5rem;
            text-align: justify;
            word-wrap: break-word;
            letter-spacing: 0.05em;
        }
        
        #mod #credit b {
            text-align: center;
            font-size: 150%;
            padding: 0.25rem 0 0.5rem;
            font-family: Arial, sans-serif;
        }
        
        #collection {
            position: relative;
            font-size: 0.75rem;
            letter-spacing: .5rem;
            padding: 0.125rem 0;
            margin: 1rem 0 0;
            border-top: 1px solid #000;
        }
        
        #collection marquee {
            display: block;
        }
        
        #collection ul {
            padding: 0;
            margin: 0 0.5rem;
            min-height:1.5rem;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            display: -ms-flexbox;
            display: flex;
            white-space: nowrap;
            -webkit-justify-content: space-between;
            justify-content: space-between;
            -webkit-flex-direction: row-reverse;
            flex-direction: row-reverse;
        }
        
        #collection li {
            list-style: none;
            position: relative;
            display: block;
            width: 1.5rem;
            height: 1.5rem;
            min-width: 1.5rem;
            min-height: 1.5rem;
            white-space:normal;
            padding: 0;
            margin: 0.25rem 0.5rem;
        }
        
        #collection i {
            display:inline-block;
            padding: 0.25rem 0 0;
        }
        
        #collection li b {
            position: absolute;
            display: inline-block;
            font-size:1rem;
            top: 50%;
            left: 50%;
            -webkit-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
        }
        
        #sign {
            position: fixed;
            bottom: 0;
            right: 0;
            z-index: -1;
            width: 100%;
            height: 100vh;
            overflow: hidden;
            pointer-events: none;
            user-select: none;
        }
        
        #sign iframe {
            border: none;
            width: 100%;
            height: 100%;
        }
        
        #weather {
            position: fixed;
            bottom: 0;
            width:100%;
            display: block;
            font-size: 0.75rem;
            letter-spacing: .5rem;
            padding: 0.25rem 0;
            margin: 1rem 0 0;
        }
        
        #weather span {
            font-family: 'Times New Roman', serif;
            font-weight: 500;
            font-stretch: condensed;
            font-variant: common-ligatures tabular-nums;
            display: inline-block;
            transform: scale(1, 1.1);
            word-spacing: -.25ch;
        }

        #credit .print {
            display:none;
        }
        
        #credit .display {
            display:block;
        }
        
        
        @media print {
            #sign {
                height: 87.5vh;
            }
            #mod #ed {
                padding: 0.25rem 0.25rem 0;
                transform: scale(0.60, 1.75);
            }
        
        #mod #today,        
        #mod #credit {
            margin: 1rem;
        }
            #credit .print {
                display:block;
            }
            #credit .display,
            #weather {
                display:none;
            }
            #collection ul {
                overflow-x: hidden;
            }
        }
    </style>
</head>
  <script>
  if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
    redirect:window.location.replace("mobile.php");
  }
  </script>

<body>
    <div id="mod">
        <b id="ed">πΏππ π­ππ¬ πΏππππ</b>
        <p id="today">
            <sup style="text-transform: uppercase;">
            <?php
            date_default_timezone_set('Asia/Tokyo');
            $w = date("w");
            $week_name = array("ζ₯", "ζ", "η«", "ζ°΄", "ζ¨", "ι", "ε");
            print(date('Y εΉ΄ n ζ j ζ₯'). " ($week_name[$w])")
            ?>
            <br/>δ»ζ₯γ?ζ°ζγ‘γθ‘¨γθ²γ¨θ¨ε·</sup>
        </p>

        <div id="credit">
            <b class="display">ε?Ώζ³θιε?</b>
            <span class="display">35 γ? θ¨ε· γ¨ 18 γ? θ² γγ δ»γ?ζ°ζγ‘γ«εγθ²γ¨θ¨ε·γζη¨Ώγγγγ?γ€γ³γΏγΌγγγγ’γΌγδ½εγ?εΆδ½γ«εε γ§γγΎγγ</span>
            <b class="print">Colors and Symbols</b>
            <span class="print">This is The Collection of Colors and Symbols That Fits On Today.</span>
            <span class="print">Those Colors and Symbols had Posted by Today's Visitors of BnA Alter Museum for Create this Work.</span>
        </div>
        <div id="collection">
                <ul class="flash">
                    <?php if (!empty($rows)): ?>
                    <?php foreach ($rows as $row): ?>
                    <li style="background:#<?=h($row[1])?>;">
                        <b class="symbol" style="color:#<?=h($row[1])?>; filter: invert();"><?=h($row[0])?></b>
                    </li>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <i style="color:#000;">No Posts Yet, Today</i>
                    <?php endif; ?>
                </ul>
        </div>
    </div>

    <div id="sign">
        <iframe src="background.php"></iframe>
    </div>
    
    <div id="weather">
        <marquee></marquee>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        var messageList = $('#weather marquee');

        //openweathermapοΌε€©ζ°δΊε ±APIοΌγ«ζ₯ηΆ
        var request = new XMLHttpRequest();
        var targetCityName = "kyoto";
        var appId = "557b466129cf7d7427b03e5b7886a4bb";
        var owmURL = "https://api.openweathermap.org/data/2.5/weather?APPID=" + appId + "&lang=ja&units=metric&q=" + targetCityName + ",jp;";


        request.open('GET', owmURL, true);
        //η΅ζγjsonεγ§εγεγ
        request.responseType = 'json';

        request.onload = function() {
            var data = this.response;
            console.log(data);
            var messageElement = $(
                "<span>" +
                data["name"] +
                " - " +
                data["weather"][0]["description"] +
                " | " +
                data["weather"][0]["main"] +
                " | ζ°ζΈ© " +
                data["main"]["temp"] +
                " β | ζι«ζ°ζΈ© " +
                data["main"]["temp_max"] +
                "β | ζδ½ζ°ζΈ© " +
                data["main"]["temp_min"] +
                "β | ι’¨ι " +
                data["wind"]["speed"] +
                " γ | ι²ι " +
                data["clouds"]["all"] +
                " % </span>"
            );
            //HTMLγ«εεΎγγγγΌγΏγθΏ½ε γγ
            messageList.append(messageElement);
        };

        request.send();
    </script>
</body>

</html>
