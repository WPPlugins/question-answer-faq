<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
wp_enqueue_style( 'mideal-faq-style', MQA_PLUGIN_URL.'/css/style.css',false,'1.0','all' );
if(get_option( 'mideal_faq_setting_avatar_smallsize' )){
    wp_enqueue_style( 'mideal-faq-avatar_small', MQA_PLUGIN_URL.'/css/small_size.css',false,'1.0','all' );
}else {
    wp_enqueue_style( 'mideal-faq-avatar_big', MQA_PLUGIN_URL.'/css/big_size.css',false,'1.0','all' );
}

if(get_option( 'mideal_faq_setting_recaptcha' )){
    wp_enqueue_script( 'mideal-faq-google_recaptcha', 'https://www.google.com/recaptcha/api.js', array( ),1.0,true );
}


wp_enqueue_style( 'mideal-faq-bootstrap', MQA_PLUGIN_URL.'/css/bootstrap.css',false,'1.0','all' );

// --------------------add script plugin, check jquery-----------------------------
wp_enqueue_script( 'mideal-faq-app', MQA_PLUGIN_URL.'/js/app.js', array( 'jquery' ),1.0,true );



// ---------------------------------Permission --------------------------------------
function mideal_faq_permission( $roles ) {
    $allowed_roles = array( 'editor', 'administrator' );
    if( array_intersect($allowed_roles, $roles ) ) {
        return true;
    } else {
        return false;
    }
}

//------------------------------- Shortcode--------------------------------------------
add_shortcode('mideal-faq', 'mideal_faq_list');

function mideal_faq_list() {
    echo '<h2>'.__("List a question", "mideal-faq").'</h2>';

    $user = wp_get_current_user();
    $user_faq_admin = mideal_faq_permission($user->roles);



    if($user_faq_admin=='true') {
        $post_status = 'any';
    } else {
        $post_status = 'publish';
    }


    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
    $args = array(
        'posts_per_page' => 5,
        'paged' => $paged, 
        'post_type' => 'mideal_faq',
        'orderby' => 'date',
        'order'   => 'DESC',
        'post_status' => $post_status
    );

    $faq_array = new WP_Query( $args );

    echo '<ul id="mideal-faq-list" class="media-list">';
    if ( $faq_array->have_posts() ) {
        foreach ( $faq_array->posts as $key => $post ) {
            echo "<li class='media-list-item";
            if( $post->post_status!="publish" ){
                echo " no-published";
            }
            echo "' data-id='".$post->ID."'>

            <div class='faq-header'><div class='faq-name'>".$post->post_title."</div><div class='faq-date'>".$post->post_date."</div></div>
            <div class='faq-question'>";
            $user_email = get_post_meta( $post->ID, 'mideal_faq_email', true );
            $url_default_avatar = urlencode(plugins_url( 'img/avatar-default.png', __FILE__ ));
            $user_avatar_url = 'https://www.gravatar.com/avatar/'.md5( strtolower( trim( $user_email ) ) ).'?d='.$url_default_avatar.'&s=80';
            echo "<img class='media-object chat-avatar' src='".$user_avatar_url."' alt='avatar'>
            <div class='chat-text' style='border-color:".get_option( 'mideal_faq_setting_question_background',"#eef1f5").";background:".get_option( 'mideal_faq_setting_question_background',"#eef1f5").";color:".get_option( 'mideal_faq_setting_question_color_text',"#444").";'>".nl2br($post->post_content)."</div>
            </div>";
            $answer_text = get_post_meta( $post->ID, 'mideal_faq_answer', true );
            if ($answer_text) {
                echo "<div class='faq-answer'>
                <div class='faq-header'>".esc_attr( get_option( 'mideal_faq_setting_answer_name', __("Answer", "mideal-faq")) )."</div>

                <div class='clearfix'></div>
                <img class='media-object chat-avatar' src='";if(get_option("mideal_faq_setting_answer_image")){echo get_option("mideal_faq_setting_answer_image");}else{echo plugins_url( "img/avatar-default.png", __FILE__ );} echo "' alt='avatar'>
                <div class='chat-text' style='border-color:".get_option( 'mideal_faq_setting_answer_background',"#3cb868").";background:".get_option( 'mideal_faq_setting_answer_background',"#3cb868").";color:".get_option( 'mideal_faq_setting_answer_color_text','#FFFFFF').";'>".nl2br($answer_text)."</div>
                </div>";
            }

            if( 'true' == $user_faq_admin ){
                echo '<div class="mideal-faq-admin-btn">';
                if( $answer_text ) {
                    $text_btn_reply = __( "Edit", "mideal-faq" );
                } else {
                    $text_btn_reply = __( "Reply", "mideal-faq" );
                }
                echo '<a class="btn btn-xs btn-success" href="'.get_edit_post_link($post->ID).'">'.$text_btn_reply.'</a>';
                if($post->post_status == 'publish'){
                    echo '<a class="btn btn-default btn-xs mideal-faq-publish-post" data-status="'.$post->post_status.'" data-id="'.$post->ID.'" href="#">'.__("Unpublish", "mideal-faq").'</a>';
                } else {
                    echo '<a class="btn btn-default btn-xs mideal-faq-publish-post" data-status="'.$post->post_status.'" data-id="'.$post->ID.'" href="#">'.__("Publish", "mideal-faq").'</a>';
                }
                echo '<a href="#" class="btn btn-xs btn-danger mideal-faq-delete-post" data-id="'.$post->ID.'">'.__( "Delete", "mideal-faq" ).'</a>';
                echo '</div>';
            }
            echo "<hr>";
            echo "</li>";
        }
    } else {
        echo "<li class='media'>".__( "No question", "mideal-faq" )."</li>";
    }


    //------------------------ Pagination ----------------------
    $big = 999999999;
    $pages = paginate_links(array(
        'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
        'format' => '',
        'current' => $paged,
        'total' => $faq_array->max_num_pages,
        'type' => 'array',
        'prev_next' => true,
        'prev_text' => '<',
        'next_text' => '>',
            ));
    if( $pages ){
        $pages = str_replace( '/page/1/', '', $pages );
        echo '<ul class="pagination">';
        foreach ( $pages as $i => $page ) {
            if ( $paged == 1 && $i == 0 ) {
                echo "<li class='active'>$page</li>";
            } else {
                if ($paged != 1 && $paged == $i) {
                    echo "<li class='active'>$page</li>";
                } else {
                    echo "<li>$page</li>";
                }
            }
        }
        echo '</ul>';
    }
    wp_reset_postdata();
    echo "</ul>";
}


