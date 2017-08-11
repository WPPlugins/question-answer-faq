<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


// --------------------Admin panel-----------------------------
add_action( 'admin_menu', 'mideal_faq_create_menu' );

function mideal_faq_create_menu() {
    
    add_submenu_page( 'edit.php?post_type=mideal_faq', 'Question answer setting', __( 'Settings' ), 'manage_options', 'settings', 'mideal_faq_settings_page' );
    add_action( 'admin_init', 'register_mideal_faq_settings' );
}

function admin_script( $hook ) {
    /**
     * assets
     */
    wp_enqueue_script( 'mideal-faq-assets-colorpicker', MQA_PLUGIN_URL.'/assets/bootstrap-colorpicker-master/js/bootstrap-colorpicker.min.js', array( 'jquery' ),1.0,true );
    wp_enqueue_script( 'mideal-faq-admin', MQA_PLUGIN_URL.'/js/admin.js', array( 'jquery' ),1.0,true );
}

add_action('admin_enqueue_scripts', 'admin_script');



function admin_css() {
    wp_enqueue_style( 'mideal-faq-admin-style', MQA_PLUGIN_URL.'/css/admin.css',false,'1.0','all' );

    /**
     * assets
     */
    wp_enqueue_style( 'mideal-faq-assets-colorpicker', MQA_PLUGIN_URL.'/assets/bootstrap-colorpicker-master/css/bootstrap-colorpicker.min.css',false,'1.0','all' );
}
add_action('admin_head', 'admin_css');


function register_mideal_faq_settings() {
    register_setting( 'mideal-faq-settings-group', 'mideal_faq_setting_email' );
    register_setting( 'mideal-faq-settings-group', 'mideal_faq_setting_email2' );
    register_setting( 'mideal-faq-settings-group', 'mideal_faq_setting_avatar_smallsize' );
    register_setting( 'mideal-faq-settings-group', 'mideal_faq_setting_recaptcha' );
    register_setting( 'mideal-faq-settings-group', 'mideal_faq_setting_recaptcha_key' );
    register_setting( 'mideal-faq-settings-group', 'mideal_faq_setting_recaptcha_key_secret' );
    register_setting( 'mideal-faq-settings-group', 'mideal_faq_setting_answer_name' );
    register_setting( 'mideal-faq-settings-group', 'mideal_faq_setting_answer_image' );
    register_setting( 'mideal-faq-settings-group', 'mideal_faq_setting_question_background' );
    register_setting( 'mideal-faq-settings-group', 'mideal_faq_setting_question_color_text' );
    register_setting( 'mideal-faq-settings-group', 'mideal_faq_setting_answer_background' );
    register_setting( 'mideal-faq-settings-group', 'mideal_faq_setting_answer_color_text' );
    register_setting( 'mideal-faq-settings-group', 'mideal_faq_setting_button_color_text' );
    register_setting( 'mideal-faq-settings-group', 'mideal_faq_setting_button_background' );
}


