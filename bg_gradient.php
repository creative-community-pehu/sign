<?php

function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

$symbol = (string)filter_input(INPUT_POST, 'symbol'); // $_POST['symbol']
$color = (string)filter_input(INPUT_POST, 'color'); // $_POST['color']
$timestamp = time() ;

$forwardedFor = $_SERVER["HTTP_X_FORWARDED_FOR"];
$ips = explode(",", $forwardedFor);
$ip = $ips[0];

$fp = fopen('symbol_color.csv', 'a+b');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    flock($fp, LOCK_EX);
    fputcsv($fp, [$symbol, $color, $timestamp, $ip]);
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
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="/sign/stylesheet.css" />
<title>自分の気持ちを知る・表す</title>
<style type="text/css">
.bg_gradient {
  position:relative;
  top:0; left:0;
  display:block;
  padding:0;
  margin:0;
  width:100%;
  height:100vh;
  background-size: 500% 500%;
  animation: gradient 50s ease infinite;
}
@keyframes gradient {
  0% {
    background-position: 100% 0%;
  }
  50% {
    background-position: 100% 100%;
  }
  100% {
    background-position: 100% 0%;
  }
}

@media print {
.bg_gradient {
  background-size: 100% 100%;
  animation: gradient none;
}
}
</style>
</head>
<body>


<ul id="symbol_color">
<li class="bg_gradient" style="background-image: linear-gradient(0deg,
<?php if (!empty($rows)): ?>
<?php foreach ($rows as $row): ?>
#<?=h($row[1])?>,
<?php endforeach; ?>
<?php else: ?>
<?php endif; ?>
#fff);">
</li>
</ul>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>
    $(function(){
    $("#open").load("/sign/log.php");
    })

    let btn = document.querySelector('#bg_link');
    let log = document.querySelector('#open');
     
    let btnToggleclass = function(el) {
      el.classList.toggle('open');
    }
     
    btn.addEventListener('click', function() {
      btnToggleclass(log);
    }, false);
</script>
</body>
</html>