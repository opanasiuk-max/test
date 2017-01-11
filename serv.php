<?php
/**
 * Created by PhpStorm.
 * User: Максим
 * Date: 23.11.2016
 * Time: 13:56
 */
<?php

  if ($_POST["changeChannel"] == "http://news.liga.net/all/rss.xml") echo json_encode(array("0" => "Москва", "1" => "Санкт-Петербург"));
  elseif ($_POST["changeChannel"] == "http://news.liga.net/top/rss.xml") echo json_encode(array("2" => "Киев", "3" => "Одесса"));
?>