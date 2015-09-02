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
define('DB_NAME', 'phonicss_wp320');

/** MySQL database username */
define('DB_USER', 'phonicss_wp320');

/** MySQL database password */
define('DB_PASSWORD', 'wnFs50bRxrjJk2p');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         '@g,[}$P#}JODPn+eM/f;a)=fU0o.xu[*tNIq;,U57+(90T*+#Okfx8?.Sc%iM9N&');
define('SECURE_AUTH_KEY',  '!^>Q0yaQU]f6;k}L=WXT$!)3`I|x@WJ*,HiJq6*tVHXxW=kg9l:ENm0l72i<.]0C');
define('LOGGED_IN_KEY',    '~8lUI_wv3f!}^bMt5 1w~+UM$2,xgl}?:}1bhLf%TfOC}OIsNgt$=~i|Sc=!Qc @');
define('NONCE_KEY',        '39hzm].0:|fhv0r({i@/M5jo,-&OSv0<R<]5?,W$0^?Gp|E@T8sysD 75n,(yI}k');
define('AUTH_SALT',        '6e-)|6X_pllvu .&s?HX[5|Xv>,Scxgq-(o-$_o E[Uzadk*Or)FR7l<(&f!nC>/');
define('SECURE_AUTH_SALT', '&!xf`K{#kjYP{$9>Qrp -a+Sv`6Wr?iF4%jfQ}>2dGtkXVf--$XcwT|}EM;O56*:');
define('LOGGED_IN_SALT',   'UZ_d}Nd?%DaI!BvxO@M//}s]+ 57)I%h`Ga4C9*-y6)G%N>!Ko$LE<?Ce{k_a/=X');
define('NONCE_SALT',       'e){52lKf`3muKfm7%v?KFR`6+VQx>,.fjZ%h Zt>l&;K{(f^~0r<}+Epos)%xMLZ');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
