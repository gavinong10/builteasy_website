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
define('DB_NAME', 'builteas_wp1');

/** MySQL database username */
define('DB_USER', 'builteas_wp1');

/** MySQL database password */
define('DB_PASSWORD', 'W&CrWOqipem#PL2k3i~06..1');

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
define('AUTH_KEY',         'H690Iu2p53a0AAskM3Vt1bUwGTxjuNEjyKbuxtHGbMwm2jU5o8uFuxnZp8xy31my');
define('SECURE_AUTH_KEY',  'jW8Xy3BPDj5s1YjRFfyr0gIpJrVJhJJzc6pnipzJCRIRZQB7Sa5Rjq37zaviorpZ');
define('LOGGED_IN_KEY',    'YwhTtRexLJ03EsjOxWfQzyrj5kAhLXYVocUvdP6XAZhemWFwQLv5qSGfvXgjVLzZ');
define('NONCE_KEY',        'p35dAsh45wvnCUPe5Fy1Sz3e4HBd4Ys8JfSExSCZvIgYRpbf4aZfG0b09uyqxbUo');
define('AUTH_SALT',        'xGEZBcPHe4yX1sc5eR9X2R7ODOfWu52LQ5AuN2YFAJhHOd2qA9posytGG8BndyZ1');
define('SECURE_AUTH_SALT', '2LCMZy44ePcYvutjwseVrsTt2uokwFddTWIKecYhy21rSgGcyL2VIbEUmLQRsBLu');
define('LOGGED_IN_SALT',   'M8lOezo9qT6pnFnGOzzS2LN5uWNuFWbDbZ17TO0brTxvbLplCArpF3KoBieE6TNm');
define('NONCE_SALT',       'aOGz2RchIbNk7H2nwTl9XvQTvgGcc0B285Wp3aaKCI2GfS53nHsvFAN9KXYziSRA');

/**
 * Other customizations.
 */
define('FS_METHOD','direct');define('FS_CHMOD_DIR',0755);define('FS_CHMOD_FILE',0644);
define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');

/**
 * Turn off automatic updates since these are managed upstream.
 */
define('AUTOMATIC_UPDATER_DISABLED', true);


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
