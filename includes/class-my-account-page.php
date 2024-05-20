<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://weal.cloud
 * @since      1.0.0
 *
 * @package    My_Account_Page
 * @subpackage My_Account_Page/includes
 */

use MyAccountAdmin\AdminSettingsClass;
use MyAccountRoutes\Routes;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    My_Account_Page
 * @subpackage My_Account_Page/includes
 * @author     leouix <nsht22sola@gmail.com>
 */
class My_Account_Page {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      My_Account_Page_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'MY_ACCOUNT_PAGE_VERSION' ) ) {
			$this->version = MY_ACCOUNT_PAGE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'my-account-page';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - My_Account_Page_Loader. Orchestrates the hooks of the plugin.
	 * - My_Account_Page_i18n. Defines internationalization functionality.
	 * - My_Account_Page_Admin. Defines all hooks for the admin area.
	 * - My_Account_Page_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-my-account-page-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-my-account-page-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-my-account-page-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-my-account-page-public.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Routes.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/AdminSettingsClass.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/InfoTabClass.php';

		$this->loader = new My_Account_Page_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the My_Account_Page_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new My_Account_Page_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new My_Account_Page_Admin( $this->get_plugin_name(), $this->get_version() );

		if ($this->isCurrentAdminPageUrl()) {
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		}

		$this->loader->add_action( 'admin_menu', $this, 'add_menu_page_my_account_page' );
		 $this->loader->add_filter( 'plugin_action_links_' . plugin_dir_path( 'my-account-page/my-account-page.php' ) . 'my-account-page.php', $this, 'my_plugin_settings');
	}

	public function my_plugin_settings( $settings ) {
		$settings[] = '<a href="'. get_admin_url(null, 'admin.php?page=my-account-page-admin') .'">Settings</a>';
		return $settings;
	}

	public function add_menu_page_my_account_page() {

		$adminSettings = new AdminSettingsClass(get_current_user_id());
		add_menu_page(
			'My Account Page',
			'My Account Page',
			'administrator',
			'my-account-page-admin',
			[$adminSettings, 'getMyAccountSettingsPage'],
			'dashicons-admin-generic'
		);
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new My_Account_Page_Public( $this->get_plugin_name(), $this->get_version() );

		 if ($this->isPublicPluginPage()) {
			 $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
			 $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_class_user_data' );
			 $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
			 $this->loader->add_action('init', $this, 'my_custom_add_user_id_to_query_vars');
		 }

		$routesClass = new Routes();
		$this->loader->add_action( 'rest_api_init', $routesClass, 'routeReg' );

		$this->loader->add_action('template_include', $this, 'showPluginContent');

	}

	public function isPublicPluginPage() {
		return $this->getPublicPageUrl() === trim(strtok($_SERVER["REQUEST_URI"], '?'), '/');
	}

	public function getPublicPageUrl() {

		$url = null;
		$cache_key = 'my_account_page_plugin_user_page_url';
		$cache_group = 'my_account_page_plugin';

		$pluginData = wp_cache_get($cache_key, $cache_group);

		if ($pluginData === false) {
			global $wpdb;
			$tablename = $wpdb->prefix . "my_account_page_plugin";
			$sql       = /** @lang text */
				"SELECT `user_page_url` FROM `" . $tablename . "`";
			$results = $wpdb->get_results($sql);
			$pluginData = $results[0] ?? null;
			wp_cache_set($cache_key, $pluginData, $cache_group, 3600);
		}

		if ( ! empty( $pluginData ) && ! empty( $pluginData->user_page_url ) ) {
			$url = $pluginData->user_page_url;
			$url = trim($url, '/');
		}

		return $url;
	}

	public function isCurrentAdminPageUrl() {
		$current_url = $_SERVER['REQUEST_URI'];
		return strpos($current_url, '/admin.php?page=my-account-page-admin') !== false;

	}

	public function showPluginContent($template)
	{
		if ($this->isPublicPluginPage())  {
			if (!is_user_logged_in()) {
				auth_redirect();
			}
			return WP_PLUGIN_DIR . '/my-account-page/public/partials/my-account-page-public-display.php';
		}
		return $template;
	}
	public function my_custom_add_user_id_to_query_vars() {
		global $wp_query;
		$wp_query->set('current_user_id', get_current_user_id());
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    My_Account_Page_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
