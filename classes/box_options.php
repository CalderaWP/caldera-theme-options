<?php

namespace calderawp\theme;


class box_options extends options{

    /**
     * @inheritDoc
     */
    protected $defaults = [
        'home' => true,
        'download' => true,
        'taxonomy' => false,
        'archive' => false,

    ];

    /**
     * Whether to use box layout or not
     *
     * @param $context
     *
     * @return bool
     */
    public function use_boxes( $context = false  ){
        if( ! $context ){
            $context = $this->find_context();
        }
        if( in_array( $context, array_keys( $this->settings ) ) ){
            return $this->settings[ $context ];
        }

        return false;
    }

    public function find_context(){
        if( is_home() ){
            return 'home';
        }elseif ( is_archive() && 'download' == get_post_type() ){
            return 'download';
        }elseif( is_archive() ) {
            return 'archive';
        }elseif ( is_tax() ){
            return 'taxonomy';
        }

    }


}