<?php 
/*
Copyright 2014 Jonatan Jumbert (email : jonatan.jumbert@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

Plugin Name: Template SEO Checker
Plugin URI: http://jonatanjumbert.com/blog/wordpress/template-seo-checker/?utm_source=Wordpress&utm_medium=Plugin&utm_term=Template%20SEO%20Checker&utm_campaign=Wordpress%20plugins
Description: This plugin allows you to check if current template your are using for your website is SEO friendly or not. <strong>Why is this important?</strong> If you want to reach as many people as possible, first of all you might take care about your <strong>SEO Onsite</strong>. And this is exactly what this plugin does. If you are looking more information about SEO optimization check my <a href="http://jonatanjumbert.com/blog/?utm_source=Wordpress&utm_medium=Plugin&utm_term=Template%20SEO%20Checker&utm_campaign=Wordpress%20plugins" title="Blog about SEO, Marketing, Social Media and more...">SEO blog</a> and enjoy yourself!. 
Version: 1.0.4
Author: Jonatan Jumbert
Author URI: http://jonatanjumbert.com/?utm_source=Wordpress&utm_medium=Plugin&utm_term=Template%20SEO%20Checker&utm_campaign=Wordpress%20plugins
License: GPLv2 or later
*/

define('TSC_PLUGIN_URI', plugins_url('', __FILE__));
define('TSC_PLUGIN_PATH', dirname(__FILE__));

define('TSC_CORE_URI', trailingslashit(TSC_PLUGIN_URI) . 'core');
define('TSC_CORE_PATH', trailingslashit(TSC_PLUGIN_PATH) . 'core');
define('TSC_LANG_URI', trailingslashit(TSC_PLUGIN_URI) . 'lang');
define('TSC_LANG_PATH', trailingslashit(TSC_PLUGIN_PATH) . 'lang');
define('TSC_MEDIA_URI', trailingslashit(TSC_PLUGIN_URI) . 'media');
define('TSC_MEDIA_PATH', trailingslashit(TSC_PLUGIN_PATH) . 'media');
define('TSC_CONTROLLERS_URI', trailingslashit(TSC_CORE_URI) . 'controllers');
define('TSC_CONTROLLERS_PATH', trailingslashit(TSC_CORE_PATH) . 'controllers');
define('TSC_LIBRARIES_URI', trailingslashit(TSC_CORE_URI) . 'libraries');
define('TSC_LIBRARIES_PATH', trailingslashit(TSC_CORE_PATH) . 'libraries');
define('TSC_MODELS_URI', trailingslashit(TSC_CORE_URI) . 'models');
define('TSC_MODELS_PATH', trailingslashit(TSC_CORE_PATH) . 'models');
define('TSC_PLUGIN_PAGE_NAME', 'template-seo-checker');

if(!defined('TSC_VERSION_KEY')) define('TSC_VERSION_KEY', 'jja_tsc_version');
if(!defined('TSC_VERSION_NUM')) define('TSC_VERSION_NUM', '1.0');

register_activation_hook(__FILE__, 'jja_tsc_install_template_seo_checker_admin');
register_uninstall_hook(__FILE__, 'jja_tsc_uninstall_template_seo_checker_admin');
register_deactivation_hook(__FILE__, 'jja_tsc_deactivation_template_seo_checker_admin');

require_once(ABSPATH . 'wp-includes/pluggable.php');
require_once(trailingslashit(TSC_CONTROLLERS_PATH) . 'jja_tsc_main_functions.php');
require_once(trailingslashit(TSC_MODELS_PATH) . 'jja_tsc_errors_model.php');
require_once(trailingslashit(TSC_MODELS_PATH) . 'jja_tsc_url_errors_model.php');
require_once(trailingslashit(TSC_MODELS_PATH) . 'jja_tsc_urls_parsed_model.php');
if(is_admin()) {
	require_once(trailingslashit(TSC_CONTROLLERS_PATH) . 'jja_tsc_config_page_controller.php');
	if(!class_exists('simple_html_dom')) {
		require_once(trailingslashit(TSC_LIBRARIES_PATH) . 'simple_html_dom.php');
	}
	require_once(trailingslashit(TSC_LIBRARIES_PATH) . 'wp_tables.php');
}

$currentLocale = get_locale();
$moFile = (!empty($currentLocale)) ? dirname(__FILE__) . "/lang/template-seo-checker-" . $currentLocale . ".mo" : dirname(__FILE__) . "/lang/template-seo-checker-en_EN.mo";
if(@file_exists($moFile) && is_readable($moFile)) load_textdomain('tsc', $moFile);

/**
 * Install function with default plugin options
 * @return unknown_type
 */
