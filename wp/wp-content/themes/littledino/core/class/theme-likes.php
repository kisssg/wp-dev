<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
/**
* LittleDino Theme Like
*
*
* @class        LittleDino_Theme_Like
* @version      1.0
* @category Class
* @author       WebGeniusLab
*/

if(!class_exists('LittleDino_Theme_Like')) {
    class LittleDino_Theme_Like{

        private static $instance = null;
        public static function get_instance( ) {
            if ( null == self::$instance ) {
                self::$instance = new self( );
            }

            return self::$instance;
        }
        public function __construct(){
            add_action( 'wp_ajax_nopriv_littledino_like', array( $this, 'init' ));
            add_action( 'wp_ajax_littledino_like', array( $this, 'init' ));
        }

        public function init(){
            // Security
            $nonce = isset( $_REQUEST['nonce'] ) ? sanitize_text_field( $_REQUEST['nonce'] ) : 0;
            if ( !wp_verify_nonce( $nonce, 'simple-likes-nonce' ) ) {
                exit( esc_html__( 'Not permitted', 'littledino' ) );
            } 
            if(!function_exists('wgl_simple_likes')){
                return;
            }
            // Test if javascript is disabled
            $disabled = ( isset( $_REQUEST['disabled'] ) && $_REQUEST['disabled'] == true ) ? true : false;
            // Test if this is a comment
            $is_comment = ( isset( $_REQUEST['is_comment'] ) && $_REQUEST['is_comment'] == 1 ) ? 1 : 0;
            // Base variables
            $post_id = ( isset( $_REQUEST['post_id'] ) && is_numeric( $_REQUEST['post_id'] ) ) ? $_REQUEST['post_id'] : '';
            
            $result = array();
            $post_users = NULL;
            $like_count = 0;
            // Get options

            if ( $post_id != '' ) {
                
                $count = ( $is_comment == 1 ) ? get_comment_meta( $post_id, '_comment_like_count', true ) : get_post_meta( $post_id, "_post_like_count", true ); // like count
                
                $count = ( isset( $count ) && is_numeric( $count ) ) ? $count : 0;
                
                if ( !wgl_simple_likes()->littledino_already_liked( $post_id, $is_comment ) ) { // Like the post
                    
                    if ( is_user_logged_in() ) { // user is logged in
                        $user_id = get_current_user_id();
                        $post_users = wgl_simple_likes()->littledino_post_user_likes( $user_id, $post_id, $is_comment );
                        if ( $is_comment == 1 ) {
                            // Update User & Comment
                            $user_like_count = get_user_option( '_comment_like_count', $user_id );
                            $user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
                            update_user_option( $user_id, '_comment_like_count', ++$user_like_count );
                            if ( $post_users ) {
                                update_comment_meta( $post_id, "_user_comment_liked", $post_users );
                            }
                        } else {
                            // Update User & Post
                            $user_like_count = get_user_option( '_user_like_count', $user_id );
                            $user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
                            update_user_option( $user_id, '_user_like_count', ++$user_like_count );
                            if ( $post_users ) {
                                update_post_meta( $post_id, '_user_liked', $post_users );
                            }
                        }
                    } else { // user is anonymous
                        $user_ip = wgl_simple_likes()->littledino_like_get_ip();
                        $post_users = wgl_simple_likes()->littledino_post_ip_likes( $user_ip, $post_id, $is_comment );
                        // Update Post
                        if ( $post_users ) {
                            if ( $is_comment == 1 ) {
                                update_comment_meta( $post_id, '_user_comment_IP', $post_users );
                            } else { 
                                update_post_meta( $post_id, '_user_IP', $post_users );
                            }
                        }
                    }
                    $like_count = ++$count;
                    $response['status'] = "liked";
                    $response['icon'] = wgl_simple_likes()->littledino_get_liked_icon();
                } else { // Unlike the post
                    if ( is_user_logged_in() ) { // user is logged in
                        $user_id = get_current_user_id();
                        $post_users = wgl_simple_likes()->littledino_post_user_likes( $user_id, $post_id, $is_comment );
                        // Update User
                        if ( $is_comment == 1 ) {
                            $user_like_count = get_user_option( '_comment_like_count', $user_id );
                            $user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
                            if ( $user_like_count > 0 ) {
                                update_user_option( $user_id, '_comment_like_count', --$user_like_count );
                            }
                        } else {
                            $user_like_count = get_user_option( '_user_like_count', $user_id );
                            $user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
                            if ( $user_like_count > 0 ) {
                                update_user_option( $user_id, '_user_like_count', --$user_like_count );
                            }
                        }
                        // Update Post
                        if ( $post_users ) {    
                            $uid_key = array_search( $user_id, $post_users );
                            unset( $post_users[$uid_key] );
                            if ( $is_comment == 1 ) {
                                update_comment_meta( $post_id, '_user_comment_liked', $post_users );
                            } else { 
                                update_post_meta( $post_id, '_user_liked', $post_users );
                            }
                        }
                    } else { // user is anonymous
                        $user_ip = wgl_simple_likes()->littledino_like_get_ip();
                        $post_users = wgl_simple_likes()->littledino_post_ip_likes( $user_ip, $post_id, $is_comment );
                        // Update Post
                        if ( $post_users ) {
                            $uip_key = array_search( $user_ip, $post_users );
                            unset( $post_users[$uip_key] );
                            if ( $is_comment == 1 ) {
                                update_comment_meta( $post_id, '_user_comment_IP', $post_users );
                            } else { 
                                update_post_meta( $post_id, '_user_IP', $post_users );
                            }
                        }
                    }
                    $like_count = ( $count > 0 ) ? --$count : 0; // Prevent negative number
                    $response['status'] = "unliked";
                    $response['icon'] = wgl_simple_likes()->littledino_get_unliked_icon();
                }
                if ( $is_comment == 1 ) {
                    update_comment_meta( $post_id, '_comment_like_count', $like_count );
                    update_comment_meta( $post_id, '_comment_like_modified', date( 'Y-m-d H:i:s' ) );
                } else { 
                    update_post_meta( $post_id, '_post_like_count', $like_count );
                    update_post_meta( $post_id, '_post_like_modified', date( 'Y-m-d H:i:s' ) );
                }
                $response['count'] = wgl_simple_likes()->get_like_count( $like_count );
                $response['testing'] = $is_comment;
                if ( $disabled == true ) {
                    if ( $is_comment == 1 ) {
                        wp_redirect( get_permalink( get_the_ID() ) );
                        exit();
                    } else {
                        wp_redirect( get_permalink( $post_id ) );
                        exit();
                    }
                } else {
                    wp_send_json( $response );
                }
            }
        }
        
    }

    new LittleDino_Theme_Like();
}
?>