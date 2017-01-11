<?php
echo <<<HTML0
<!doctype html>
<html lang="en">
    <head>
    <meta charset="utf-8">
    <title>RSS reader</title>
    <script type="text/javascript" src="jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="getXmlHttpRequest.js"></script>
    <script type="text/javascript" src="showList.js"></script>
    <link href="rss.css" rel="stylesheet" type="text/css">
    </head>
<body>
<div id="wrap">     <!-- начало обертки -->
    <div id="header">Шапка страницы</div>
    <div id="rssContent"></div>
    <div id="side">
HTML0;




// подключение скрипта заполнения списка каналов
    include_once 'rssChannel.php';

echo <<<HTML1
    </div>
    <div id="footer">Нижний колонтитул</div>
    
</div>              <!-- конец обертки -->
</body>
</html>
HTML1;

?>