function jja_tsc_install_template_seo_checker_admin() {
	if(!function_exists('curl_exec')) {
		die(__('<strong>Template SEO Checker</strong> need <a href="http://se1.php.net/manual/en/book.curl.php" target="_blank">CURL library</a> to work.', 'tsc'));
		return;
	}
	$def_version = get_option(TSC_VERSION_KEY, FALSE);

	if(!$def_version) {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$sql_tsc_urls_parsed = "CREATE TABLE IF NOT EXISTS `tsc_urls_parsed` (
			`id` INT(10) NOT NULL AUTO_INCREMENT,
			`url` VARCHAR(255) NOT NULL,
			`post_name` VARCHAR(200) NOT NULL,
			`score` FLOAT NOT NULL,
			`improvement` FLOAT NOT NULL,
			`time_checked` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (`id`),
			KEY `url` (`url`),
			KEY `post_name` (`post_name`)
		) COLLATE='utf8_general_ci';";
		$sql_tsc_errors = "CREATE TABLE IF NOT EXISTS `tsc_errors` (
		  `id` int(10) NOT NULL AUTO_INCREMENT,
		  `error` varchar(64) NOT NULL,
		  `coef` float NOT NULL,
		  PRIMARY KEY (`id`)
		) COLLATE='utf8_general_ci';";
		$sql_tsc_url_errors = "CREATE TABLE IF NOT EXISTS `tsc_url_errors` (
		  `id` int(10) NOT NULL AUTO_INCREMENT,
		  `url` varchar(255) NOT NULL,
		  `id_error` int(10) NOT NULL DEFAULT '0',
		  `num_elements` varchar(2048) DEFAULT '',
		  `comments` varchar(2048) DEFAULT '',
		  `score` int(10) NOT NULL,
		  PRIMARY KEY (`id`),
		  KEY `url` (`url`)
		)COLLATE='utf8_general_ci'";
		
		dbDelta($sql_tsc_urls_parsed);
		dbDelta($sql_tsc_errors);
		dbDelta($sql_tsc_url_errors);
		
		global $wpdb;
		$wpdb->query("INSERT INTO `tsc_errors` (`id`, `error`, `coef`) VALUES
			(1, 'Robots', 1),
			(2, 'Inline styles', 0.12),
			(3, 'Responsive ready', 0.11),
			(4, 'Headings evaluation', 0.1),
			(5, 'Images size', 0.09),
			(6, 'Number of CSS', 0.08),
			(7, 'Number of JS', 0.08),
			(8, 'Title', 0.04),
			(9, 'Description', 0.03),
			(10, 'CSS size', 0.06),
			(11, 'JS size', 0.06),
			(12, 'Links without title', 0.05),
			(13, 'Images without alt', 0.05),
			(14, 'DOM elements', 0.05),
			(15, 'HTML5 ready', 0.05),
			(16, 'Images without height and width', 0.02),
			(17, 'CSS for print', 0.01)");
		add_option(TSC_VERSION_KEY, TSC_VERSION_NUM);
		
		// Setting errors coefients in wordpress cache for one day
		jja_tsc_set_coeficients();
	} else {
		if($def_version != TSC_VERSION_NUM) {
			update_option(TSC_VERSION_KEY, TSC_VERSION_NUM);
		}
	}
}

/**
 * Deactivation function
 */
function jja_tsc_deactivation_template_seo_checker_admin() {
	// Nothing to do... we preverse user options for when he decide activate again the plugin.
}

/**
 * Uninstall plugin functions, delete configuration.
 * @return unknown_type
 */
function jja_tsc_uninstall_template_seo_checker_admin() {
	delete_option(TSC_VERSION_KEY);
	delete_option('jja_tsc_summary_report');
	
	$errorsModel = jja_tsc_errors_model::get_instance();
	$errors = $errorsModel->get_all_errors();
	foreach($errors as $error) {
		delete_transient('tsc_coef_'.$error->error);
		delete_transient('tsc_error_key_'.$error->error);
		delete_transient('tsc_error_id_'.$error->id);
	}
 
	global $wpdb;
	$wpdb->query("DROP TABLE IF EXISTS `tsc_urls_parsed`");
	$wpdb->query("DROP TABLE IF EXISTS `tsc_errors`");
	$wpdb->query("DROP TABLE IF EXISTS `tsc_url_errors`");
}

/**
 * Set plugin config page on wordpress menu
 * @return unknown_type
*/
function jja_tsc_template_seo_checker_page() {
	add_menu_page(__('Template SEO Checker', 'tsc'), __('Template SEO Checker', 'tsc'), 'activate_plugins', TSC_PLUGIN_PAGE_NAME, 'jja_template_seo_checker_config_page', '');
	
	// Setting up errors copies
	__('Robots', 'tsc');
	__('Inline styles', 'tsc');
	__('Responsive ready', 'tsc');
	__('Headings evaluation', 'tsc');
	__('Images size', 'tsc');
	__('Number of CSS', 'tsc');
	__('Number of JS', 'tsc');
	__('Title', 'tsc');
	__('Description', 'tsc');
	__('CSS size', 'tsc');
	__('JS size', 'tsc');
	__('Links without title', 'tsc');
	__('Images without alt', 'tsc');
	__('DOM elements', 'tsc');
	__('HTML5 ready', 'tsc');
	__('Images without height and width', 'tsc');
	__('CSS for print', 'tsc');
}

if(current_user_can('activate_plugins')) {
	add_action('admin_menu', 'jja_tsc_template_seo_checker_page');
	$non_tsc_post_types = array('attachment', 'revision', 'nav_menu_item');
	global $non_tsc_post_types;
	
	// ajax function
	add_action('wp_ajax_jja_tsc_get_score', 'jja_tsc_get_score_callback');
}
?>