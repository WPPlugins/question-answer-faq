<?php
/**
 * @author  mideal
 * @package Question answer
 * @version 1.2
 */
/*
Plugin Name: Question answer
Plugin URI: http://mideal.ru/contacts/
Description: Question answer, ajax, bootstrap plugin with gravatar avatar and Google reCaptcha 2. Looks like chat.
Author: Mideal
Version: 1.2
Author URI: http://mideal.ru/
*/
/*  Copyright 2017  mideal  (email: midealf@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


if (!defined('ABSPATH'))
{
    exit;
}
if ( ! class_exists( 'MidealQA' ) ) :

final class MidealQA {

    public function __construct() {
        $this->define_constants();
        $this->includes();
    }

    private function define_constants() {
        $this->define( 'MQA_PLUGIN_URL', plugins_url( '', __FILE__ ) );
        $this->define( 'MQA_PLUGIN_FILE', __FILE__ );
        $this->define( 'MQA_ABSPATH', dirname( __FILE__ ) . '/' );
    }

    private function define( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
        }
    }

    /**
     * Проверка типа запроса
     *
     * @param  string $type admin, ajax, cron or frontend.
     * @return bool
     */
    private function is_request( $type ) {
        switch ( $type ) {
            case 'admin' :
                return is_admin();
            case 'ajax' :
                return defined( 'DOING_AJAX' );
            case 'cron' :
                return defined( 'DOING_CRON' );
            case 'frontend' :
                return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
        }
    }

    public function includes() {

        if ( $this->is_request( 'admin' ) ) {
            include_once( MQA_ABSPATH . 'includes/admin/admin.php' );
        }


        if ( $this->is_request( 'frontend' ) ) {
            $this->frontend_includes();
        }
    }

    /**
     * Include required frontend files.
     */
    public function frontend_includes() {
        include_once( MQA_ABSPATH . 'includes/frontend.php' );
    }


}
endif;

$MidealQA = new MidealQA();


add_action( 'plugins_loaded', 'mideal_faq_init' );
function mideal_faq_init(){


    // --------------------------------- Add support ajax----------------------------------
    wp_localize_script('mideal-faq-base', 'myajax', 
        array(
            'url' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'myajax-nonce' )
        )
    );  


    // ------------------------------------ Add translate------------------------------------
    load_plugin_textdomain( 'mideal-faq', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    
    // ------------------------------- Add script translate---------------------------------
    $translation_array = array( 
     'errorajax' => __( 'Unfortunately, an error occurred. Try again later please', "mideal-faq" ),
     'okajax' => __( 'Thank you for your question. It will appear after moderation', "mideal-faq" ),
     'publish' => __("Publish", "mideal-faq"),
     'unpublish' => __("Unpublish", "mideal-faq"),
     'nogooglecapcha' => __("Google capcha check error", "mideal-faq"),
    );
    wp_localize_script( 'mideal-faq-base', 'mideal_faq_l10n', $translation_array );




// --------------------add setting linc in admin panel----------------------------
function mideal_faq_add_settings_link( $links ) {
    $settings_link = '<a href="edit.php?post_type=mideal_faq&page=settings">' . __( 'Settings' ) . '</a>';
    array_push( $links, $settings_link );
    return $links;
}
$plugin = plugin_basename( __FILE__ );
    add_filter( "plugin_action_links_{$plugin}", 'mideal_faq_add_settings_link' );
}