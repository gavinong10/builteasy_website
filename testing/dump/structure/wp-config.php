<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'builteas_wp415');

/** MySQL database username */
define('DB_USER', 'builteas_wp415');

/** MySQL database password */
define('DB_PASSWORD', 'tSS!48E5P-');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'jarcr4yaanorv8rrv8sxptj2kohovcuuuimtwvex8nr093mulr0j07stgo6y2c7o');
define('SECURE_AUTH_KEY',  'uhxg8hoztlwgdhbzizdzr3z3qfpmbnxgzt0bccpehrgmpcgfjn8wtff3gtonmsit');
define('LOGGED_IN_KEY',    'yegejvgpaatjbkvrwzhuuplyl75gjq0glltssucdrs69d5v8ehj6bokjnfb47isg');
define('NONCE_KEY',        'uiyxfofuls0u36qqu5tdls1tc9c692fth637gkmx61jjkntvl2lunnyahu0lbvl8');
define('AUTH_SALT',        'cf25xwjq5ebfn9jlqkyhon5auzyya7pxr2wibm5wej1qab5dypsucbllernphoup');
define('SECURE_AUTH_SALT', '0roljgeiw8whwsndotmryurmpoddengzbbs9fltcqf6klr1yaqmypf3vnpl2kbqb');
define('LOGGED_IN_SALT',   'to6kkcjkctr2d9yzbsmcoldlbpsq6ricxbxjiohvj6zkc53hm8jnqna2kfxkuu4z');
define('NONCE_SALT',       'chvkb4qzzzxkx0h6la938wjwpqtc8rrqqs3rcluizynrglmyyyeglgwhn8em2fe0');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = '4c8_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
