<?php
/**
 * Plugin Name: Behrmedia Performance & Security Base
 * Description: Setzt Best-Practice Browser-Caching-Regeln, schützt die wp-login.php und integriert robuste GitHub-Updates.
 * Version: 1.0.0
 * Author: behrmedia
 * Author URI: https://behrmedia.de
 */

// Direkten Zugriff verhindern
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ==============================================================================
// WICHTIG: 'use' Anweisungen im globalen Bereich
// ==============================================================================
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

// ==============================================================================
// 1. GitHub Plugin Update Checker (PUC) - ROBUSTE VERSION
// ==============================================================================
$puc_file = __DIR__ . '/plugin-update-checker/plugin-update-checker.php';

if (file_exists($puc_file)) {
    require_once $puc_file;
    
    // Dynamischer Slug für GitHub-ZIPs
    $plugin_slug = basename(__DIR__); 
    
    $myUpdateChecker = PucFactory::buildUpdateChecker(
        'https://github.com/barti-mcfly/behrmedia-performance/',
        __FILE__,
        $plugin_slug
    );

    $myUpdateChecker->setBranch('main');
} else {
    // Warnung, falls die Bibliothek fehlt, ohne die Seite abzustürzen
    add_action('admin_notices', function() {
        echo '<div class="error"><p>Behrmedia Performance: Der Ordner <b>plugin-update-checker</b> wurde nicht gefunden. Updates via GitHub sind deaktiviert.</p></div>';
    });
}

// ==============================================================================
// 2. .htaccess Regeln für Browser-Caching & Login-Schutz
// ==============================================================================
register_activation_hook( __FILE__, 'behrmedia_performance_add_htaccess_rules' );
function behrmedia_performance_add_htaccess_rules() {
    $htaccess_file = get_home_path() . '.htaccess';
    
    $rules = array(
        '<IfModule mod_expires.c>',
        '    ExpiresActive On',
        '',
        '    # Bilder & Grafiken',
        '    ExpiresByType image/jpeg "access plus 1 year"',
        '    ExpiresByType image/png "access plus 1 year"',
        '    ExpiresByType image/gif "access plus 1 year"',
        '    ExpiresByType image/webp "access plus 1 year"',
        '    ExpiresByType image/avif "access plus 1 year"',
        '    ExpiresByType image/svg+xml "access plus 1 year"',
        '    ExpiresByType image/x-icon "access plus 1 year"',
        '',
        '    # Schriften',
        '    ExpiresByType font/woff2 "access plus 1 year"',
        '    ExpiresByType font/woff "access plus 1 year"',
        '    ExpiresByType font/ttf "access plus 1 year"',
        '    ExpiresByType font/otf "access plus 1 year"',
        '    ExpiresByType application/font-woff "access plus 1 year"',
        '',
        '    # Videos',
        '    ExpiresByType video/mp4 "access plus 1 year"',
        '    ExpiresByType video/webm "access plus 1 year"',
        '',
        '    # CSS & JavaScript',
        '    ExpiresByType text/css "access plus 1 year"',
        '    ExpiresByType application/javascript "access plus 1 year"',
        '    ExpiresByType text/javascript "access plus 1 year"',
        '</IfModule>',
        '',
        '<IfModule mod_headers.c>',
        '    # Cache-Control für statische Assets',
        '    <FilesMatch "\.(css|js|jpg|jpeg|png|gif|webp|avif|svg|ico|woff|woff2|ttf|otf|mp4|webm)$">',
        '        Header set Cache-Control "public, max-age=31536000, immutable"',
        '    </FilesMatch>',
        '',
        '    # Login-Seite nicht cachen (Security Feature)',
        '    <FilesMatch "^wp-login\.php$">',
        '        Header set Cache-Control "no-cache, no-store, must-revalidate"',
        '        Header set Pragma "no-cache"',
        '        Header set Expires "0"',
        '    </FilesMatch>',
        '</IfModule>'
    );

    insert_with_markers( $htaccess_file, 'Behrmedia Base Settings', $rules );
}

register_deactivation_hook( __FILE__, 'behrmedia_performance_remove_htaccess_rules' );
function behrmedia_performance_remove_htaccess_rules() {
    $htaccess_file = get_home_path() . '.htaccess';
    insert_with_markers( $htaccess_file, 'Behrmedia Base Settings', array() );
}