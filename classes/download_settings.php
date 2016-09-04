<?php

namespace calderawp\theme;

/**
 * Class download_settings
 *
 * Download settings container
 *
 * @package calderawp\theme
 */
class download_settings extends page_settings{

    /**
     * @inheritDoc
     */
    protected $defaults = [
        'show_menu' => true,
        'full_width_header' => true,
        'images' => [],
        'tagline' => '',
        'order' => 42
    ];

}