function mideal_faq_settings_page() {
?>
<div class="wrap mideal-css">
<h1>Mideal Faq</h1>
<h2 class="nav-tab-wrapper">
    <a class="nav-tab" id="setting-tab" href="#setting">Настройки</a>
    <a class="nav-tab" id="fronted-tab" href="#fronted">Отображение</a>
</h2>
<form method="post" action="options.php">
    <?php settings_fields( 'mideal-faq-settings-group' ); ?>
    <?php do_settings_sections( 'mideal-faq-settings-group' ); ?>
<div id="setting" class="midealfaqtab">
    <table class="form-table">
        <tr valign="top">
            <th scope="row">
                <?php _e( "The E-mail address for notifications about new question", "mideal-faq" );?>
            </th>
            <td>
                <input type="text" name="mideal_faq_setting_email" value="<?php echo esc_attr( get_option( 'mideal_faq_setting_email',get_option( 'admin_email' )) ); ?>" />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <?php _e( "Second E-mail address for notifications about new question", "mideal-faq" );?>
            </th>
            <td>
                <input type="text" name="mideal_faq_setting_email2" value="<?php echo esc_attr( get_option( 'mideal_faq_setting_email2') ); ?>" />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <?php _e( "Name of answer", "mideal-faq" );?> 
            </th>
            <td>
                <input type="text" name="mideal_faq_setting_answer_name" value="<?php echo esc_attr( get_option( 'mideal_faq_setting_answer_name', __("Answer", "mideal-faq")) ); ?>" />
            </td>
        </tr>

        <tr valign="top">
            <th scope="row">
                Google reCAPTCHA
            </th>
            <td>
                <input type="checkbox" name="mideal_faq_setting_recaptcha" data-hide="input-google-recaptcha" class="qa-checkbox-show-el" value="1" <?php echo checked( 1, get_option( 'mideal_faq_setting_recaptcha' ), false ) ;?> />
            </td>
        </tr>
        <tr valign="top" class="input-google-recaptcha">
            <th>
                
            </th>
            <td>
                <a target="_blank" href="https://www.google.com/recaptcha/admin" rel="nofollow"><?php _e( "Add your site in google reCaptcha, and write your key and secret key", "mideal-faq" );?></a>
            </td>
        </tr>
        <tr valign="top" class="input-google-recaptcha">
            <th scope="row">
                <?php _e( "Google recaptcha key", "mideal-faq" );?>
            </th>
            <td>
                <input type="text" name="mideal_faq_setting_recaptcha_key" value="<?php echo esc_attr( get_option( 'mideal_faq_setting_recaptcha_key') ); ?>" />
            </td>
        </tr>
        <tr valign="top" class="input-google-recaptcha">
            <th scope="row">
                <?php _e( "Google recaptcha secret key", "mideal-faq" );?>
            </th>
            <td>
                <input type="text" name="mideal_faq_setting_recaptcha_key_secret" value="<?php echo esc_attr( get_option( 'mideal_faq_setting_recaptcha_key_secret') ); ?>" />
            </td>
        </tr>

    </table>

</div>
<div id="fronted" class="midealfaqtab">
    <table class="form-table">
         <tr valign="top">
            <th scope="row">
                <?php _e( "Avatar of answer", "mideal-faq" );?> 
           </th>
            <td>
                <img style="display: block;width: 80px; height: 80px;border-radius: 50%;" src="<?php if(get_option("mideal_faq_setting_answer_image")){echo get_option("mideal_faq_setting_answer_image");}else{echo plugins_url( "img/avatar-default.png", __FILE__ );}?>"><br>
                <input type="text" name="mideal_faq_setting_answer_image" value='<?php if(get_option("mideal_faq_setting_answer_image")){echo get_option("mideal_faq_setting_answer_image");}else{echo plugins_url( "img/avatar-default.png", __FILE__ );}?>' />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <?php _e( "Small size avatar", "mideal-faq" );?> 
            </th>
            <td>
                <input type="checkbox" name="mideal_faq_setting_avatar_smallsize" value="1" <?php echo checked( 1, get_option( 'mideal_faq_setting_avatar_smallsize' ), flase ) ;?> />
            </td>
        </tr>


        <tr valign="top">
            <th scope="row">
                <?php _e( "Color question background", "mideal-faq" );?> 
            </th>
            <td>
                <div class="input-group colorpicker-component">
                    <input type="text" name="mideal_faq_setting_question_background" value="<?php echo esc_attr( get_option( 'mideal_faq_setting_question_background',"#eef1f5") ); ?>" class="form-control" />
                    <span class="input-group-addon"><i></i></span>
                </div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <?php _e( "Color question text", "mideal-faq" );?> 
            </th>
            <td>
                <div class="input-group colorpicker-component">
                    <input type="text" name="mideal_faq_setting_question_color_text" value="<?php echo esc_attr( get_option( 'mideal_faq_setting_question_color_text',"#444") ); ?>" class="form-control" />
                    <span class="input-group-addon"><i></i></span>
                </div>
            </td>
        </tr>



        <tr valign="top">
            <th scope="row">
                <?php _e( "Color answer background", "mideal-faq" );?> 
            </th>
            <td>
                <div class="input-group colorpicker-component">
                    <input type="text" name="mideal_faq_setting_answer_background" value="<?php echo esc_attr( get_option( 'mideal_faq_setting_answer_background',"#3cb868") ); ?>" class="form-control" />
                    <span class="input-group-addon"><i></i></span>
                </div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <?php _e( "Color answer text", "mideal-faq" );?> 
            </th>
            <td>
                <div class="input-group colorpicker-component">
                    <input type="text" name="mideal_faq_setting_answer_color_text" value="<?php echo esc_attr( get_option( 'mideal_faq_setting_answer_color_text',"#FFFFFF") ); ?>" class="form-control" />
                    <span class="input-group-addon"><i></i></span>
                </div>
            </td>
        </tr>



        <tr valign="top">
            <th scope="row">
                <?php _e( "Color button background", "mideal-faq" );?> 
            </th>
            <td>
                <div class="input-group colorpicker-component">
                    <input type="text" name="mideal_faq_setting_button_background" value="<?php echo esc_attr( get_option( 'mideal_faq_setting_button_background',"#3cb868") ); ?>" class="form-control" />
                    <span class="input-group-addon"><i></i></span>
                </div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <?php _e( "Color button text", "mideal-faq" );?> 
            </th>
            <td>
                <div class="input-group colorpicker-component">
                    <input type="text" name="mideal_faq_setting_button_color_text" value="<?php echo esc_attr( get_option( 'mideal_faq_setting_button_color_text',"#FFFFFF") ); ?>" class="form-control" />
                    <span class="input-group-addon"><i></i></span>
                </div>
            </td>
        </tr>
          </table>
</div>
<?php submit_button(); ?>
</form>

</div>
<?php }

