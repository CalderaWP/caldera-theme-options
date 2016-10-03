<?php

namespace calderawp\theme;

/**
 * Class theme
 *
 * The smelly god class for theme
 *
 * @package calderawp\theme
 */
class theme{

    /**
     * Option key for whether or not this site uses box type index or not.
     */
    CONST BOX_KEY = 'caldera_theme_box_settings';

    /**
     * Meta key for show menu option
     *
     * @string
     */
    CONST PAGE_MENU_KEY = 'caldera_theme_show_menu';

    /**
     * Meta key for full width option
     */
    CONST PAGE_FW_KEY = 'caldera_theme_fullwidth';

    /**
     * Meta key for product images
     */
    CONST PRODUCT_IMAGES_KEY = 'caldera_theme_product_images';

    /**
     * Meta key for product tagline
     */
    CONST TAGLINE_KEY = 'product_tagline';

    /**
     * Meta key for product order
     */
    CONST ORDER_KEY = 'order';

	/**
	 * Meta key for no product image
	 */
	CONST NO_COLOR_IMAGE = 'caldera_theme_no_product_image';

    /**
     * @var theme
     */
    protected static $instance;

    /**
     * Holds download a page settings instances
     *
     * @var array
     */
    protected $settings = [];

    /**
     * @var box_options
     */
    protected $box_options;

    /**
     * CM2 loader class (not a CMB2 instance)
     *
     * @var cmb2
     */
    protected $cmb2;

    /**
     * @var default_options;
     */
    protected  $default_options;

    /**
     * @return theme
     */
    public static function get_instance(){
        if( null === static::$instance ){
            static::$instance = new static();
        }

        return static::$instance;
    }

    private function __construct(){

        $this->box_options = new box_options( get_option( self::BOX_KEY, [] ) );
        $this->cmb2 = new cmb2();
    }

    /**
     * Get the box options
     *
     * @return box_options
     */
    public function get_box_options(){
        return $this->box_options;
    }

    /**
     * Get ID of the FooGallery used for the download image gallery
     *
     * @return int
     */
    public function get_download_foo_gallery_id(){
        return intval( apply_filters('caldera_theme_download_foo_gallery_id', 28447 ) );
    }

    /**
     * Get a page settings or download_settings instance. Handles lazyloading/cache/etc
     *
     * @param $id
     * @return page_settings|download_settings|\stdClass
     */
    public function get_settings( $id ){

        if( ! isset( $this->settings[ $id ] ) ) {
            if ( 'page' == get_post_type( $id ) ) {
                $this->settings[$id] = $this->create_a_page_settings($id);
            } elseif( 'download' == get_post_type( $id ) ) {
                $this->settings[$id] = $this->create_a_download_settings( $id );
            }else{
                if ( null == $this->default_options ) {
                    $this->default_options = new default_options();
                }

                return $this->default_options;

            }
        }

        return $this->settings[ $id ];
    }

    /**
     * Create a new page_settings instance
     *
     * @param int $id
     * @return page_settings
     */
    protected function create_a_page_settings($id){
        $data = [];
        $metas = [
            'show_menu' => self::PAGE_MENU_KEY,
            'full_width_header' => self::PAGE_FW_KEY,
        ];

        foreach( $metas as $meta => $key ){
            $data[ $meta ] = get_post_meta( $id, $key, true );
        }

       return new page_settings($data);
    }

    /**
     * Create a new download_settings instance
     *
     * @param int $id
     * @return download_settings
     */
    protected function create_a_download_settings($id){
        $data = [];
        $metas = [
            'show_menu' => self::PAGE_MENU_KEY,
            'full_width_header' => self::PAGE_FW_KEY,
            'images' => self::PRODUCT_IMAGES_KEY,
            'tagline' => self::TAGLINE_KEY,
            'order' => self::ORDER_KEY
        ];

        foreach( $metas as $meta => $key ){
            $data[ $meta ] = get_post_meta( $id, $key, true );
        }

        return new download_settings($data);
    }


}