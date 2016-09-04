<?php
namespace calderawp\theme;

class page_settings extends \calderawp\theme\options{

    /**
     * @inheritDoc
     */
    protected $defaults = [
        'show_menu' => true,
        'full_width_header' => true,
    ];

    /**
     * @return bool
     */
    public function show_menu(){
        return $this->settings[ 'show_menu' ];

    }

    /**
     * @return bool
     */
    public function full_width_header(){
        return $this->settings[ 'full_width_header' ];
    }

}