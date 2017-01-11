<?php
/**
 * Created by PhpStorm.
 * User: Максим
 * Date: 21.11.2016
 * Time: 19:19
 */


			$items_to_display = 15;
//$xml_channel=$channel_item->link;


           $xml_channel=$xml->channelList->channel->link;
            $rss = simplexml_load_file($xml_channel);
            if ($rss)
            {
                echo '<h3 id="channel">' . $rss->channel->title . '</h3>';

                $items = $rss->channel->item;
                $item_count = 0;
 echo <<<html4

<form name="test">
<select size="20" id="selArticl">
html4;

                foreach($items as $item)
                {
                    $title = $item -> title;
                    $link = $item -> link;
                    $ts = strtotime($item -> pubDate);
                    $published_on = date('Y-m-d', $ts);
                    $description = $item -> description;
                    echo '<option value="'.$link.'"><h3>'.$title.'</h3></option>';
                };
                if(++$item_count >= $items_to_display)
                    break;
            }
    echo '</select></form>';