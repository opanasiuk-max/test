<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе
 * установки. Необязательно использовать веб-интерфейс, можно
 * скопировать файл в "wp-config.php" и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define('DB_NAME', 'wordpress');

/** Имя пользователя MySQL */
define('DB_USER', 'root');

/** Пароль к базе данных MySQL */
define('DB_PASSWORD', '');

/** Имя сервера MySQL */
define('DB_HOST', 'localhost');

/** Кодировка базы данных для создания таблиц. */
define('DB_CHARSET', 'utf8mb4');

/** Схема сопоставления. Не меняйте, если не уверены. */
define('DB_COLLATE', '');

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'GWz{D2>Y4;/zrX~(41y)qs7_`TKY_)FywtGszwqu3x|7%3JL#a{Yx/5~l3q=|&&m');
define('SECURE_AUTH_KEY',  ']0vUl!?OHW)i~M:t3qmsN(E>%qrM&ohM~M&C,i@~kRgq)wyYm8G 6on,RR+Oto$t');
define('LOGGED_IN_KEY',    '~X3|~vG*hjQe)~urLVB7Nze!K~M6s{nOtU*j|I3W*%jl:q0dB`c*-$=x+,1(w^1#');
define('NONCE_KEY',        '!UutMl#ag4T,+OS-GZg1V}dxml! k5l9^NIOUq0{4&&<`UT[;&ciWN`:$vo?tU,C');
define('AUTH_SALT',        'r OiZ]R8{kAa3zN;o<[SbZY-I0gTym7wi/HQZ<Opf3-f6,_c5T=4(]GCpS(FN+.y');
define('SECURE_AUTH_SALT', 'P0V/~Q/|P+:GS@1h`nES0KQ8>i~e*`mKXlP54<AAeH!b~9g|0a|OJRXF_*lR]9<6');
define('LOGGED_IN_SALT',   'mha<!rA;k/|EoaxZ%)3DXHLcsygC<$.)et4=$M[%@`5Y?58z,Z]aX>OEK;.{P!{8');
define('NONCE_SALT',       'K2vXks@Lbh7Ylo]oyHJf)reyb!Pz1o~#c:D8iC:&Yw&DgOPkI;&72jy lsA8p2Oo');

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix  = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 * 
 * Информацию о других отладочных константах можно найти в Кодексе.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Инициализирует переменные WordPress и подключает файлы. */
require_once(ABSPATH . 'wp-settings.php');