// ------------------- add new question----------------

add_shortcode( 'mideal-faq-form', 'mideal_faq_form' );

function mideal_faq_form() {

    echo '<h2>'.__( 'Add question', 'mideal-faq' ).'</h2>
        <form id="form-mideal-faq">

        <div class="form-group">
        <label>'.__("Name", "mideal-faq").'<span class="red">*</span>:</label>
        <input type="text" name="mideal_faq_name" class="form-control" placeholder="'.__("Name", "mideal-faq").'">
        </div>
        <div class="form-group">
        <label>'.__("E-mail", "mideal-faq").'<span class="red">*</span>:</label>
        <input type="text" name="mideal_faq_email" class="form-control" placeholder="'.__("Your E-mail", "mideal-faq").'">
        </div>
        <div class="form-group">
        <label>'.__("Question", "mideal-faq").'<span class="red">*</span>:</label>
        <textarea name="mideal_faq_question" class="form-control" placeholder="'.__("Your question", "mideal-faq").'"></textarea>
        </div>';
    
    if(get_option( 'mideal_faq_setting_recaptcha' )){
        echo '<div class="form-group">
            <div class="g-recaptcha" data-sitekey="'.get_option( 'mideal_faq_setting_recaptcha_key').'"></div>
            </div>';
    }
    
    echo '<div class="form-group sent-group">
        <div class="message-error-sent"></div>
        <input class="btn btn-primary sent-mideal-faq" style="color:'.get_option( 'mideal_faq_setting_button_color_text',"#FFFFFF").';background:'.get_option( 'mideal_faq_setting_button_background',"#3cb868").';" type="submit" value="'.__("Ask a question", "mideal-faq").'">
        </div>

        </form>';

}


// ------------------- Add post ajax----------------
if( defined('DOING_AJAX') && DOING_AJAX ) {
    add_action('wp_ajax_mideal_faq_add', 'mideal_faq_add_callback');
    add_action('wp_ajax_nopriv_mideal_faq_add', 'mideal_faq_add_callback');
}

