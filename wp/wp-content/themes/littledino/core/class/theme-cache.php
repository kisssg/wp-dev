<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
/**
* LittleDino Theme Cache
*
*
* @class        LittleDino_Theme_Cache
* @version      1.0
* @category Class
* @author       WebGeniusLab
*/

if (!class_exists('LittleDino_Theme_Cache')) {
    class LittleDino_Theme_Cache{

        private static $instance = null;
        public static function get_instance( ) {
            if ( null == self::$instance ) {
                self::$instance = new self( );
            }

            return self::$instance;
        }

        /**
         * @return array
         */
        public static function cache_query($args = array()){
            $args['update_post_term_cache'] = false; // don't retrieve post terms
            $args['update_post_meta_cache'] = false; // don't retrieve post meta
            $k = http_build_query( $args );
            $custom_query = wp_cache_get( $k, 'littledino_theme' );
            if ( false ===  ($custom_query) ) {
                $custom_query = new WP_Query( $args );
                if ( ! is_wp_error( $custom_query ) && $custom_query->have_posts() ) {
                    wp_cache_set( $k, $custom_query, 'littledino_theme' );
                }
            }
            return $custom_query;       
        }
        
    }
}
?>