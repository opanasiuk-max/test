 <?php
// Задаем формат даты
define('DATE_FORMAT_RFC822','r');
// Сообщяем браузеру что передаем XML
header("Content-type: text/xml; charset=windows-1251");

// Дата последней сборки фида
$lastBuildDate=date(DATE_FORMAT_RFC822);

echo <<<END
<?xml version="1.0" encoding="windows-1251"?>
<rss version="2.0">
<channel>
    <title>mysite.com RSSFeed</title>
    <link>http://mysite.com</link>
    <description>Мой супер блог</description>
    <pubDate>$lastBuildDate</pubDate>
    <lastBuildDate>$lastBuildDate</lastBuildDate>
    <docs>http://blogs.law.harvard.edu/tech/rss</docs>
    <generator>Weblog Editor 2.0</generator>
    <copyright>Copyright 2006 mysite.com</copyright>
    <managingEditor>editor@mysite.com</managingEditor>
    <webMaster>webmaster@mysite.com</webMaster>
    <language>ru</language>
END;

// В этом файле надо разместить подключение к базе данных
include_once("db.php");

// Модифицируйте запрос под вашу таблицу
$query = "SELECT name, anon, url, UNIX_TIMESTAMP(date) as pubdate 
          FROM news
          WHERE visible=1
          ORDER by date desc
          LIMIT 0,10";

$res   = mysql_query($query);
while ($row=mysql_fetch_array($res)) {
// Убираем из тайтла html теги и лишние пробелы
$title   = strip_tags(trim($row['name']));
// С аноносом можно не проводить такие 
// манипуляции, т.к. мы вставим его в блок CDATA
$anon    = $row['annonce'];
$url     = $row['uri'];
$pubDate = date(DATE_FORMAT_RFC822, $row['pubdate']);

echo <<<END
    <item>
        <title>$title</title>
        <description><![CDATA[$anon]]></description>
        <link>http://mysite.com/news/$url</link>
        <guid isPermaLink="true">http://mysite.com/news/$url</guid>
        <pubDate>$pubDate</pubDate>
    </item>
END;
}

echo <<<END
</channel>
</rss>
END;
?>