function mideal_faq_add_callback() {
    $nonce = $_POST['nonce'];

    if ( ! wp_verify_nonce( $nonce, 'myajax-nonce' ) ){
        die ( 'Stop!');
    }

    if(get_option( 'mideal_faq_setting_recaptcha' )){
        if (!$_POST["g-recaptcha-response"]) {
            die ( 'norecaptcha');
        }

        $secret = get_option( 'mideal_faq_setting_recaptcha_key_secret');
        $response=$_POST["g-recaptcha-response"];
        $verify=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}");
        $captcha_success=json_decode($verify);

        if ($captcha_success->success==false) {
            die ( 'norecaptcha');
        }
    }

    $post_data = array(
        'post_title'    => sanitize_text_field( $_POST['mideal_faq_name'] ),
        'post_content'  => sanitize_textarea_field($_POST['mideal_faq_question']),
        'post_status'   => 'pending',
        'post_type'  => 'mideal_faq',
    );

    $post_id = wp_insert_post( $post_data );
    if( $post_id ){
        if( is_email( $_POST['mideal_faq_email'] ) ){
            $user_email = sanitize_email( $_POST['mideal_faq_email']);
            update_post_meta( $post_id, 'mideal_faq_email', $user_email );
        }


        //sent notification on email
        $sendto   = get_option('mideal_faq_setting_email',get_option('admin_email'));
        $subject  = __("New question on site", "mideal-faq");

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html;charset=utf-8 \r\n";
        if(get_option('mideal_faq_setting_email2')){
            $headers .= "Cc: ".get_option('mideal_faq_setting_email2')." \r\n";
        }

        $username  = sanitize_text_field($_POST['mideal_faq_name']);
        $usermail = sanitize_email($_POST['mideal_faq_email']);
        $faq_content  = sanitize_textarea_field($_POST['mideal_faq_question']);
        $msg  = "<html><body style='font-family:Arial,sans-serif;'>";
        $msg .= "<h2 style='font-weight:bold;border-bottom:1px dotted #ccc;'>".__('New question on site', 'mideal-faq').":</h2>\r\n";
        $msg .= "<p><strong>".__('Name', 'mideal-faq').":</strong> ".$username."</p>\r\n";
        $msg .= "<p><strong>".__('E-mail', 'mideal-faq').":</strong> ".$usermail."</p>\r\n";
        $msg .= "<p><strong>".__('Question', 'mideal-faq').":</strong> ".nl2br($faq_content)."</p>\r\n";
        $msg .= "<p><strong><a href='".get_edit_post_link($post_id)."'>".__('Reply', 'mideal-faq')."</a></strong></p>\r\n";
        $msg .= "</body></html>";

        wp_mail( $sendto, $subject, $msg, $headers );

    }
    wp_die();
}


// ------------------- Delete post ajax----------------

if( defined('DOING_AJAX') && DOING_AJAX ) {
    add_action('wp_ajax_mideal_faq_delete', 'mideal_faq_delete_callback');
}

function mideal_faq_delete_callback() {
    $nonce = $_POST['nonce'];
    $user = wp_get_current_user();
    $user_faq_admin = mideal_faq_permission($user->roles);

    if ( ! wp_verify_nonce( $nonce, 'myajax-nonce' ) ){
        die ( 'Stop!');
    }

     if ( $user_faq_admin!='true' ) {
         die ('Stop!');
     }

    wp_delete_post($_POST['ID'] );
    wp_die();
}

// ------------------- Publish post ajax----------------

if( defined('DOING_AJAX') && DOING_AJAX ) {
    add_action('wp_ajax_mideal_faq_publish', 'mideal_faq_publish_callback');
}

function mideal_faq_publish_callback() {
    $nonce = $_POST['nonce'];
    $user = wp_get_current_user();
    $user_faq_admin = mideal_faq_permission($user->roles);

    if ( ! wp_verify_nonce( $nonce, 'myajax-nonce' ) ){
        die ( 'Stop!');
    }

    if ( $user_faq_admin!='true' ) {
        die ('Stop!');
    }


    if( $_POST['post_status'] != 'publish'){
        if(intval($_POST['ID'])){
            wp_publish_post( $_POST['ID'] );
        }
    } else {
        if(intval($_POST['ID'])){
            $post_data = array(
                'ID'    => $_POST['ID'],
                'post_status'   => 'pending',
                'post_type'  => 'mideal_faq'
            );
            wp_update_post( $post_data );
        }
    }

    wp_die();
}