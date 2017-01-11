<?php
$items_to_display = 5;
$url = "rss_feed_sources News.xml";
$rss = simplexml_load_file($url);
if ($rss)
{
 echo '<h1>' . $rss->channel->title . '</h1>';
 echo '<br/>' . $rss->channel->pubDate . '<br/>';
  $items = $rss->channel->item;
  $item_count = 0;
  foreach($items as $item)
  {
    $title = $item->title;
    $link = $item->link;
    $ts = strtotime($item->pubDate);
    $published_on = date("Y-m-d", $ts);
    $description = $item->description;
    echo '<h3><a href="' . $link . '">' . $title . '</a></h3>';
    echo '<span>' . $published_on . '</span>';
    echo '<p>' . $description . '</p>';
    if(++$item_count >= $items_to_display)
      break;
  }
}
?>