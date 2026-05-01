<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'hongdu' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'jR}!_*}|BnKPW[/rw2?SaIW@H.=[l^ -9Q..SPw?h/vNPp~WUd[|ZimAt=V,M)Yu' );
define( 'SECURE_AUTH_KEY',  '-0#u[k{k@%Z:db;,e}]*8rm8pECLpD]<10*V8uPz{Ma=v;< jk35SNOGp]~F2q>.' );
define( 'LOGGED_IN_KEY',    'J*cYbIHp49Tn@Y{+)p]^k}tIPVkRZvaX26(-t77oPV%@k:#pd1d^|i{v,_/foH~Y' );
define( 'NONCE_KEY',        '}H|FoPpqDl##?@jkvA_W QdaSV4`lvJW@+@2,6%+5f!RP}3FeN%J@;BTQ-q_Pf!2' );
define( 'AUTH_SALT',        '.MtSWP]q62%iZgX&#f,o+_|Ez%`Ou<D-!sn3ua t`bmdT/]?]I`E]Mch.6%&Q7iR' );
define( 'SECURE_AUTH_SALT', 'Bt&R]b=;p:;.Ww9{i:1?+hqf.C}jG^*D$qceDR+XpoK>tg*l[-p3s=B>GPyd9u?(' );
define( 'LOGGED_IN_SALT',   'IpZ;C1no8wq>=#viEg7pX,:O<[Rw|wTNM~Dn`>Neg,S<UsiB0y8`HA_]TO7Maj_J' );
define( 'NONCE_SALT',       '4P,}@4De J-CcNMMBS~]RP9IWs;poP1Tx927|!ST)Xi[u2~UeU4B.5gy8c=l-Nl`' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
