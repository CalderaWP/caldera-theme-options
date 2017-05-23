<?php

namespace calderawp\theme;

/**
 * Class cmb2
 *
 * Make cmb2 go
 *
 * @package calderawp\theme
 */
class cmb2{

    /**
     * @var \CMB2
     */
    protected $download;

    /**
     * @var \CMB2
     */
    protected $common;

    public function __construct(){

        add_action( 'cmb2_admin_init', [ $this, 'make_boxes' ] );
        add_action( 'cmb2_admin_init', [ $this, 'add_download_fields' ], 25 );
        add_action( 'cmb2_admin_init', [ $this, 'add_common_fields' ], 30 );

	    /** Options Page */
	    add_action( 'admin_menu', [ $this, 'add_options_page' ] );
	    add_action( 'cmb2_admin_init', [ $this, 'add_options_page_metabox' ] );
    }

    /**
     * Make meta boxes
     *
     * @uses "cmb2_admin_init" action
     */
    public function make_boxes(){
        $this->common = new_cmb2_box( array(
            'id'            => 'caldera_theme_page_options',
            'title'         => __( 'Page Options', 'caldera_theme' ),
            'object_types'  => array( 'page', 'download' ),
            'context'       => 'normal',
            'priority'      => 'high',
            'show_names'    => true,

        ) );

        $this->download = new_cmb2_box( array(
            'id'            => 'caldera_theme_download_options',
            'title'         => __( 'Extra Download Options', 'caldera_theme' ),
            'object_types'  => array( 'download' ),
            'context'       => 'normal',
            'priority'      => 'high',
            'show_names'    => true,


        ) );
    }

    /**
     * Add fields to the common metabox
     *
     * @uses "cmb2_admin_init" action
     */
    public function add_common_fields(){
        $this->common->add_field( array(
            'name' => 'Show Menu',
            'desc' => 'Show the menu',
            'id'   => theme::PAGE_MENU_KEY,
            'type' => 'checkbox',
            'default'       => true
        ) );

        $this->common->add_field( array(
            'name' => 'Full width header',
            'desc' => 'Featured image will be full width',
            'id'   => theme::PAGE_FW_KEY,
            'type' => 'checkbox',
            'default'       => false
        ) );
    }

    /**
     * Add fields to the download metabox
     *
     * @uses "cmb2_admin_init" action
     */
    public function add_download_fields(){
        $this->download->add_field( array(
            'name'    => 'Tagline',
            'desc'    => 'Product tagline. Keep short',
            'default' => '',
            'id'      => theme::TAGLINE_KEY,
            'type'    => 'text_medium',
            'sanitization_cb' => 'wp_kses_post',
            'escape_cb'       => 'esc_html',
        ) );

        $this->download->add_field( array(
            'name'    => 'Order',
            'desc'    => 'Used for ordering display of CF Add-ons',
            'default' => '42',
            'id'      => theme::ORDER_KEY,
            'type'    => 'text_small',
            'sanitization_cb' => 'absint',
            'escape_cb'       => 'absint',
            'attributes' => array(
                'type' => 'number',
                'pattern' => '\d*',
            ),
        ) );

	    $this->download->add_field( array(
		    'name'    => 'No color image',
		    'desc'    => 'A version of product banner with no color',
		    'id'      => theme::NO_COLOR_IMAGE,
		    'type'    => 'file',
		    'options' => array(
			    'url' => false, // Hide the text input for the url
		    ),
		    'text'    => array(
			    'add_upload_file_text' => 'Add Image' // Change upload button text. Default: "Add or Upload File"
		    ),

	    ) );


        $this->download->add_field( array(
            'name'    => 'Product Images',
            'desc'    => 'Used for front-end images, featured image will be set first.',
            'id'      => theme::PRODUCT_IMAGES_KEY,
            'type'    => 'file_list',
            'repeatable' => false,
            'options' => array(
                'url' => false
            ),
            'text' => array(
                'add_upload_files_text' => 'Add Image', // default: "Add or Upload Files"
                'remove_image_text' => 'Remove Image', // default: "Remove Image"
                'file_text' => 'Replacement', // default: "File:"
                'file_download_text' => 'Replacement', // default: "Download"
                'remove_text' => 'Replacement', // default: "Remove"
            ),
        ) );






    }

	public function add_options_page() {
		$this->options_page = add_menu_page( 'CL Settings', 'CL Settings', 'manage_options', 'cl_settings', [ $this, 'admin_page_display' ] );
		// Include CMB CSS in the head to avoid FOUC
		//add_action( "admin_print_styles-{$this->options_page}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
	}
	/**
	 * Admin page markup. Mostly handled by CMB2
	 * @since  0.1.0
	 */
	public function admin_page_display() {
		?>
		<div class="wrap cmb2-options-page <?php echo 'cl_settings'; ?>">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<?php cmb2_metabox_form( cl_settings, 'cl_settings' ); ?>
		</div>
		<?php
	}
	/**
	 * Add the options metabox to the array of metaboxes
	 * @since  0.1.0
	 */
	function add_options_page_metabox() {
		// hook in our save notices
		add_action( "cmb2_save_options-page_fields_{cl_settings}", array( $this, 'settings_notices' ), 10, 2 );
		$cmb = new_cmb2_box( array(
			'id'         => 'cl_settings',
			'hookup'     => false,
			'cmb_styles' => false,
			'show_on'    => array(
				// These are important, don't remove
				'key'   => 'options-page',
				'value' => array( 'cl_settings', )
			),
		) );
		// Set our CMB2 fields
		$cmb->add_field( array(
			'name' => __( 'ROI Page', 'caldera_theme' ),
			'desc' => __( 'Page for ROI section', 'caldera_theme' ),
			'id'   => 'roi_page',
			'type' => 'select',
			'options_cb' => [ $this, 'get_pages']
		) );
	}

	function get_pages( $field ) {

		$pages = get_posts( ['post_type' => 'page'] );
		$return_pages = [0 => 'Choose A Page'];
		foreach( $pages as $page ) {
			$return_pages[$page->ID] = $page->post_title;
		}

		return $return_pages;

	}

	/**
	 * Register settings notices for display
	 *
	 * @since  0.1.0
	 * @param  int   $object_id Option key
	 * @param  array $updated   Array of updated fields
	 * @return void
	 */
	public function settings_notices( $object_id, $updated ) {
		if ( $object_id !== 'cl_settings' || empty( $updated ) ) {
			return;
		}
		add_settings_error( 'cl_settings' . '-notices', '', __( 'Settings updated.', 'myprefix' ), 'updated' );
		settings_errors( 'cl_settings' . '-notices' );
	}
	/**
	 * Public getter method for retrieving protected/private variables
	 * @since  0.1.0
	 * @param  string  $field Field to retrieve
	 * @return mixed          Field value or exception is thrown
	 */
	public function __get( $field ) {
		// Allowed fields to retrieve
		if ( in_array( $field, array( 'key', 'metabox_id', 'title', 'options_page' ), true ) ) {
			return $this->{$field};
		}
		throw new Exception( 'Invalid property: ' . $field );
	}
}