//------------------------------- New type post --------------------------------------------


add_action( 'init', 'create_mideal_faq' );

function create_mideal_faq() {
    register_post_type( 'mideal_faq',
        array(
            'labels' => array(
            'name'               => __("Question", "mideal-faq"),
            'singular_name'      => __("Question", "mideal-faq"),
            'add_new'            => __("Add question", "mideal-faq"),
            'add_new_item'       => __("Add question", "mideal-faq"),
            'edit_item'          => __("Edit question", "mideal-faq"),
            'new_item'           => __("New question", "mideal-faq"),
            'menu_name'          => __("Question", "mideal-faq"),
            ),
            'public' => true,
            'menu_position' => 15,
            'supports' => array( 'title', 'editor' ),
           // 'menu_icon' => plugins_url( 'img/icon.png', __FILE__ ),
        )
    );
}

// ------------------------ Answer colum ----------------

add_filter( 'manage_mideal_faq_posts_columns', 'set_custom_edit_faq_columns' );
add_action( 'manage_mideal_faq_posts_custom_column' , 'custom_faq_column', 10, 2 );

function set_custom_edit_faq_columns( $columns ) {

    $num = 2;

    $new_columns = array(
        'faq_answer' => __("Answer", "mideal-faq"),
    );
    return array_slice( $columns, 0, 2 ) + $new_columns + array_slice( $columns, $num );
}

function custom_faq_column( $column, $post_id ) {
    switch ( $column ) {

        case 'faq_answer' :
            echo get_post_meta( $post_id, 'mideal_faq_answer', true );
            break;
    }
}

// ------------------------------- add sort colum -------------------------------
add_filter( 'manage_edit-mideal_faq_sortable_columns', 'add_views_sortable_column' );
function add_views_sortable_column( $sortable_columns ){
    $sortable_columns['faq_answer'] = __( "Answer", "mideal-faq" );
    return $sortable_columns;
}

//------------------------------- add custom fields in FAQ--------------------------------------------


add_action( 'add_meta_boxes', 'mideal_faq_add_fields' );

function mideal_faq_add_fields() {
    add_meta_box( 'mideal_faq_fields', __("Answer a question", "mideal-faq"), 'mideal_faq_add_field_func', 'mideal_faq', 'normal', 'high'  );
}


function mideal_faq_add_field_func( $faq_item ){
    $faq_answer = get_post_meta( $faq_item->ID, 'mideal_faq_answer', true );
    $faq_email = get_post_meta( $faq_item->ID, 'mideal_faq_email', true );
    wp_editor( $faq_answer,'faq_add_answer', array( 'textarea_name' => 'mideal_faq_answer' ));
    echo '<br />';
    echo '<br />';
    echo __( "User Email", "mideal-faq" ).': <input type="text" name="mideal_faq_email" value="'.$faq_email.'" size="25" />';
    wp_nonce_field( plugin_basename(__FILE__), 'mideal_faq_noncename' );
}



// update fields after save
add_action( 'save_post', 'mideal_faq_update' );

function mideal_faq_update( $post_id ){

    if ( ! wp_verify_nonce( $_POST['mideal_faq_noncename'], plugin_basename(__FILE__) ) ) return $post_id;; 
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post_id; // если это автосохранение
    if ( 'page' == $_POST['post_type'] && ! current_user_can( 'edit_page', $post_id ) ) {
          return $post_id;
    } elseif( ! current_user_can( 'edit_post', $post_id ) ) {
        return $post_id;
    }
    if ( ! isset( $_POST['mideal_faq_answer'] ) ) return $post_id;
    
    $my_data = sanitize_textarea_field($_POST['mideal_faq_answer']);
    $my_data2 = sanitize_email( $_POST['mideal_faq_email'] );

    update_post_meta( $post_id, 'mideal_faq_answer', $my_data );
    update_post_meta( $post_id, 'mideal_faq_email', $my_data2 );
}