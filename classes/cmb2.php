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
            'name'    => 'Product Images',
            'desc'    => 'Used for front-end images, featured image will be set first.',
            'id'      => theme::PRODUCT_IMAGES_KEY,
            'type'    => 'file_list',
            'repeatable' => true,
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
}