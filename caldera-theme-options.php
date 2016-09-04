<?php
/**
 Plugin Name: Caldera Theme Options
 */
/**
 * Load the classes
 */
add_action('init', function () {
    spl_autoload_register(function ($class) {
        if (0 === strpos($class, "calderawp\\theme\\")) {
            $file = __DIR__ . '/classes/' . str_replace("calderawp\\theme\\", '', $class) . '.php';
            include_once $file;
        }

    });

    \calderawp\theme\theme::get_instance();
});