<?php
# Database Configuration
define( 'DB_NAME', 'wp_cnmi' );
define( 'DB_USER', 'cnmi' );
define( 'DB_PASSWORD', 'QC0oAeZAnVusXvDMpbTY' );
define( 'DB_HOST', '127.0.0.1' );
define( 'DB_HOST_SLAVE', '127.0.0.1' );
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', 'utf8_unicode_ci');
$table_prefix = 'wp_';

# Security Salts, Keys, Etc
define('AUTH_KEY',         'b+d|ekh|gv(d^K[ .%/wc6 h=IboY94|*!H{oFhKnS{IEw.|H.9I&w!yep|;%eH1');
define('SECURE_AUTH_KEY',  'E#z04;aa?cei>czpKTmptQ[HQxtf2E3|by^;EC+eGR6GV;^-wt7<BmB>a|JOb<cl');
define('LOGGED_IN_KEY',    '7C9EmVRm=tqQ}pXO#aCgi-d[p6vHqJ!STPvpL-uDs$c2_0k1WW+s%q_ .F:+MHH-');
define('NONCE_KEY',        'V!Inp+}x)5o4b1tlvx^@;jv_E>5q_OBfkqk(A.VI/2HVTU?CTxfY~E.b+mZFH82V');
define('AUTH_SALT',        'e29w=G^0^gz/o=IlF;%1.zRD-}x}lhkC`ayfl?0.X|2B+Y*<LCruDZ?EenQ~^rS8');
define('SECURE_AUTH_SALT', ' 9e+3R_xROtm/&(mK?rp-<Ps!c/Pl@k||l`J|T`)FzTUZ?)+gK.&~SfAnS;Ur-]2');
define('LOGGED_IN_SALT',   '|fn|Jc(di8rg{u-^l  qF`Za%-6/?;j)4]2W`]t-vJt@F6#BH[`4As-]IKr(,S*g');
define('NONCE_SALT',       '$h=>x}2HIJ9#%bTa49D|RR44VC#T9nSg)@8rX^9|#D0YHECj)S#@P8|!). (%dY@');


#Turn on debug

define("WP_DEBUG", true);

define("WP_DEBUG_DISPLAY", true);

define("WP_DEBUG_LOG", true);

# Localized Language Stuff

define( 'WP_CACHE', FALSE );

define( 'WP_AUTO_UPDATE_CORE', false );

define( 'PWP_NAME', 'cnmi' );

define( 'FS_METHOD', 'direct' );

define( 'FS_CHMOD_DIR', 0775 );

define( 'FS_CHMOD_FILE', 0664 );

define( 'PWP_ROOT_DIR', '/nas/wp' );

define( 'WPE_APIKEY', 'a1508cbead94393eb153c3bb4c426277085d504d' );

define( 'WPE_CLUSTER_ID', '114020' );

define( 'WPE_CLUSTER_TYPE', 'pod' );

define( 'WPE_ISP', true );

define( 'WPE_BPOD', false );

define( 'WPE_RO_FILESYSTEM', false );

define( 'WPE_LARGEFS_BUCKET', 'largefs.wpengine' );

define( 'WPE_SFTP_PORT', 2222 );

define( 'WPE_LBMASTER_IP', '' );

define( 'WPE_CDN_DISABLE_ALLOWED', false );

define( 'DISALLOW_FILE_MODS', FALSE );

define( 'DISALLOW_FILE_EDIT', FALSE );

define( 'DISABLE_WP_CRON', false );

define( 'WPE_FORCE_SSL_LOGIN', false );

define( 'FORCE_SSL_LOGIN', false );

/*SSLSTART*/ if ( isset($_SERVER['HTTP_X_WPE_SSL']) && $_SERVER['HTTP_X_WPE_SSL'] ) $_SERVER['HTTPS'] = 'on'; /*SSLEND*/

define( 'WPE_EXTERNAL_URL', false );

define( 'WP_POST_REVISIONS', FALSE );

define( 'WPE_WHITELABEL', 'wpengine' );

define( 'WP_TURN_OFF_ADMIN_BAR', false );

define( 'WPE_BETA_TESTER', false );

umask(0002);

$wpe_cdn_uris=array ( );

$wpe_no_cdn_uris=array ( );

$wpe_content_regexs=array ( );

$wpe_all_domains=array ( 0 => 'cnmi.wpengine.com', );

$wpe_varnish_servers=array ( 0 => 'pod-114020', );

$wpe_special_ips=array ( 0 => '35.197.85.52', );

$wpe_ec_servers=array ( );

$wpe_netdna_domains=array ( );

$wpe_netdna_domains_secure=array ( );

$wpe_netdna_push_domains=array ( );

$wpe_domain_mappings=array ( );

$memcached_servers=array ( );
define('WPLANG','');

# WP Engine ID


# WP Engine Settings






# That's It. Pencils down
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
require_once(ABSPATH . 'wp-settings.php');

$_wpe_preamble_path = null; if(false){}
