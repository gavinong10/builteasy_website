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
define('DB_NAME', 'builteasy_wp_live');

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
define('AUTH_KEY',         ' I^/++zN,!//+s&m3@@O$NuK+@ip#mv*c]rzD:h,Zg&[s~:v@S!3lDOT|NFGS8I/');
define('SECURE_AUTH_KEY',  '0A& io4*@h[Lj% ]mjy-Q+.vI{P<O@^3ZNz>M7QRKx{tmV.+mJQU;$<$f&uY4Exa');
define('LOGGED_IN_KEY',    'uge={b+d$[^vU>.50Ji:LL:/,:X6kNE{#r7=L2;%aMuqQ^EXiFK|9.9W0]^{e(6W');
define('NONCE_KEY',        'l,BcV2.R9b!3&{/=uC<()6VoTX3kec,voe=dzr/.<v/C;_@)[h]I8TikB6A6JIM;');
define('AUTH_SALT',        'I %.)x.F`/*+8CP=qRXBX1;*so$8uy8+|DE](Mt.JHVh5A*K{)H=5~b)KlVP:Ldt');
define('SECURE_AUTH_SALT', 'vDnxi:Iui$<lLc/Cm3En}iSht(IlNg_&f$lN[szUb+ZbjQuZj!26P`V6x[`@30z_');
define('LOGGED_IN_SALT',   'G7X5F)o^UJd(+ZjayFX,O~MFp<*ubW+!]Lnx}lnt|V38Pyj0h--ls|.PV1RFhB81');
define('NONCE_SALT',       'I<|_5:r44)aJ$jAv3]^ej|>0o;v~!`cREXt|3Lypd?]$>lZm;No=Q`=(%Z zSlGi');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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

//define('WP_HOME', 'http://ec2-34-216-116-4.us-west-2.compute.amazonaws.com');
//define('WP_SITEURL', 'http://ec2-34-216-116-4.us-west-2.compute.amazonaws.com');
#define('FS_METHOD', 'ftpext');
define('FTP_BASE', '/home/ec2-user/');
define('FTP_CONTENT_DIR', '/home/ec2-user/www/vhosts/builteasy.com.au/wp-content/');
define('FTP_PLUGIN_DIR ', '/home/ec2-user/www/vhosts/builteasy.com.au/wp-content/plugins/');
#define('FTP_PUBKEY', '/home/username/.ssh/id_rsa.pub');
#define('FTP_PRIKEY', '/home/username/.ssh/id_rsa');
define('FTP_USER', 'ec2-user');
define('FTP_PASS', 'BuiltEasy123!');
define('FTP_HOST', 'ec2-34-216-116-4.us-west-2.compute.amazonaws.com:21');
define('FTP_SSL', false);
define('FS_METHOD', 'direct');
