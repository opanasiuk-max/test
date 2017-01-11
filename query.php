<?php
/**
 * Created by PhpStorm.
 * User: Максим
 * Date: 22.11.2016
 * Time: 12:49
 */

$channels_xml1="rss_channels.xml";
$mainSelect=simplexml_load_file($channels_xml1);
$query=$_POST['query'];
foreach ( $mainSelect->channel->item as $item1 ){
 if ()
    $title1=$item1->title;
    $link1=$item1->link

}