<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
switch ($_SERVER['SERVER_NAME']) {

  case "local.egg.com":
    define( 'DB_NAME',     'getitwrite' );
    define( 'WP_SITEURL',  'http://local.get-it-write.com' );
    define( 'WP_HOME', 'http://local.get-it-write.com' );
    define( 'DB_USER',     'root' );
    define( 'DB_PASSWORD', 'root' );
    define( 'DB_HOST',     'localhost' );

  case "get-it-write.nowwhat.hk":
    define( 'DB_NAME',     'nowwhat_getitwrite' );
    define( 'WP_SITEURL',  'http://get-it-write.nowwhat.hk' );
    define( 'WP_HOME', 'http://get-it-write.nowwhat.hk' );
    define( 'DB_USER',     'nowwhat' );
    define( 'DB_PASSWORD', '20273214' );
    define( 'DB_HOST',     'localhost' );
}

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '[0|X5Q`j6?iWii/-0QT]Fbb`V7P$|opdNLvBi#/bZ^Yw*n<d@JhKFTnP2aw<4m<y');
define('SECURE_AUTH_KEY',  '/NSs@+-!<{lzX)=)Au=PG)tsudc` m!}#Di~D~>b8&iH(A_/zIT{s~q!m-+a8]m`');
define('LOGGED_IN_KEY',    'r]Vd8Hd-]RRg0kFxA>tOZrG3?qEEHu^*9Itvt|K-^s3 ]+GI!o&ad8Jm=?D=.HU>');
define('NONCE_KEY',        '7DZQhG-fqi,+Z|C[M$t7X.`WD/RpXwuP;u6`iVj75Q|$5NAOgI QH@sWCNev.uT%');
define('AUTH_SALT',        'k>z(fV9LdwIFU[|MUM8ycb.g8&J@HS9m@q)<aT<GdlVQ)_Tr+]4`M~fo}y+33_9`');
define('SECURE_AUTH_SALT', ']h,e7P/:=&T.Gv!BG+|?BRX |d`7{d9wcVO&)C:dTVThvDh xw||fBuo,25~Ce/V');
define('LOGGED_IN_SALT',   '4?+2)qGbyF{.VtC;#u yOBDvA+NDb<.XQ85)*ho2L]T`oV|EI@+7:&4cT<PUMjkv');
define('NONCE_SALT',       'b7;LzLz4=/jUO3$j4l(i?vPEG8ggP&MTp&RO]29qi:EES,B]$ccH:4u78+$++N+L');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'getitwrite_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

//define ('WPLANG', 'zh_TW');

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
