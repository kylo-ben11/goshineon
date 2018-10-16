<?php /* 
Plugin Name: Replace wp-admin logo
Plugin URI: http://www.help4cms.com/ 
Version: 0.1 
Author: Mudit Kumawat 
Description: This plugin Replace wp-admin Default logo 
*/





define('directory', plugins_url('replace-wp-admin-logo') );
$options = get_option('replace_wp_admin_logo');
//print_r($options);
if(!empty($options )){
extract($options);
};




class replace_wp_admin_logo_Admin {

    /**
     * Option key, and option page slug
     * @var string
     */
    private $key = 'replace_wp_admin_logo';

    /**
     * Array of metaboxes/fields
     * @var array
     */
    protected $option_metabox = array();

    /**
     * Options Page title
     * @var string
     */
    protected $title = '';

    /**
     * Options Page hook
     * @var string
     */
    protected $options_page = '';

    /**
     * Constructor
     * @since 0.1.0
     */
    public function __construct() {
		
		
$menus = get_terms('nav_menu',array('hide_empty'=>false));
$menu = array();
foreach( $menus as $m ) {
$menu[$m->name] = $m->name;
	}
		
        // Set our title
        $this->title = __( 'Replace wp-admin logo', 'Replace wp-admin logo' );

        // Set our Replace wp-admin logo Admin Fields
        $this->fields = array(
		
 array(
                'name'    => __( 'Upload Logo Here ', 'Replacewp-adminlogo' ),
                'desc'    => __( 'This is the Logo Replace wp-admin logo', 'Replacewp-adminlogo' ),
                'id'      => 'wp_admin_logo',
                'type'    => 'file',
              
            ),
		
		 array(
                'name'    => __( 'Logo Width ', 'Replacewp-adminlogo' ),
                'desc'    => __( 'Add here logo image width ', 'Replacewp-adminlogo' ),
                'id'      => 'logo_width',
                'type'    => 'text_small',
              
            ),
			
			
			 array(
                'name'    => __( 'Logo Height ', 'Replacewp-adminlogo' ),
                'desc'    => __( 'Add here logo image height', 'Replacewp-adminlogo' ),
                'id'      => 'logo_height',
                'type'    => 'text_small',
              
            ),
		
		
		
			
        );
    }

    /**
     * Initiate our hooks
     * @since 0.1.0
     */
    public function hooks() {
        add_action( 'admin_init', array( $this, 'init' ) );
        add_action( 'admin_menu', array( $this, 'add_options_page' ) );
    }

    /**
     * Register our setting to WP
     * @since  0.1.0
     */
    public function init() {
        register_setting( $this->key, $this->key );
    }

    /**
     * Add menu options page
     * @since 0.1.0
     */
    public function add_options_page() {
        $this->options_page = add_menu_page( $this->title, $this->title, 'manage_options', $this->key, array( $this, 'admin_page_display' )  ,'dashicons-art');
    }


    public function admin_page_display() {
        ?>

<div class="wrap replace_wp_admin_logo_page <?php echo $this->key; ?>">
  <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
  <?php cmb_metabox_form( $this->option_metabox(), $this->key ); ?>
</div>
<?php
    }


    public function option_metabox() {
        return array(
            'id'         => 'option_metabox',
            'show_on'    => array( 'key' => 'options-page', 'value' => array( $this->key, ), ),
            'show_names' => true,
            'fields'     => $this->fields,
        );
    }

 
    public function __get( $field ) {

// Allowed fields to retrieve
        if ( in_array( $field, array( 'key', 'fields', 'title', 'options_page' ), true ) ) {
            return $this->{$field};
        }
        if ( 'option_metabox' === $field ) {
            return $this->option_metabox();
        }

        throw new Exception( 'Invalid property: ' . $field );
    }

}

// Get it started
$replace_wp_admin_logo_Admin = new replace_wp_admin_logo_Admin();
$replace_wp_admin_logo_Admin->hooks();


function replace_wp_admin_logo_get_option( $key = '' ) {
    global $replace_wp_admin_logo_Admin;
    return cmb_get_option( $replace_wp_admin_logo_Admin->key, $key );
}




// Initialize the metabox class
add_action( 'init', 'replace_wp_admin_logo_meta_boxes', 9999 );
function replace_wp_admin_logo_meta_boxes() {
    if ( !class_exists( 'cmb_Meta_Box' ) ) {
        require_once( 'metabox/init.php' );
    }
}







// Add custom logo in wp-admin
function help4cms_wp_admin_custom_logo() { 
global  $wp_admin_logo,$logo_width,$logo_height;
if(!empty($wp_admin_logo)):
?>
<style type="text/css">
body.login div#login h1 a {
background-image: url(<?php echo $wp_admin_logo?>);  /* Add Here your Logo Path*/
<?php if(!empty($logo_width)): ?>
background-size: <?php  echo $logo_width ?>px;  <?php endif; ?>  /* Change Here  background-size according to  Your Logo size */
<?php if(!empty($logo_height)):?>    height: <?php echo $logo_height; ?>px; <?php endif; ?> /* Change Here  height according to  Your Logo size */
<?php if(!empty($logo_width)):?>    width: <?php  echo $logo_width ?>px;  <?php endif; ?> /*  Change Here  width according to  Your Logo size  */
}
</style>
<?php 
endif;
} add_action( 'login_enqueue_scripts', 'help4cms_wp_admin_custom_logo' );