<?php
define('WP_AUTO_UPDATE_CORE', 'minor');// This setting is required to make sure that WordPress updates can be properly managed in WordPress Toolkit. Remove this line if this WordPress website is not managed by WordPress Toolkit anymore.
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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'fantwist-new' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'eppxx0sscxoklhzllf7an91ykf5q9y505qbgodixfhzzeseu56sxhfg5ajpdo9ye' );
define( 'SECURE_AUTH_KEY',  '0kzbrpfsck79lk60duez0qdwwifnvcqlmthrihyqpw4xtyujzjc5czxvurvopriw' );
define( 'LOGGED_IN_KEY',    'njrqdfzbagnmfiz792m07pyghvkk3p9a6xwprrzztp7zsvluebpeovzemccvyts3' );
define( 'NONCE_KEY',        'tkmnn5bhtcra2oofwbm7ugnofmbmbwuwwiybtbi7u4ldfeoyrou6ctdpaairnts8' );
define( 'AUTH_SALT',        'uzksgghjwl0i1gkkjkmdtmpwa2gqeml1kmialoqcuxjshotkx3zqm6yc9mb3zldv' );
define( 'SECURE_AUTH_SALT', 'bfjftvfyyas1yal7vxk8lnbwutxjglliuadxijugxbzlviosuhocqh4angqxwz9y' );
define( 'LOGGED_IN_SALT',   'azultdhz3ebbwihzfiwqqnmylva3ravdmk75ba4npgp9vknbnwg0pt3afkkhtscp' );
define( 'NONCE_SALT',       'wx4ivnnaexoh9ytx6bs0mowznwztl0yzclyf2lyid1tyuqibkpre668y0ldq0zkr' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpqf_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );


/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
