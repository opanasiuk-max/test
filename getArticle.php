<?php
/**
 * Created by PhpStorm.
 * User: Максим
 * Date: 28.11.2016
 * Time: 16:03
 */
session_start();



//$url_sel_chann=include_once 'getURLchann.js';
$docum=simplexml_load_file($erl_sel_chann);
$text="<ol>";
foreach ($docum->item as $items) {

    $title = $items->title;
    $link = $items->link;
    $des = $items->description;
    $text=+ "<li><p>title: </p>" . $title . "<p>link: </p>" . $link."</li><br>";
}
$text=+ "</ol>";