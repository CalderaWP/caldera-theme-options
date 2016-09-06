<?php

namespace calderawp\theme;

abstract  class options {

    /**
     * @var array
     */
    protected $settings;

    /**
     * @var array
     */
    protected $defaults;

    /**
     * options constructor.
     * @param array $settings Stored settings
     */
    public function __construct( array  $settings = array() ){
        $this->set_settings($settings);
    }

    /**
     * @return array
     */
    public function get_defaults(){
        return $this->defaults;
    }

    /**
     * @param array $settings
     */
    protected function set_settings(array $settings){
        if (! empty( $settings )) {
            foreach ($settings as $key => $setting) {
                if (! array_key_exists($key, $this->defaults)) {
                    unset($settings[$key]);
                }
            }
            $this->settings = wp_parse_args($settings, $this->defaults);
        } else {
            $this->settings = $this->defaults;
        }


    }
}