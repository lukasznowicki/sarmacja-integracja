<?php
/*
Plugin Name: KS User
Plugin URI: http://fc.sarmacja.org/viewtopic.php?f=726&t=24413
Description: Wtyczka umożliwiająca integrację systemów Księstwa Sarmacji z WP
Version: 0.1.1
Author: Ł.N.
Author URI: https://lukasznowicki.info/
License: MIT
License URI: https://raw.githubusercontent.com/lukasznowicki/sarmacja-integracja/master/LICENSE
*/

namespace KS\Plugin\User;

defined( 'ABSPATH' ) or exit;

define( __NAMESPACE__ . '\PLUGIN_CLASS_DIR', trailingslashit( __DIR__ ) . 'KS' . \DIRECTORY_SEPARATOR . 'Plugin' . \DIRECTORY_SEPARATOR . 'User' . \DIRECTORY_SEPARATOR );

require_once PLUGIN_CLASS_DIR . 'Autoloader.php';

$ksuser_autoloader = new Autoloader();
$ksuser_autoloader->register();
$ksuser_autoloader->add_namespace( __NAMESPACE__, PLUGIN_CLASS_DIR );

new Plugin();

// tutaj będzie jeszcze deaktywacja, ale to temat na koniec
