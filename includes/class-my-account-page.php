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

use AdminSettings\AdminSettingsClass;
use MyAccountPagePlugin\InfoTabClass;

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
	 * @var mixed
	 */
	private $current_user;

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

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/InfoTabClass.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/AdminSettingsClass.php';

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

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $this, 'add_menu_page_my_account_page' );
	}


	public function add_menu_page_my_account_page() {
		add_menu_page('My Account Page', 'My Account Page', 'administrator', 'my-account-page-slug', [$this, 'my_account_settings_page'],'dashicons-admin-generic');
	}
	public function my_account_settings_page() {
		include plugin_dir_path( dirname( __FILE__ ) ) . 'includes/AdminSettingsPage.php';
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

		if ( $_SERVER["REQUEST_URI"] === '/my-account/' ) {
			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		}

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'rest_api_init', $this, 'routeReg' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_class_user_data' );
	}

	public function routeReg() {
		register_rest_route( 'my-account/v1', '/switchTabAjax/', array(
			'methods'             => 'POST',
			'callback'            => [ $this, 'switchTabAjax' ],
			'permission_callback' => '__return_true',
			'login_user_id'       => get_current_user_id(),
		) );

		register_rest_route( 'my-account/v1', '/info-tab/', array(
			'methods'             => 'POST',
			'callback'            => [ $this, 'saveUserInfo' ],
			'permission_callback' => '__return_true',
			'login_user_id'       => get_current_user_id(),
		) );

		register_rest_route( 'my-account/v1', '/admin-save-page-settings/', array(
			'methods'             => 'POST',
			'callback'            => [ $this, 'adminSavePageSettings' ],
			'permission_callback' => '__return_true',
		) );
	}

	/**
	 * @throws Exception
	 */
	public function adminSavePageSettings($request) {

		$postData = $_POST;
		AdminSettingsClass::validateInputData($postData);
		AdminSettingsClass::save($postData);
	}

	/**
	 * @throws Exception
	 */
	public function saveUserInfo( $request ) {

		$attrs =  $request->get_attributes();

		if ( empty( $attrs['login_user_id'] ) ) {
			return false;
		}

		$userData = array();
		$userData["user_nicename"]   = $_POST["user_nicename"];
		$userData["user_email"]      = $_POST["user_email"];
		$userData["user_registered"] = $_POST["user_registered"];
		$userData["display_name"]    = $_POST["display_name"];
		$userData["user_url"]        = $_POST["user_url"];
		$userData["nickname"]        = $_POST["nickname"];
		$userData["first_name"]      = $_POST["first_name"];
		$userData["last_name"]       = $_POST["last_name"];
		$userData["description"]     = $_POST["description"];

		$userId = $attrs['login_user_id'];
		unset( $attrs['login_user_id'] );

		$infoTabClass = new InfoTabClass($userId);
		$infoTabClass->saveUserInfo( $userId, $userData );

		return true;
	}


	/**
	 * @throws Exception
	 */
	public function switchTabAjax($request) {

		$this->validate($request);

		ob_start();

		switch ($_POST['tabName']) {
			case "users":
				$this->usersTab();
				break;
			case "my-comments":
				$this->myCommentsTab();
				break;
			case "info":
				$this->infoTab($request->get_attributes());
				break;
			default:
				return false;
		}

		$data = ob_get_contents();
		ob_end_clean();

		return new WP_REST_Response( array(
			'html' => $data,
		) );
	}

	public function usersTab() {
		return include plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/tab-users.php';
	}

	public function myCommentsTab() {
		return include plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/tab-my-comments.php';
	}

	public function infoTab($attrs) {
		if ($_POST['actionWanted'] === 'toGet') {
			return $this->getInfoTab();
		}
	}

	private function getInfoTab()
	{
		$userData = $this->getUserData($this->current_user);
		return include plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/tab-info.php';
	}

	private function getUserData($userId) {

		$userMeta = get_user_meta($userId);
		$userData = get_userdata($userId);

		$data = new stdClass();
		$data->ID              = $userData->ID;
		$data->user_nicename   = $userData->user_nicename;
		$data->user_email      = $userData->user_email;
		$data->user_registered = $userData->user_registered;
		$data->display_name    = $userData->display_name;
		$data->user_url        = $userData->user_url;
		$data->nickname        = $userMeta["nickname"][0];
		$data->first_name      = $userMeta["first_name"][0];
		$data->last_name       = $userMeta["last_name"][0];
		$data->description     = $userMeta["description"][0];

		return $data;
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

	/**
	 * @throws Exception
	 */
	private function validate( $request ) {
		$attrs =  $request->get_attributes();

		if ( empty($attrs['login_user_id']) ) {
			throw new Exception('Login User ID is required.');
		}

		if (empty($_POST['tabName'])) {
			throw new Exception('Tab Name is required.');
		}

		$this->current_user = $attrs['login_user_id'];
	}

}
