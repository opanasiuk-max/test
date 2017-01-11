<?php
function store_rss_feed_results($file_sequence_number, $result_array) {
   // Сначала проверяем, существует ли подкаталог с именем "feed_results".
   if (file_exists(RSS_FEED_RESULTS_DIRECTORY) == false) {
      // Каталог не существует. Создадим его сейчас.
      mkdir(RSS_FEED_RESULTS_DIRECTORY);		
   } // Конец условия (file_exists(RSS_FEED_RESULTS_DIRECTORY) == false)
	
   // Формируем имя файла.
   $result_file_name = sprintf("%s/%03d%s", RSS_FEED_RESULTS_DIRECTORY,
      $file_sequence_number, RSS_FEED_RESULTS_FILE_NAME_SUFFIX);
	
   // Если файл уже был создан в ходе предыдущих запусков, просто удаляем его.
   // Мы перезапишем в него последнюю информацию о канале.
   if (file_exists($result_file_name) == true) {
      unlink($result_file_name);
   }

   // Создаём и открываем файл.
   $handle = fopen($result_file_name, FILE_CREATE_WRITE_FLAG);
	
   if ($handle == false) {
      // Создать файл не удалось. Возврат.
      return;
   } 
	
   // Можно начинать запись полученных RSS-каналов в этот файл.
   // Записываем порядковый номер провайдера канала.
   $feed_provider_number = FEED_PROVIDER_SEQUENCE_NUMBER . 
      $file_sequence_number . NEW_LINE;
   fwrite($handle, $feed_provider_number);
   // Записываем имя провайдера канала.
   $feed_provider_name = RSS_FEED_PROVIDER_NAME . $result_array[0] . NEW_LINE;
   fwrite($handle, $feed_provider_name);
   // Записываем количество полученных элементов канала.
   $number_of_received_rss_feeds = RECEIVED_RSS_FEEDS_CNT . 
      $result_array[1] . NEW_LINE;
   fwrite($handle, $number_of_received_rss_feeds);

   $rss_feed_title_array = $result_array[2];
   $rss_feed_url_array = $result_array[3];
   $rss_feed_description_array = $result_array[4];

   // Остаёмся в цикле и записываем заголовок, URL и описание.
   for($cnt=0; $cnt < sizeof($rss_feed_title_array); $cnt++) {
      $feed_item_separator = FEED_ITEM_SEPARATOR_LINE . NEW_LINE;
      fwrite($handle, $feed_item_separator);
      $feed_item_sequence_number = FEED_ITEM_SEQUENCE_NUMBER . 
         ($cnt+1) . NEW_LINE;
      fwrite($handle, $feed_item_sequence_number);
      $feed_item_title = FEED_ITEM_TITLE . 
         $rss_feed_title_array[$cnt] . NEW_LINE;
      fwrite($handle, $feed_item_title);
      $feed_item_url = FEED_ITEM_URL .
         $rss_feed_url_array[$cnt] . NEW_LINE;
      fwrite($handle, $feed_item_url);
      $feed_item_description = FEED_ITEM_DESCRIPTION . NEW_LINE .
         $rss_feed_description_array[$cnt] . NEW_LINE;
      fwrite($handle, $feed_item_description);		
   } // Конец for($cnt=0; $cnt < sizeof($rss_feed_title_array), $cnt++)

   $feed_item_separator = FEED_ITEM_SEPARATOR_LINE . NEW_LINE;
   fwrite($handle, $feed_item_separator);	
   fclose($handle);
} // Конец функции store_rss_feed_results
?>