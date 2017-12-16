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
define('DB_NAME', 'builteasy_wp');

/** MySQL database username */
define('DB_USER', 'builteasy_wp');

/** MySQL database password */
define('DB_PASSWORD', 'lk4lcAkGsPlX');

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
define('AUTH_KEY',         '}gALcDMM{PbDS2`VNNc){[P7%OEoUNgGyr2-}+Ri.k60eF5%i+~uMUJenz(A_(pf');
define('SECURE_AUTH_KEY',  'RY2(-3rs4f1#Pd2+3|OGdig2y[SrzkQS/{gv>J=Bx)FE3UYyb6PJq2UC(%cO%=u@');
define('LOGGED_IN_KEY',    '5CcobLHyD~$I=TV^Uh=,(9P1KyKy.Z4:8.%ke$=R# l9L0Eu@L$QPnImJvCgji>2');
define('NONCE_KEY',        'pQb7hGyBX1YtZU& c.,F@O0t T{9+6l,s5o[0,E^~y_G$q`}fl $FjPJ1Y4E+5@0');
define('AUTH_SALT',        'C<5e|!0hsEpxJ:rDz&<GS]YkID**6 O[.$l=8F22_mpy2D)S)t^4pz@nLFz`4n?0');
define('SECURE_AUTH_SALT', '_cA+cEm*[<Sl^BS3NY>[~klBm{l$-*M!_4suRAX 5Qh%5oYN5iZyKh2W y%wRxJR');
define('LOGGED_IN_SALT',   'sRj.)Aas94rx-%/6XI[JW3w3LpB<PMuRT?)Uhp%*,5(VhmD(w4E0DnRm$fk3Sa5e');
define('NONCE_SALT',       'lA)EyR#n?f^G[_@j#!+Ty8O7%{-n@),Ow^^$@&by PEIzTu|l3px#I,Q?pU#:aGt');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_root_';

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
