<?php

//селекция каналов в файле .xml
$channels_xml="rss_channels.xml";


$xml=simplexml_load_file($channels_xml);
//$xml=new DOMDocument();
//$xml->load($_SESSION['channels_xml']);
//echo $doc->saveXML();
echo <<<Channels
<!--<form name="channelsList" action="" method="post" id="selectChannel">-->
<form>
<label><h2>Каналы</h2></label>
<select  name="selChan" id="selChan" size="10" >

Channels;

// выборка заголовков каналов и ссылок на каналы
foreach ($xml->channelList->channel as $channel_item) {
    echo '<option value="'.$channel_item->link.'"><h3>'.$channel_item->title.'</h3></option>';

    //запись содержимого каждого канала в файл

    $qwerty=simplexml_load_file($channel_item->link);

    $xml_var=$qwerty->asXML();
    $fp=fopen("xml/xml_".$channel_item->title.".xml",'w');
    fwrite($fp,$xml_var);
    fclose($fp);

}

echo <<<kidSelect
</select>
<label id="channel_url"></label>
</form>
kidSelect;

/*echo <<<mko
<p>Channel: <span onclick="getXMLHttpRequest()" id="channels"></span></p>
<form name="selectArticle" action ="" method="" id="selectArticle">
<select id="selArticle" onchange=>
    <option>select article</option>
</select>
</form>

mko;*/

//URL адрес выбранного RSS канала доступен для считывания в скрытом поле Label


//подключение кода вывода содержимого канала
//include_once 'getRSSarticle.php';
include_once "RSS.js";
