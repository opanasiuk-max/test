<?php
include_once("write_rss.php");//подключаем файл с функцией вывода RSS-новостей
//адресс новостей (URL-адрес RSS потока)
$url_rss="http://sitear.ru/rss.php";
 //количество выводимых новостей
$kol_print_news=10;
 //имя файла для хранения RSS-новостей на локальном сервере (то есть кэш-файл)
$file_rss="cache_rss.xml"; 
//время обновления, в часах
$hclock=1;
print_rss($url_rss,$file_rss,$hclock,$kol_print_news); //вызываем функцию вывода RSS-новостей
?>