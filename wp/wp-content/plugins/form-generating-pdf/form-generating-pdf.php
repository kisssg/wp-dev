<?php


/**
 * Plugin Name: Form Generating PDF - Wordpress Plugin
 * Plugin URI:  https://codecanyon.net/item/form-generating-pdf-wordpress-plugin/20403948
 * Description: This plugin will send an PDF attachment filled with form user data, via email after his registration on a form, it's easy and manageable in simple three steps, it has the most unique features, that cannot be found on any existing plugin on the market.
 * Version: 3.5.8
 * Author: ClimaxWeb
 * Author URI:  http://www.climaxwebmaxell.com/
 * Tested up to: 3.5.8
 **/






// exit if plugin accessed directly
if( !defined( 'ABSPATH' ) ) exit;


if (!function_exists('fgpdf_add_admin_scripts')){

    function fgpdf_add_admin_scripts(){

    wp_register_style( 'custom_wp_admin_css', plugins_url( '/css/style.css', __FILE__ ));
    wp_enqueue_style( 'custom_wp_admin_css' );

    wp_register_script( 'pdf-worker-script', plugins_url( '/js/pdf.worker.js', __FILE__ ), '',true );
    wp_enqueue_script( 'pdf-worker-script' );
    
    wp_register_script( 'pdf-script', plugins_url( '/js/pdf.js', __FILE__ ), '',true );
    wp_enqueue_script( 'pdf-script' );

    wp_register_script( 'custom-script', plugins_url( '/js/custom.js', __FILE__ ), array('jquery'), '',true );
    wp_localize_script('custom-script', 'myAjaxLink', array(
        "ajax_url" => admin_url("admin-ajax.php")
        ));
    wp_enqueue_script( 'custom-script' );
    }

}





if (!function_exists('fgpdf_add_main_scripts')){

    function fgpdf_add_main_scripts(){
        wp_register_script('jquery-validate-min', plugins_url('/js/jquery.validate.min.js', __FILE__ ), array( 'jquery' ) );
        wp_enqueue_script( 'jquery-validate-min' );
        wp_register_script( 'fgpdf-main-script', plugins_url( '/js/main.js', __FILE__ ), array('jquery'), '',true );

        wp_localize_script('fgpdf-main-script', 'users_obj', array(
            "ajax_url" => admin_url("admin-ajax.php")
            ));

        wp_enqueue_script( 'fgpdf-main-script' );
    }

}




add_action( 'admin_enqueue_scripts', 'fgpdf_add_admin_scripts' );
add_action( 'wp_enqueue_scripts', 'fgpdf_add_main_scripts' );
add_action( 'admin_menu', 'fgpdf_admin_menus');



if (!function_exists('fgpdf_admin_menus')){

    function fgpdf_admin_menus() {
        /* main menu */
        $top_menu_item = 'fgpdf_form_pdfs_page';
        add_menu_page( '', 'Form Generating PDF', 'manage_options', $top_menu_item, $top_menu_item, plugins_url("images/check-form.png", __FILE__) );
        add_submenu_page( $top_menu_item, '', 'Form PDF Projects', 'manage_options', $top_menu_item, $top_menu_item );
        add_submenu_page( $top_menu_item, '', 'Define Page & Attachment', 'manage_options', 'fgpdf_options_admin_page', 'fgpdf_options_admin_page' );
        $my_plugins_page = add_submenu_page( $top_menu_item, '', 'Input Properties', 'manage_options', 'fgpdf_input_properties_settings', 'fgpdf_input_properties_settings' );
        add_submenu_page( $top_menu_item, '', 'Manage Mail Options', 'manage_options', 'fgpdf_manage_mail', 'fgpdf_manage_mail' );
    }

}





if (!function_exists('fgpdf_get_input_types')){

    function fgpdf_get_input_types($current_key){
        try{
        include_once(plugin_dir_path( __FILE__ )."libs/simplehtmldom/simple_html_dom.php");
        $selected_form_element = fgpdf_form_element();
        $fgpdf_page_att_options_array = get_option('fgpdf_page_att_options');
        $fgpdf_page_att_options = $fgpdf_page_att_options_array[$current_key];
        $pageId = (array_key_exists('fgpdf_form_page_id', $fgpdf_page_att_options)) ? $fgpdf_page_att_options['fgpdf_form_page_id'] : '';
        $pageUrl = get_permalink($pageId);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_URL, $pageUrl);
        curl_setopt($curl, CURLOPT_REFERER, $pageUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $str = curl_exec($curl);
        curl_close($curl);
        $html_base = new simple_html_dom();
        $html_base->load($str);

        $input_types = array();
        $input_types_test = array();
        if($html_base != false){
            if($html_base->find("form") != false){
                foreach($html_base->find("form") as $form){
                   foreach($form->find('input, textarea, button, select, canvas') as $input) 
                   {
                        if($input->getAttribute('type') == 'hidden' || $input->getAttribute('type') == 'submit'){
                            continue;
                        }
                        elseif($input->getAttribute('type') == 'checkbox'){
                            array_push($input_types, array(
                                'type' => 'checkbox',
                                'name' => $input->getAttribute('name'),
                                'text_value' => $input->innertext,
                                'value' => $input->getAttribute('value'),
                                'id' => $input->getAttribute('id'),
                                'class' => $input->getAttribute('class'),
                            ));
                            array_push($input_types_test, 'checkbox');
                        }
                        elseif($input->getAttribute('type') == 'radio'){
                            array_push($input_types, array(
                                'type' => 'radio',
                                'name' => $input->getAttribute('name'),
                                'text_value' => $input->innertext,
                                'value' => $input->getAttribute('value'),
                                'id' => $input->getAttribute('id'),
                                'class' => $input->getAttribute('class'),
                            ));
                            array_push($input_types_test, 'radio');
                        }
                        elseif($input->tag == 'select'){
                            $arr = array(
                                'type' => 'select',
                                'inputValue' => array(),
                                'text_value' => array(),
                                'name' => $input->getAttribute('name'),
                                'id' => $input->getAttribute('id'),
                                'class' => $input->getAttribute('class'),
                            );
                            foreach ($input->find('option') as $option) {
                                array_push($arr['inputValue'], $option->getAttribute('value'));
                                array_push($arr['text_value'], $option->innertext);
                            }
                            array_push($input_types,$arr);
                            unset($arr);
                            array_push($input_types_test, 'select');
                            
                        }
                        elseif($input->tag == 'textarea'){
                            array_push($input_types, array(
                                'type' => 'textarea',
                                'name' => $input->getAttribute('name'),
                                'id' => $input->getAttribute('id'),
                                'class' => $input->getAttribute('class')
                            ));
                            array_push($input_types_test, 'textarea');
                        }
                        elseif($input->tag == 'canvas'){
                            array_push($input_types, array(
                                'type' => 'signature',
                                'id' => $input->getAttribute('id')
                            ));
                            array_push($input_types_test, 'textarea');
                        }
                        elseif(in_array($input->getAttribute('type'), array(0 => 'text', 1 => 'email', 2 => 'password', 3 => 'date', 4 => 'number', 5 => 'tel', 6 => 'time', 7 => 'url', 8 => 'file'))){
                            array_push($input_types, array(
                                'type' => $input->getAttribute('type'),
                                'name' => $input->getAttribute('name'),
                                'id' => $input->getAttribute('id'),
                                'class' => $input->getAttribute('class')
                            ));
                            array_push($input_types_test, $input->getAttribute('type'));
                        }
                        else{
                            continue;
                        }
                        
                   }
                   
                   if(count(array_intersect(array_unique($input_types_test), $selected_form_element)) == count($selected_form_element)){
                        break;
                    }else{
                        unset($input_types);
                        $input_types = array();
                        continue;
                    }
                   
                }
            }
        }
        $html_base->clear(); 
        unset($html_base);
        if(count(array_intersect($selected_form_element, $input_types_test)) == count($selected_form_element))
            return $input_types;

        return array();
    } catch (Exception $e) {
        return array();
    }


    }

}





if (!function_exists('fgpdf_form_element')){

    function fgpdf_form_element(){

        $fgpdf_page_att_options_array = get_option('fgpdf_page_att_options');
        $current_key = get_option('fgpdf_pdf_project_options');
        $fgpdf_page_att_options = $fgpdf_page_att_options_array[$current_key['current_project']];

        $fgpdf_form_checks = $fgpdf_page_att_options['fgpdf_form_checks'];
        $form_elements = array(
            'fgpdf_text' => 'text',
            'fgpdf_email' => 'email',
            'fgpdf_password' => 'password',
            'fgpdf_select' => 'select',
            'fgpdf_textarea' => 'textarea',
            'fgpdf_checkbox' => 'checkbox',
            'fgpdf_radio'=> 'radio',
            'fgpdf_tel' => 'tel',
            'fgpdf_date' => 'date',
            'fgpdf_number' => 'number',
            'fgpdf_file' => 'file',
            'fgpdf_signature' => 'signature'
            );

        $check_form_array = array();
        foreach ($form_elements as $key => $element) {
            if($fgpdf_form_checks[$key] == 1){
                array_push($check_form_array, $element);
            }
        }
        return $check_form_array;
    }

}


if(!function_exists('fgpdf_form_pdfs_page')){
    function fgpdf_form_pdfs_page(){

        if (isset($_POST['add_form_project']) && check_admin_referer(plugin_basename(__FILE__), 'add_form_project_nonce_name')) {
            //$fgpdf_page_att_options = get_option('fgpdf_page_att_options')

            $fgpdf_page_att_options = get_option('fgpdf_page_att_options');

            $fgpdf_page_att_options[count($fgpdf_page_att_options)] = array(
                'fgpdf_form_page_id' => '',
                'fgpdf_select_attachment_id' => '',
                'fgpdf_email_subject' => 'This is your subject',
                'fgpdf_email_body' => '<p><br>Thank you for registering</p>',
                'fgpdf_admin_email' => '',
                'fgpdf_send_to' => 'users',
                'fgpdf_attachment_name' => '',
                'fgpdf_form_checks' => array(
                    'fgpdf_text' => 0,
                    'fgpdf_email' => 0,
                    'fgpdf_password' => 0,
                    'fgpdf_select' => 0,
                    'fgpdf_textarea' => 0,
                    'fgpdf_checkbox' => 0,
                    'fgpdf_radio' => 0,
                    'fgpdf_tel' => 0,
                    'fgpdf_date' => 0,
                    'fgpdf_number' => 0,
                    'fgpdf_file' => 0,
                    'fgpdf_signature' => 0
                    ),
                'fgpdf_alternative_method' => 0,
                'fgpdf_form_types' => array()
            );
            update_option('fgpdf_page_att_options', $fgpdf_page_att_options);

        }


    ?>



        <h2>Form PDF Projects</h2>
        

        <form action="" method="post">
            <table class="widefat">
            <thead>
                <tr>
                    <th>Page Name</th>
                    <th>Attachment Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $fgpdf_page_att_options = get_option('fgpdf_page_att_options');
            if(!$fgpdf_page_att_options){
                echo('<tr>
                    <td class="row-title">There is no pdf project yet</td>
                    <td></td>
                    <td></td>
                    </tr>');
            }else{
            foreach ($fgpdf_page_att_options as $key => $project) {
                if($project['fgpdf_form_page_id']){
                ?>

                <tr>
                    <td class='row-title'><?= get_the_title( $project['fgpdf_form_page_id'] )?></td>
                    <td><?= fgpdf_slash_attachment_escape($project['fgpdf_select_attachment_id'])?></td>
                    <td><input type="button" class="editProject button-primary" value="Edit" key="<?=$key?>">
                    <button type="button" class="deleteProject button-primary" key="<?=$key?>">Delete</button>
                    </td>
                </tr>
                <?php
                    }else{
                        echo('<tr>
                            <td class="row-title">You have created a new project Click here to configure it <input style="margin-left:5px;" type="button" class="editProject button-primary" value="Configure" key="'.$key.'"></td>
                            <td></td>
                            <td></td>
                            </tr>');
                    }
                }
            }

            ?>
            </tbody>
            </table>
            <!-- Submit -->
            <p class="submit">
                <input type="submit" id="add_form_project" class="button-primary" value="<?php _e('Add Form Project', 'form-generating-pdf') ?>" />
                <input type="hidden" name="add_form_project" value="submit" />
                <?php wp_nonce_field(plugin_basename(__FILE__), 'add_form_project_nonce_name'); ?>
            </p>
        </form>
        <?php
    }
}


add_action( 'wp_ajax_fgpdf_edit_form_project', 'fgpdf_edit_form_project' );

if(!function_exists('fgpdf_edit_form_project')){

    function fgpdf_edit_form_project(){
        if(isset($_POST['form_project_id'])){
            update_option('fgpdf_pdf_project_options', array('current_project' => $_POST['form_project_id']));
        }
        wp_die();
    }

}


add_action( 'wp_ajax_fgpdf_delete_form_project', 'fgpdf_delete_form_project' );

if(!function_exists('fgpdf_delete_form_project')){

    function fgpdf_delete_form_project(){
        $fgpdf_page_att_options = get_option('fgpdf_page_att_options');
        $fgpdf_input_properties_options = get_option('fgpdf_input_properties_options');
        $idKey = ( isset($_POST['form_project_id']) ) ? $_POST['form_project_id'] : '';
        //if(array_key_exists($idKey, $fgpdf_page_att_options) && array_key_exists($idKey, $fgpdf_input_properties_options)){
            array_splice($fgpdf_page_att_options, $idKey, 1);
            array_splice($fgpdf_input_properties_options, $idKey, 1);
        //}
        update_option('fgpdf_page_att_options', $fgpdf_page_att_options);
        update_option('fgpdf_input_properties_options', $fgpdf_input_properties_options);
        wp_die();
    }

}





if(!function_exists('fgpdf_input_properties_settings')){

    function fgpdf_input_properties_settings(){
        
        $fgpdf_page_att_options_array = get_option('fgpdf_page_att_options');
        
        if($fgpdf_page_att_options_array !== array()){

        $current_key = get_option('fgpdf_pdf_project_options');
        $fgpdf_page_att_options = $fgpdf_page_att_options_array[$current_key['current_project']];
        $web_scraping_form = fgpdf_get_input_types($current_key['current_project']);
        $input_types = ($fgpdf_page_att_options['fgpdf_alternative_method']) ? $fgpdf_page_att_options['fgpdf_form_types'] : $web_scraping_form ;
        $fgpdf_input_properties_options_array = get_option('fgpdf_input_properties_options');

        if(!array_key_exists($current_key['current_project'], $fgpdf_input_properties_options_array))
            $fgpdf_input_properties_options_array[$current_key['current_project']] = array() ;

        $fgpdf_input_properties_options = $fgpdf_input_properties_options_array[$current_key['current_project']];

        if (isset($_POST['input_properties_form_submit']) && check_admin_referer(plugin_basename(__FILE__), 'input_properties_nonce_name')) {
            /* Update settings */
            for($i=0; $i<count($input_types); $i++){
                switch ($input_types[$i]['type']) {
                    case 'text':
                    case 'email':
                    case 'password':
                    case 'textarea':
                    case 'date':
                    case 'number':
                    case 'tel':
                    case 'time':
                    case 'url':
                        $fgpdf_input_properties_options["x".$i] = isset($_POST["x".$i]) ? $_POST["x".$i] : '';
                        $fgpdf_input_properties_options["y".$i] = isset($_POST["y".$i]) ? $_POST["y".$i] : '';
                        $fgpdf_input_properties_options["font-family".$i] = isset($_POST["font-family".$i]) ? $_POST["font-family".$i] : 'Arial';
                        $fgpdf_input_properties_options["color".$i] = isset($_POST["color".$i]) ? $_POST["color".$i] : '#000000';
                        $fgpdf_input_properties_options["pageN".$i] = isset($_POST["pageN".$i]) ? $_POST["pageN".$i] : 1;
                        $fgpdf_input_properties_options["font-size".$i] = isset($_POST["font-size".$i]) ? $_POST["font-size".$i] : 11;
                        if($input_types[$i]['type'] == 'textarea'){
                            $fgpdf_input_properties_options["width".$i] = isset($_POST["width".$i]) ? $_POST["width".$i] : '';
                        }
                        $fgpdf_input_properties_options["font-weight".$i.'B'] = isset($_POST["font-weight".$i.'B']) ? $_POST["font-weight".$i.'B'] : '';
                        $fgpdf_input_properties_options["font-weight".$i.'I'] = isset($_POST["font-weight".$i.'I']) ? $_POST["font-weight".$i.'I'] : '';
                        $fgpdf_input_properties_options["font-weight".$i.'U'] = isset($_POST["font-weight".$i.'U']) ? $_POST["font-weight".$i.'U'] : '';
                        $fgpdf_input_properties_options["text-alignment".$i] = isset($_POST["text-alignment".$i]) ? $_POST["text-alignment".$i] : 'J';
                        break;
                    case 'checkbox':
                    case 'radio':
                        $fgpdf_input_properties_options["x".$i] = isset($_POST["x".$i]) ? $_POST["x".$i] : '';
                        $fgpdf_input_properties_options["y".$i] = isset($_POST["y".$i]) ? $_POST["y".$i] : '';
                        $fgpdf_input_properties_options["pageN".$i] = isset($_POST["pageN".$i]) ? $_POST["pageN".$i] : 1;
                        break;
                    case 'select':
                        foreach ($input_types[$i]['inputValue'] as $j => $value){
                                $fgpdf_input_properties_options["x".$i.$j] = isset($_POST["x".$i.$j]) ? $_POST["x".$i.$j] : '';
                                $fgpdf_input_properties_options["y".$i.$j] = isset($_POST["y".$i.$j]) ? $_POST["y".$i.$j] : '';
                                $fgpdf_input_properties_options["pageN".$i] = isset($_POST["pageN".$i]) ? $_POST["pageN".$i] : 1;
                        }
                        break;
                    case 'file':
                    case 'signature':
                        $fgpdf_input_properties_options["x".$i] = isset($_POST["x".$i]) ? $_POST["x".$i] : '';
                        $fgpdf_input_properties_options["y".$i] = isset($_POST["y".$i]) ? $_POST["y".$i] : '';
                        $fgpdf_input_properties_options["larg".$i] = isset($_POST["larg".$i]) ? $_POST["larg".$i] : '';
                        $fgpdf_input_properties_options["haut".$i] = isset($_POST["haut".$i]) ? $_POST["haut".$i] : '';
                        $fgpdf_input_properties_options["pageN".$i] = isset($_POST["pageN".$i]) ? $_POST["pageN".$i] : 1;
                        break;
                }
            }
            $fgpdf_input_properties_options["email_pos"] = isset($_POST["email_pos"]) ? $_POST["email_pos"] : 0;
            $fgpdf_input_properties_options["total-pages"] = isset($_POST["total-pages"]) ? $_POST["total-pages"] : 1;

            $fgpdf_input_properties_options_array[$current_key['current_project']] = $fgpdf_input_properties_options;
            update_option('fgpdf_input_properties_options', $fgpdf_input_properties_options_array);
        }

        if($input_types !== array()){
            ?>
            <h2>Input Properties</h2>
            <div class="notice notice-info is-dismissible"><p>Pease fill in the inputs properties of your form on the page <strong><?= get_the_title($fgpdf_page_att_options['fgpdf_form_page_id'])?> </strong> and define their position on the pdf document, then specify which input contains the email</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>

            <div class="notice notice-info is-dismissible"><p>Position of the text and the checked mark will be printed as follow :<img src='<?=plugins_url("images/ind1.png", __FILE__)?>' style="width:85px;height:42px"/>   <img src='<?= plugins_url("images/ind2.png", __FILE__)?>' style="width:85px;height:42px"/></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>

            <div class="notice notice-info is-dismissible"><p>Check out the tutorial <a href="https://youtu.be/K_sPgAPZ1u4">here</a> if you hadn't yet.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>

            <div class="notice notice-warning"><p>Notice: If you don't want your element to appear on the pdf document, do not specify any position for the element by leaving it blank</p></div>
            <form action="" method="post">
            <div class="wrap">

            <table class="widefat">
            <thead>
                <tr>
                    <th width="500px">Options</th>
                    <th width="600px">Pdf Canvas</th>
                </tr>
            </thead>
            <tr>
            <td>
            <table class="scroll">
                <thead>
                    <tr>
                        <th>Specify Email Input</th>
                        <th>Label/input type</th>
                        <th>Properties</th>
                    </tr>
                </thead>
            <tbody> 
            <?php
            $email_pos = (array_key_exists("email_pos", $fgpdf_input_properties_options)) ? $fgpdf_input_properties_options["email_pos"] : 0;
            $total_pages = (array_key_exists("total-pages", $fgpdf_input_properties_options)) ? $fgpdf_input_properties_options["total-pages"] : 1;
            foreach ($input_types as $key => $input) {
                switch($input['type']){
                    case 'text':
                    case 'email':
                    case 'password':
                    case 'textarea':
                    case 'date':
                    case 'number':
                    case 'tel':
                    case 'time':
                    case 'url': 
                    $hor_pos = (array_key_exists('x'.$key, $fgpdf_input_properties_options)) ? $fgpdf_input_properties_options['x'.$key] : '';
                    $ver_pos = (array_key_exists('y'.$key, $fgpdf_input_properties_options)) ? $fgpdf_input_properties_options['y'.$key] : '';
                    $page_number = (array_key_exists('pageN'.$key, $fgpdf_input_properties_options)) ? $fgpdf_input_properties_options['pageN'.$key] : '';
                    $font_family = (array_key_exists('font-family'.$key, $fgpdf_input_properties_options)) ? $fgpdf_input_properties_options['font-family'.$key] : 'Arial';
                    $font_size = (array_key_exists('font-size'.$key, $fgpdf_input_properties_options)) ? $fgpdf_input_properties_options['font-size'.$key] : 11;
                    $color = (array_key_exists("color".$key, $fgpdf_input_properties_options)) ? $fgpdf_input_properties_options["color".$key] : '#000000';
                    $bold = (array_key_exists('font-weight'.$key.'B', $fgpdf_input_properties_options)) ? $fgpdf_input_properties_options['font-weight'.$key.'B'] : '';
                    $italic = (array_key_exists('font-weight'.$key.'I', $fgpdf_input_properties_options)) ? $fgpdf_input_properties_options['font-weight'.$key.'I'] : '';
                    $underline = (array_key_exists('font-weight'.$key.'U', $fgpdf_input_properties_options)) ? $fgpdf_input_properties_options['font-weight'.$key.'I'] : '';
                    $text_alignment = (array_key_exists('text-alignment'.$key, $fgpdf_input_properties_options)) ? $fgpdf_input_properties_options['text-alignment'.$key] : 'J';

            ?> 
                        <tr>
                        <td><input type="radio" id="email_pos" name="email_pos" value="<?= $key ?>" <?php checked( $email_pos, $key, true )?> ></td>
                        <td class="row-title"><?php echo (array_key_exists('inputValue', $input)  && $input['inputValue'][0] !='') ? $input['inputValue'][0] : 'Input #'.($key+1);  ?>  Type: <?= $input['type'] ?> </td>
                        <td>
                             <input id="<?= 'x'.$key.'val' ?>" name="<?= 'x'.$key ?>" type="text"  value="<?= $hor_pos ?>" style="width: 60px;">
                             x
                             <input id="<?= 'y'.$key.'val' ?>" name="<?= 'y'.$key ?>" type="text"  value="<?= $ver_pos  ?>" style="width: 60px;">
                             y
                             <button type="button" key="<?= $key ?>" class="positionElement button button-primary" style="margin-left: 17px;">Position</button>
                             <br>
                            <?php
                            if($input['type'] === 'textarea'){
                                $width = (array_key_exists('width'.$key, $fgpdf_input_properties_options)) ? $fgpdf_input_properties_options['width'.$key] : '';
                            ?>
                                <input id="<?= 'width'.$key.'val' ?>" name="<?= 'width'.$key ?>" type="text" value="<?= $width ?>" style="width: 135px;">
                                px
                                <button type="button" key="<?= $key ?>" class="widthElement button button-primary" style="margin-left: 14px;">Width</button>
                                <br>
                            <?php } ?>
                        <div>

                            <?= fgpdf_get_font_select('font-family'.$key,  $font_family) ?>
                            <?= fgpdf_get_font_size_select('font-size'.$key, $font_size) ?>
                             <div id="font-style-div">
                             <input type="color" name="<?= 'color'.$key ?>" value="<?= $color ?>">
                             <label>
                                <span class="font-style-bold">
                                  <input type="checkbox" name="<?= 'font-weight'.$key.'B' ?>" value="B" <?= checked( 'B', $bold, true ) ?> >
                                  <img class="bold-unchecked" src="<?= plugins_url("images/Bold.png", __FILE__) ?>"  height="36" width="36">
                                  <img class="bold-checked" src="<?= plugins_url("images/Bold-checked.png", __FILE__) ?>"  height="36" width="36">
                                </span>
                            </label>
                            <label>
                                <span class="font-style-italic">
                                  <input type="checkbox" name="<?= 'font-weight'.$key.'I' ?>" value="I" <?= checked('I', $italic, true )?> >
                                  <img class="italic-unchecked" src="<?= plugins_url("images/Italic.png", __FILE__) ?>"  height="36" width="36">
                                  <img class="italic-checked" src="<?= plugins_url("images/Italic-checked.png", __FILE__) ?>"  height="36" width="36">
                                </span>
                            </label>
                            <label>
                                <span class="font-style-underline">
                                  <input type="checkbox" name="<?= 'font-weight'.$key.'U' ?>" value="U" <?= checked( 'U', $underline, true ) ?> >
                                  <img class="underline-unchecked" src="<?= plugins_url("images/Underline.png", __FILE__) ?>"  height="36" width="36">
                                  <img class="underline-checked" src="<?= plugins_url("images/Underline-checked.png", __FILE__) ?>"  height="36" width="36">
                                </span>
                             </label>


                            </div>
                             <input id="<?= 'page'.$key.'val' ?>" name="<?= 'pageN'.$key ?>" type="hidden" value="<?= $page_number ?>">
                             <div id="text-alignment-div">
                             <label>
                                <span class="text-alignment-left">
                                  <input type="radio" name="<?= 'text-alignment'.$key ?>" value="L" <?php checked( $text_alignment,'L', true ) ?> >
                                  <img class="left-unchecked" src="<?= plugins_url("images/Left.png", __FILE__) ?>"  height="36" width="36">
                                  <img class="left-checked" src="<?= plugins_url("images/Left-checked.png", __FILE__) ?>"  height="36" width="36">
                                </span>
                            </label>
                            <label>
                                <span class="text-alignment-center">
                                  <input type="radio" name="<?= 'text-alignment'.$key ?>" value="C" <?php checked( $text_alignment,'C', true ) ?> >
                                  <img class="center-unchecked" src="<?= plugins_url("images/Center.png", __FILE__) ?>"  height="36" width="36">
                                  <img class="center-checked" src="<?= plugins_url("images/Center-checked.png", __FILE__) ?>"  height="36" width="36">
                                </span>
                            </label>
                            <label>
                                <span class="text-alignment-right">
                                  <input type="radio" name="<?= 'text-alignment'.$key ?>" value="R" <?php checked( $text_alignment,'R', true ) ?> >
                                  <img class="right-unchecked" src="<?= plugins_url("images/Right.png", __FILE__) ?>"  height="36" width="36">
                                  <img class="right-checked" src="<?= plugins_url("images/Right-checked.png", __FILE__) ?>"  height="36" width="36">
                                </span>
                             </label>
                             <label>
                                <span class="text-alignment-justify">
                                  <input type="radio" name="<?= 'text-alignment'.$key ?>" value="J" <?php checked( $text_alignment,'J', true ) ?> >
                                  <img class="justify-unchecked" src="<?= plugins_url("images/Justify.png", __FILE__) ?>"  height="36" width="36">
                                  <img class="justify-checked" src="<?= plugins_url("images/Justify-checked.png", __FILE__) ?>"  height="36" width="36">
                                </span>
                             </label>
                            </div>
                        <?php
                        $type = ($input['type'])       ? ' <br>Type : '.$input['type']      : '';
                        if(!$fgpdf_page_att_options['fgpdf_alternative_method']){
                        
                        $name = ($input['name'])       ? ' <br>Name : '.$input['name']      : '';
                        $id = ($input['id'])           ? " <br>Id : ".$input['id']          : '';
                        $class = ($input['class'])     ? " <br>Class : ".$input['class']    : '';

                        $description = "This is an input element with :".$type.$name.$id.$class."<br> Please specify its properties.";
                    }else{
                        $name = ($input['inputValue'][0] !='')       ? ' <br>Name : '.$input['inputValue'][0]      : '';
                        $description = "This is an input element with :".$type.$name."<br> Please specify its properties.";
                    }
                        echo('  
                        <p class="description">'.$description.'</p>
                        </td>
                        </tr>
                        ');
                    
                        break;
                    case 'radio':
                    case 'checkbox':
                        $hor_pos = (array_key_exists('x'.$key, $fgpdf_input_properties_options)) ? $fgpdf_input_properties_options['x'.$key] : '';
                        $ver_pos = (array_key_exists('y'.$key, $fgpdf_input_properties_options)) ? $fgpdf_input_properties_options['y'.$key] : '';
                        $page_number = (array_key_exists('pageN'.$key, $fgpdf_input_properties_options)) ? $fgpdf_input_properties_options['pageN'.$key] : '';
                        ?> 
                        <tr>
                            <td><input id="email_pos" type="radio" name="email_pos" value="<?= $key ?>" <?php checked( $email_pos, $key, true ) ?> ?></td>
                            <td class="row-title"><?php echo (array_key_exists('inputValue', $input) && $input['inputValue'][0] !='') ? $input['inputValue'][0] : 'Input #'.($key+1);  ?> Type :<?= $input['type'] ?> </td>
                            <td>
       
                        <input id="<?= 'x'.$key.'val' ?>" name="<?= 'x'.$key ?>" type="text"  value="<?= $hor_pos ?>" style="width: 60px;">
                        x
                        <input id="<?= 'y'.$key.'val' ?>" name="<?= 'y'.$key ?>" type="text"  value="<?= $ver_pos ?>" style="width: 60px;" >
                        y
                        <button type="button" key="<?= $key ?>" class="positionElement button button-primary" style="margin-left: 17px;">Position</button>
                        <br>
                        <input id="<?= 'page'.$key.'val' ?>" name="<?= 'pageN'.$key ?>" type="hidden" value="<?= $page_number ?>">
                        

                        <?php
                        $type = ($input['type'])             ? ' <br>Type : '.$input['type'] : '';
                        if(!$fgpdf_page_att_options['fgpdf_alternative_method']){
                        $name = ($input['name'])             ? ' <br>Name : '.$input['name'] : '';
                        $text_value = ($input['text_value']) ? ' <br>Outer Value : '.$input['text_value'] : '';
                        $value = ($input['value'])           ? ' <br>Input Value : '.$input['value'] : '';
                        $id = ($input['id'])                 ? ' <br>Id : '.$input['id'] : '';
                        $class = ($input['class'])           ? ' <br>Class : '.$input['class'] : '';
                        $description = "This is an input element with :".$type.$name.$text_value.$value.$id.$class."<br> Please specify its position.";
                        }else{
                        $name = ($input['inputValue'][0] !='')      ? ' <br>Name : '.$input['inputValue'][0] : '';
                        $description = "This is an input element with :".$type.$name."<br> Please specify its position.";
                        }
                        echo('   
                        <p class="description">'.$description.'</p>
                        </td>
                        </tr>
                        ');
                    
                        break;
                    case 'select':
                        $page_number = (array_key_exists('pageN'.$key, $fgpdf_input_properties_options)) ? $fgpdf_input_properties_options['pageN'.$key] : '';
                        ?>
                        <tr>
                            <td><input id="email_pos" type="radio" name="email_pos" value="<?= $key ?>" <?php checked( $email_pos, $key, true ) ?> ></td>
                            <td class="row-title"> Select Input </td>
                            <td>
                        <?php
                    foreach ($input['inputValue'] as $i => $value) {
                        $hor_pos = (array_key_exists('x'.$key.$i, $fgpdf_input_properties_options)) ? $fgpdf_input_properties_options['x'.$key.$i] : '';
                        $ver_pos = (array_key_exists('y'.$key.$i, $fgpdf_input_properties_options)) ? $fgpdf_input_properties_options['y'.$key.$i] : '';
                        ?>
                            <input id="<?= 'x'.$key.$i.'val' ?>" name="<?= 'x'.$key.$i ?>" type="text"  value="<?= $hor_pos ?>" style="width: 60px;">
                            x
                            <input id="<?= 'y'.$key.$i.'val' ?>" name="<?= 'y'.$key.$i ?>" type="text"  value="<?= $ver_pos ?>" style="width: 60px;">
                            y
                            <button type="button" key="<?= $key.$i ?>" class="positionElement button button-primary" style="margin-left: 17px;">Position</button>
                            <br>
                            <input id="<?= 'page'.$key.$i.'val' ?>" name="<?= 'pageN'.$key ?>" type="hidden" value="<?= $page_number ?>">
                            <p class="description" >This is value option #<?= $i+1 ?> : <strong><?= $value ?></strong></p>
                       <?php     
                        }
                        $type  = ($input['type'])             ? ' <br>Type : '.$input['type']      : '';
                        if(!$fgpdf_page_att_options['fgpdf_alternative_method']){
                        $name  = ($input['name'])             ? ' <br>Name : '.$input['name']      : '';
                        $id    = ($input['id'])               ? ' <br>Id : '.$input['id']          : '';
                        $class = ($input['class'])            ? ' <br>Class : '.$input['class']    : '';
                        $description = "This is an input element with :".$type.$name.$id.$class."<br> Please specify its position.";
                        }else{
                            $description = "This is an input element with :".$type."<br> Please specify its position.";
                        }
                        echo('
                        <p class="description" >'.$description.'</p>
                        </td>
                        </tr>
                        ');
                    
                        break;
                    case 'file':
                    case 'signature':
                        $hor_pos = (array_key_exists('x'.$key, $fgpdf_input_properties_options)) ? $fgpdf_input_properties_options['x'.$key] : '';
                        $ver_pos = (array_key_exists('y'.$key, $fgpdf_input_properties_options)) ? $fgpdf_input_properties_options['y'.$key] : '';

                        $larg = (array_key_exists('larg'.$key, $fgpdf_input_properties_options)) ? $fgpdf_input_properties_options['larg'.$key] : '';
                        $haut = (array_key_exists('haut'.$key, $fgpdf_input_properties_options)) ? $fgpdf_input_properties_options['haut'.$key] : '';


                        $page_number = (array_key_exists('pageN'.$key, $fgpdf_input_properties_options)) ? $fgpdf_input_properties_options['pageN'.$key] : '';
                        ?> 
                        <tr>
                            <td><input id="email_pos" type="radio" name="email_pos" value="<?= $key ?>" <?php checked( $email_pos, $key, true ) ?> ?></td>
                            <td class="row-title"><?php echo (array_key_exists('inputValue', $input) && $input['inputValue'][0] !='') ? $input['inputValue'][0] : 'Input #'.($key+1);  ?> Type :<?= $input['type'] ?> </td>
                            <td>
       
                        <input id="<?= 'x'.$key.'val' ?>" name="<?= 'x'.$key ?>" type="text"  value="<?= $hor_pos ?>" style="width: 60px;">
                        x
                        <input id="<?= 'y'.$key.'val' ?>" name="<?= 'y'.$key ?>" type="text"  value="<?= $ver_pos ?>" style="width: 60px;" >
                        y
                        <button type="button" key="<?= $key ?>" class="positionElement button button-primary" style="margin-left: 17px;">Position</button>


                        <input id="<?= 'larg'.$key.'val' ?>" name="<?= 'larg'.$key ?>" type="text"  value="<?= $larg ?>" style="width: 60px;">
                        w
                        <input id="<?= 'haut'.$key.'val' ?>" name="<?= 'haut'.$key ?>" type="text"  value="<?= $haut ?>" style="width: 60px;" >
                        h
                        <button type="button" key="<?= $key ?>" class="sizeElement button button-primary" style="margin-left: 17px;">Size</button>


                        <br>
                        <input id="<?= 'page'.$key.'val' ?>" name="<?= 'pageN'.$key ?>" type="hidden" value="<?= $page_number ?>">
                        

                        <?php
                        $type = ($input['type'])             ? ' <br>Type : '.$input['type'] : '';
                        if(!$fgpdf_page_att_options['fgpdf_alternative_method']){
                        $name = ($input['name'])             ? ' <br>Name : '.$input['name'] : '';
                        $text_value = ($input['text_value']) ? ' <br>Outer Value : '.$input['text_value'] : '';
                        $value = ($input['value'])           ? ' <br>Input Value : '.$input['value'] : '';
                        $id = ($input['id'])                 ? ' <br>Id : '.$input['id'] : '';
                        $class = ($input['class'])           ? ' <br>Class : '.$input['class'] : '';
                        $description = "This is an input element with :".$type.$name.$text_value.$value.$id.$class."<br> Please specify its position.";
                        }else{
                        $name = ($input['inputValue'][0] !='')      ? ' <br>Name : '.$input['inputValue'][0] : '';
                        $description = "This is an input element with :".$type.$name."<br> Please specify its position.";
                        }
                        echo('   
                        <p class="description">'.$description.'</p>
                        </td>
                        </tr>
                        ');
                    
                        break;




                    }
                }
            ?>
            </tbody>
            </table>
            </div>
            </div>
            </td>
            <td>
            <div id="special">
              <input type="button" class="button button-default" id="upload-button" value="Select PDF">
              <input type="file" id="file-to-upload" accept="application/pdf" />
              <div id="pdf-main-container">
                 <div id="pdf-loader">Loading document ...</div>
                  <div id="pdf-contents">
                      <canvas id="pdf-canvas" width="500px" height="1000" style="cursor: crosshair;"></canvas>
                      <div id="page-loader">Loading page ...</div>
                      <div id="pdf-meta">
                          <div id="pdf-buttons">
                              <input type="button" id="pdf-prev" class="button button-default" value="Previous">
                              <input type="button" id="pdf-next" class="button button-default" value="Next">
                          </div>
                          <div id="page-count-container">Page <div id="pdf-current-page"></div> of <div id="pdf-total-pages"></div></div>
                      </div>
                  </div>
               </div>
            </div>
            <input id="total-pages" name="total-pages" type="hidden" value="<?= $total_pages ?>">
            </td>
            </tr>
            <tfoot>
              <tr>
                <th>Options</th>
                <th>Pdf Canvas</th>
              </tr>
            </tfoot>
            </table>
            <!-- Submit -->
            <p class="submit">
            <input type="submit" id="define-input-properties" class="button-primary" value="<?php _e('Save Changes', 'form-generating-pdf') ?>" />
            <input type="hidden" name="input_properties_form_submit" value="submit" />
            <?php wp_nonce_field(plugin_basename(__FILE__), 'input_properties_nonce_name');
             ?>
            </p> 
            </form>
            </div>
            <?php
    
        }
        elseif($fgpdf_page_att_options['fgpdf_form_page_id']==''){
            echo('<br><div class="notice notice-error"><p>Please complete the first step <strong>Define Page & attachment</strong> first so we can automatically generate this admin page options.</p></div>');
        }
        else{
            echo('<br><div class="notice notice-error"><p>This admin panel couldn\'t be generated this is cause by either you have specified page that has no form in it, or because you have checked the wrong input types in the first step, remmember that you need only to check one or two types not all of them.<br><strong>Important:</strong><br>If you\'re keep getting this message, just check out the <strong>Alternative Method</strong></p></div>');
        }
      }else{
        echo('<br><div class="notice notice-error"><p>Please create a project before setting input properties</p></div>');
        }
    }

}




if(!function_exists('fgpdf_get_font_select')){

    function fgpdf_get_font_select( $input_name="", $selected_value="" ) {
        $fonts = array("Arial", "Times", "Courier");
        $select = '<select name="'. $input_name .'" ';
        $select .= '><option value="">- Select Font -</option>';
        foreach ( $fonts as &$font ):
            $selected = '';
            if( $selected_value == $font ):
                $selected = ' selected="selected" ';
            endif;
            $option = '<option value="' . $font . '" '. $selected .'>';
            $option .= $font;
            $option .= '</option>';

            $select .= $option;
        endforeach;
        $select .= '</select>';
        return $select;
    }

}




if(!function_exists('fgpdf_get_font_size_select')){

    function fgpdf_get_font_size_select( $input_name="", $selected_value="" ) {
        $sizes = array(8, 9, 10, 11, 12, 14, 16, 18, 20, 24, 26, 28, 36, 48, 72);
        $select = '<select name="'. $input_name .'" >';
        foreach ( $sizes as &$size ):
            $selected = '';
            if( $selected_value == $size ):
                $selected = ' selected="selected" ';
            endif;
            $option = '<option value="' . $size . '" '. $selected .'>';
            $option .= $size;
            $option .= '</option>';
            $select .= $option;
        endforeach;
        $select .= '</select>';
        return $select;
    }

}





/*================================= Manage Mail ==============================================================*/



add_action('wp_ajax_users_inputs', 'fgpdf_sending_final_email');
add_action('wp_ajax_nopriv_users_inputs', 'fgpdf_sending_final_email');




if(!function_exists('fgpdf_sending_final_email')){

    function fgpdf_sending_final_email(){
        
        if(isset($_POST['inputValues'])){        
        $fgpdf_input_properties_options_array = get_option('fgpdf_input_properties_options');
        $fgpdf_page_att_options_array = get_option('fgpdf_page_att_options');
        $page = $_POST['inputValues'][0];
        $postedFiles = isset($_FILES['file']) ? $_FILES['file'] : array();
        $canvas = isset($_POST['canvas']) ? $_POST['canvas'] : array();
        $saniPage = substr($page, strpos($page, "/") + 1);
        foreach ($fgpdf_page_att_options_array as $key => $fgpdf_page_att_options) {
            $link = get_page_link($fgpdf_page_att_options['fgpdf_form_page_id']);
            $saniLink = substr($link, strpos($link, "/") + 1);
            if(strpos($saniPage,$saniLink) !== false){

                $email_pos = ($fgpdf_input_properties_options_array[$key]["email_pos"]) ? $fgpdf_input_properties_options_array[$key]["email_pos"] : 0;
                $fgpdf_email_subject = ($fgpdf_page_att_options['fgpdf_email_subject']) ? $fgpdf_page_att_options['fgpdf_email_subject'] : 'Default Subject';
                $fgpdf_email_body = ($fgpdf_page_att_options['fgpdf_email_body']) ? $fgpdf_page_att_options['fgpdf_email_body'] : 'Default Body';
                $fgpdf_attachment_name = ($fgpdf_page_att_options['fgpdf_attachment_name']) ? $fgpdf_page_att_options['fgpdf_attachment_name'] : 'attachment';
                $intvalue = intval($email_pos);

                if(($fgpdf_page_att_options['fgpdf_send_to'] == 'users') || ($fgpdf_page_att_options['fgpdf_send_to'] == 'both')){
                    fgpdf_test_mail($_POST['inputValues'][$intvalue+1], $fgpdf_email_subject, $fgpdf_email_body, $_POST['inputValues'], $postedFiles, $canvas, $fgpdf_attachment_name, $key);
                    //var_dump('true');
                }
                if(($fgpdf_page_att_options['fgpdf_send_to'] == 'admin') || ($fgpdf_page_att_options['fgpdf_send_to'] == 'both')){
                    fgpdf_test_mail($fgpdf_page_att_options['fgpdf_admin_email'], $fgpdf_email_subject, $fgpdf_email_body, $_POST['inputValues'], $postedFiles, $canvas, $fgpdf_attachment_name, $key);
                }
                wp_die();
                break;

            }
        }
    }


    }

}



if(!function_exists('fgpdf_manage_mail')){

    function fgpdf_manage_mail() {
        echo '<div class="wrap" id="swpsmtp-mail">';
        echo '<h2>' . __("Manage Mail Options", 'easy-wp-smtp') . '</h2>';
        echo '<div id="poststuff"><div id="post-body">';

        $display_add_options = $message = $error = $result = '';

        $swpsmtp_options = get_option('swpsmtp_options');
        $smtp_test_mail = get_option('smtp_test_mail');
        if(empty($smtp_test_mail)){
            $smtp_test_mail = array('swpsmtp_to' => '', 'swpsmtp_subject' => '', 'swpsmtp_message' => '', );
        }

        if (isset($_POST['swpsmtp_form_submit']) && check_admin_referer(plugin_basename(__FILE__), 'swpsmtp_nonce_name')) {
            /* Update settings */
            $swpsmtp_options['from_name_field'] = isset($_POST['swpsmtp_from_name']) ? sanitize_text_field(wp_unslash($_POST['swpsmtp_from_name'])) : '';
            if (isset($_POST['swpsmtp_from_email'])) {
                if (is_email($_POST['swpsmtp_from_email'])) {
                    $swpsmtp_options['from_email_field'] = sanitize_email($_POST['swpsmtp_from_email']);
                } else {
                    $error .= " " . __("Please enter a valid email address in the 'FROM' field.", 'easy-wp-smtp');
                }
            }

            $swpsmtp_options['smtp_settings']['host'] = sanitize_text_field($_POST['swpsmtp_smtp_host']);
            $swpsmtp_options['smtp_settings']['type_encryption'] = ( isset($_POST['swpsmtp_smtp_type_encryption']) ) ? sanitize_text_field($_POST['swpsmtp_smtp_type_encryption']) : 'none';
            $swpsmtp_options['smtp_settings']['autentication'] = ( isset($_POST['swpsmtp_smtp_autentication']) ) ? sanitize_text_field($_POST['swpsmtp_smtp_autentication']) : 'yes';
            $swpsmtp_options['smtp_settings']['username'] = sanitize_text_field($_POST['swpsmtp_smtp_username']);
            $smtp_password = sanitize_text_field($_POST['swpsmtp_smtp_password']);
            $swpsmtp_options['smtp_settings']['password'] = base64_encode($smtp_password);

            /* Check value from "SMTP port" option */
            if (isset($_POST['swpsmtp_smtp_port'])) {
                if (empty($_POST['swpsmtp_smtp_port']) || 1 > intval($_POST['swpsmtp_smtp_port']) || (!preg_match('/^\d+$/', $_POST['swpsmtp_smtp_port']) )) {
                    $swpsmtp_options['smtp_settings']['port'] = '25';
                    $error .= " " . __("Please enter a valid port in the 'SMTP Port' field.", 'easy-wp-smtp');
                } else {
                    $swpsmtp_options['smtp_settings']['port'] = sanitize_text_field($_POST['swpsmtp_smtp_port']);
                }
            }

            /* Update settings in the database */
            if (empty($error)) {
                update_option('swpsmtp_options', $swpsmtp_options);
                $message .= __("Settings saved.", 'easy-wp-smtp');
            } else {
                $error .= " " . __("Settings are not saved.", 'easy-wp-smtp');
            }
        }

        /* Send test letter */
        $swpsmtp_to = '';
        
        if ((isset($_POST['swpsmtp_test_submit']) && check_admin_referer(plugin_basename(__FILE__), 'swpsmtp_nonce_name'))) {
            if (isset($_POST['swpsmtp_to']) ) {
                $to_email = sanitize_text_field($_POST['swpsmtp_to']);
                if (is_email($to_email)) {
                    $swpsmtp_to = $to_email;
                } else {
                    $error .= __("Please enter a valid email address in the recipient email field.", 'easy-wp-smtp');
                }
            }
            $swpsmtp_subject = isset($_POST['swpsmtp_subject']) ? sanitize_text_field($_POST['swpsmtp_subject']) : '';
            $swpsmtp_message = isset($_POST['swpsmtp_message']) ? sanitize_text_field($_POST['swpsmtp_message']) : '';
            
            //Save the test mail details so it doesn't need to be filled in everytime.
            $smtp_test_mail['swpsmtp_to'] = $swpsmtp_to;
            $smtp_test_mail['swpsmtp_subject'] = $swpsmtp_subject;
            $smtp_test_mail['swpsmtp_message'] = $swpsmtp_message;
            update_option('smtp_test_mail', $smtp_test_mail);
            
            if (!empty($swpsmtp_to)) {
                $current_key = get_option('fgpdf_pdf_project_options');
                $result = fgpdf_test_mail($swpsmtp_to, $swpsmtp_subject, $swpsmtp_message, array('status'=>'test'), array(), array(), 'testAttachment', $current_key['current_project']);
            }
        }
        

        
        ?>
        <div class="swpsmtp-yellow-box">
            Please visit this step <a target="_blank" href="https://wp-ecommerce.net/easy-wordpress-smtp-send-emails-from-your-wordpress-site-using-a-smtp-server-2197">documentation page</a>  for usage instructions.
        </div>

        <div class="updated fade" <?php if (empty($message)) echo "style=\"display:none\""; ?>>
            <p><strong><?php echo $message; ?></strong></p>
        </div>
        <div class="error" <?php if (empty($error)) echo "style=\"display:none\""; ?>>
            <p><strong><?php echo $error; ?></strong></p>
        </div>
        <div id="swpsmtp-settings-notice" class="updated fade" style="display:none">
            <p><strong><?php _e("Notice:", 'easy-wp-smtp'); ?></strong> <?php _e("The plugin's settings have been changed. In order to save them please don't forget to click the 'Save Changes' button.", 'easy-wp-smtp'); ?></p>
        </div>

        <div class="postbox">
            <h3 class="hndle"><label for="title"><?php _e('Configuration Settings', 'easy-wp-smtp'); ?></label></h3>
            <div class="inside">
                
                <form id="swpsmtp_settings_form" method="post" action="">                   
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row"><?php _e("From Email Address", 'easy-wp-smtp'); ?></th>
                            <td>
                                <input type="text" name="swpsmtp_from_email" value="<?php echo esc_attr($swpsmtp_options['from_email_field']); ?>"/><br />
                                <p class="description"><?php _e("This email address will be used in the 'From' field.", 'easy-wp-smtp'); ?></p>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php _e("From Name", 'easy-wp-smtp'); ?></th>
                            <td>
                                <input type="text" name="swpsmtp_from_name" value="<?php echo esc_attr($swpsmtp_options['from_name_field']); ?>"/><br />
                                <p class="description"><?php _e("This text will be used in the 'FROM' field", 'easy-wp-smtp'); ?></p>
                            </td>
                        </tr>           
                        <tr class="ad_opt swpsmtp_smtp_options">
                            <th><?php _e('SMTP Host', 'easy-wp-smtp'); ?></th>
                            <td>
                                <input type='text' name='swpsmtp_smtp_host' value='<?php echo esc_attr($swpsmtp_options['smtp_settings']['host']); ?>' /><br />
                                <p class="description"><?php _e("Your mail server", 'easy-wp-smtp'); ?></p>
                            </td>
                        </tr>
                        <tr class="ad_opt swpsmtp_smtp_options">
                            <th><?php _e('Type of Encription', 'easy-wp-smtp'); ?></th>
                            <td>
                                <label for="swpsmtp_smtp_type_encryption_1"><input type="radio" id="swpsmtp_smtp_type_encryption_1" name="swpsmtp_smtp_type_encryption" value='none' <?php if ('none' == $swpsmtp_options['smtp_settings']['type_encryption']) echo 'checked="checked"'; ?> /> <?php _e('None', 'easy-wp-smtp'); ?></label>
                                <label for="swpsmtp_smtp_type_encryption_2"><input type="radio" id="swpsmtp_smtp_type_encryption_2" name="swpsmtp_smtp_type_encryption" value='ssl' <?php if ('ssl' == $swpsmtp_options['smtp_settings']['type_encryption']) echo 'checked="checked"'; ?> /> <?php _e('SSL', 'easy-wp-smtp'); ?></label>
                                <label for="swpsmtp_smtp_type_encryption_3"><input type="radio" id="swpsmtp_smtp_type_encryption_3" name="swpsmtp_smtp_type_encryption" value='tls' <?php if ('tls' == $swpsmtp_options['smtp_settings']['type_encryption']) echo 'checked="checked"'; ?> /> <?php _e('TLS', 'easy-wp-smtp'); ?></label><br />
                                <p class="description"><?php _e("For most servers SSL is the recommended option", 'easy-wp-smtp'); ?></p>
                            </td>
                        </tr>
                        <tr class="ad_opt swpsmtp_smtp_options">
                            <th><?php _e('SMTP Port', 'easy-wp-smtp'); ?></th>
                            <td>
                                <input type='text' name='swpsmtp_smtp_port' value='<?php echo esc_attr($swpsmtp_options['smtp_settings']['port']); ?>' /><br />
                                <p class="description"><?php _e("The port to your mail server", 'easy-wp-smtp'); ?></p>
                            </td>
                        </tr>
                        <tr class="ad_opt swpsmtp_smtp_options">
                            <th><?php _e('SMTP Authentication', 'easy-wp-smtp'); ?></th>
                            <td>
                                <label for="swpsmtp_smtp_autentication"><input type="radio" id="swpsmtp_smtp_autentication" name="swpsmtp_smtp_autentication" value='no' <?php if ('no' == $swpsmtp_options['smtp_settings']['autentication']) echo 'checked="checked"'; ?> /> <?php _e('No', 'easy-wp-smtp'); ?></label>
                                <label for="swpsmtp_smtp_autentication"><input type="radio" id="swpsmtp_smtp_autentication" name="swpsmtp_smtp_autentication" value='yes' <?php if ('yes' == $swpsmtp_options['smtp_settings']['autentication']) echo 'checked="checked"'; ?> /> <?php _e('Yes', 'easy-wp-smtp'); ?></label><br />
                                <p class="description"><?php _e("This options should always be checked 'Yes'", 'easy-wp-smtp'); ?></p>
                            </td>
                        </tr>
                        <tr class="ad_opt swpsmtp_smtp_options">
                            <th><?php _e('SMTP username', 'easy-wp-smtp'); ?></th>
                            <td>
                                <input type='text' name='swpsmtp_smtp_username' value='<?php echo esc_attr($swpsmtp_options['smtp_settings']['username']); ?>' /><br />
                                <p class="description"><?php _e("The username to login to your mail server", 'easy-wp-smtp'); ?></p>
                            </td>
                        </tr>
                        <tr class="ad_opt swpsmtp_smtp_options">
                            <th><?php _e('SMTP Password', 'easy-wp-smtp'); ?></th>
                            <td>
                                <input type='password' name='swpsmtp_smtp_password' value='<?php echo esc_attr(fgpdf_get_password()); ?>' /><br />
                                <p class="description"><?php _e("The password to login to your mail server", 'easy-wp-smtp'); ?></p>
                            </td>
                        </tr>
                    </table>
                    <p class="submit">
                        <input type="submit" id="settings-form-submit" class="button-primary" value="<?php _e('Save Changes', 'easy-wp-smtp') ?>" />
                        <input type="hidden" name="swpsmtp_form_submit" value="submit" />
                        <?php wp_nonce_field(plugin_basename(__FILE__), 'swpsmtp_nonce_name'); ?>
                    </p>                
                </form>
            </div><!-- end of inside -->
        </div><!-- end of postbox -->

        <div class="updated fade" <?php if (empty($result)) echo "style=\"display:none\""; ?>>
            <p><strong><?php echo $result; ?></strong></p><!-- shows the result from the test email send function -->
        </div>

        <div class="postbox">
            <h3 class="hndle"><label for="title"><?php _e('Testing And Debugging Settings', 'easy-wp-smtp'); ?></label></h3>
            <div class="inside">    

                <p>You can use this section to send an email from your server using the above configured details to see if the email gets delivered.</p>
                
                <form id="swpsmtp_settings_form" method="post" action="">                   
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row"><?php _e("To", 'easy-wp-smtp'); ?>:</th>
                            <td>
                                <input type="text" name="swpsmtp_to" value="<?php echo esc_html($smtp_test_mail['swpsmtp_to']); ?>" /><br />
                                <p class="description"><?php _e("Enter the recipient's email address", 'easy-wp-smtp'); ?></p>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php _e("Subject", 'easy-wp-smtp'); ?>:</th>
                            <td>
                                <input type="text" name="swpsmtp_subject" value="<?php echo esc_html($smtp_test_mail['swpsmtp_subject']); ?>" /><br />
                                <p class="description"><?php _e("Enter a subject for your message", 'easy-wp-smtp'); ?></p>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php _e("Message", 'easy-wp-smtp'); ?>:</th>
                            <td>
                                <textarea name="swpsmtp_message" id="swpsmtp_message" rows="5"><?php echo esc_textarea($smtp_test_mail['swpsmtp_message']); ?></textarea>
                                <br />
                                <p class="description"><?php _e("Write your email message", 'easy-wp-smtp'); ?></p>
                            </td>
                        </tr>               
                    </table>
                    <p class="submit">
                        <input type="submit" id="settings-form-submit" class="button-primary" value="<?php _e('Send Test Email', 'easy-wp-smtp') ?>" />
                        <input type="hidden" name="swpsmtp_test_submit" value="submit" />
                        <?php wp_nonce_field(plugin_basename(__FILE__), 'swpsmtp_nonce_name'); ?>
                    </p>                
                </form>
            </div><!-- end of inside -->
        </div><!-- end of postbox -->

        <?php
        echo '</div></div>'; //<!-- end of #poststuff and #post-body -->
        echo '</div>'; //<!--  end of .wrap #swpsmtp-mail .swpsmtp-mail -->
    }

}




if(!function_exists('fgpdf_admin_init')){

    function fgpdf_admin_init() {
        /* Internationalization, first(!) */
        load_plugin_textdomain('easy-wp-smtp', false, dirname(plugin_basename(__FILE__)) . '/languages/');

        if (isset($_REQUEST['page']) ) {
            /* register plugin settings */
            fgpdf_register_settings();
        }
    }

}



if(!function_exists('fgpdf_register_settings')){

    function fgpdf_register_settings() {
        $swpsmtp_options_default = array(
            'from_email_field' => '',
            'from_name_field' => '',
            'smtp_settings' => array(
                'host' => 'smtp.example.com',
                'type_encryption' => 'none',
                'port' => 25,
                'autentication' => 'yes',
                'username' => 'yourusername',
                'password' => 'yourpassword'
            )
        );


        /* install the default plugin options */
        if (!get_option('swpsmtp_options')) {
            add_option('swpsmtp_options', $swpsmtp_options_default, '', 'yes');
        }
    }

}




add_action( 'wp_ajax_fgpdf_my_form_types', 'fgpdf_my_form_types' );

if(!function_exists('fgpdf_my_form_types')){

    function fgpdf_my_form_types() {

        $fgpdf_page_att_options_array = get_option('fgpdf_page_att_options');
        $current_key = get_option('fgpdf_pdf_project_options');
        $fgpdf_page_att_options = $fgpdf_page_att_options_array[$current_key['current_project']];
        $mergeArray = ( isset($_POST['inputElement']) ) ? $_POST['inputElement'] : array();
        $fgpdf_page_att_options['fgpdf_form_types'] = array_merge($fgpdf_page_att_options['fgpdf_form_types'], $mergeArray);
        $fgpdf_page_att_options_array[$current_key['current_project']]['fgpdf_form_types'] = $fgpdf_page_att_options['fgpdf_form_types'];
        update_option('fgpdf_page_att_options', $fgpdf_page_att_options_array);
        wp_die();
    }

}



add_action( 'wp_ajax_fgpdf_delete_element', 'fgpdf_delete_element' );

if(!function_exists('fgpdf_delete_element')){

    function fgpdf_delete_element() {
        $fgpdf_page_att_options_array = get_option('fgpdf_page_att_options');
        $current_key = get_option('fgpdf_pdf_project_options');
        $fgpdf_page_att_options = $fgpdf_page_att_options_array[$current_key['current_project']];
        $idKey = ( isset($_POST['id']) ) ? $_POST['id'] : '';
        $fgpdf_form_types = $fgpdf_page_att_options['fgpdf_form_types'];
        if(array_key_exists($idKey, $fgpdf_form_types)){
            array_splice($fgpdf_form_types, $idKey, 1);
        }
        $fgpdf_page_att_options_array[$current_key['current_project']]['fgpdf_form_types'] = $fgpdf_form_types;
        update_option('fgpdf_page_att_options', $fgpdf_page_att_options_array);
        wp_die();
    }

}



add_action( 'wp_ajax_fgpdf_delete_value_item', 'fgpdf_delete_value_item' );

if(!function_exists('fgpdf_delete_value_item')){

    function fgpdf_delete_value_item() {
        $fgpdf_page_att_options_array = get_option('fgpdf_page_att_options');
        $current_key = get_option('fgpdf_pdf_project_options');
        $fgpdf_page_att_options = $fgpdf_page_att_options_array[$current_key['current_project']];
        $keyArray = ( isset($_POST['keyArray']) ) ? $_POST['keyArray'] : '';
        $fgpdf_form_types = $fgpdf_page_att_options['fgpdf_form_types'];
        if(array_key_exists($keyArray[0], $fgpdf_form_types) && array_key_exists($keyArray[1], $fgpdf_form_types[$keyArray[0]]['inputValue'])){
            array_splice($fgpdf_form_types[$keyArray[0]]['inputValue'], $keyArray[1], 1);
        }    
        $fgpdf_page_att_options_array[$current_key['current_project']]['fgpdf_form_types'] = $fgpdf_form_types;
        update_option('fgpdf_page_att_options', $fgpdf_page_att_options_array);
        wp_die();
    }

}



if(!function_exists('fgpdf_slash_attachment_escape')){

    function fgpdf_slash_attachment_escape($path){
        $arr = explode("/", $path);
        $last = $arr[count($arr) -1] ;
        return $last;
    }

}


if(!function_exists('fgpdf_options_admin_page')){

    function fgpdf_options_admin_page() {
        $fgpdf_page_att_options_array = get_option('fgpdf_page_att_options');

        if($fgpdf_page_att_options_array !== array()){
        $current_key = get_option('fgpdf_pdf_project_options');
        $fgpdf_page_att_options = $fgpdf_page_att_options_array[$current_key['current_project']]; 
        echo '<div class="wrap">';
        echo '<h2>' . __("Define Page & Attachment", 'form-generating-pdf') . '</h2>';
        
        if (isset($_POST['page_att_form_submit']) && check_admin_referer(plugin_basename(__FILE__), 'page_att_nonce_name')) {
            /* Update settings */
            $fgpdf_page_att_options['fgpdf_form_page_id'] = isset($_POST['fgpdf_form_page_id']) ? $_POST['fgpdf_form_page_id'] : '';
            $fgpdf_page_att_options['fgpdf_select_attachment_id'] = ( isset($_POST['fgpdf_select_attachment_id']) ) ? $_POST['fgpdf_select_attachment_id'] : '';
            $fgpdf_page_att_options['fgpdf_email_subject'] = ( isset($_POST['fgpdf_email_subject']) ) ? sanitize_text_field($_POST['fgpdf_email_subject']) : '';
            $fgpdf_page_att_options['fgpdf_email_body'] = ( isset($_POST['fgpdf_email_body']) ) ? stripslashes($_POST['fgpdf_email_body']) : '';
            $fgpdf_page_att_options['fgpdf_admin_email'] = ( isset($_POST['fgpdf_admin_email']) ) ? $_POST['fgpdf_admin_email'] : '';

            $fgpdf_page_att_options['fgpdf_attachment_name'] = ( isset($_POST['fgpdf_attachment_name']) ) ? $_POST['fgpdf_attachment_name'] : '';

            $fgpdf_page_att_options['fgpdf_send_to'] = ( isset($_POST['fgpdf_send_to']) ) ? $_POST['fgpdf_send_to'] : '';

            $fgpdf_page_att_options['fgpdf_form_checks']['fgpdf_text'] = ( isset($_POST['fgpdf_text']) ) ? $_POST['fgpdf_text'] : '';
            $fgpdf_page_att_options['fgpdf_form_checks']['fgpdf_email'] = ( isset($_POST['fgpdf_email']) ) ? $_POST['fgpdf_email'] : '';
            $fgpdf_page_att_options['fgpdf_form_checks']['fgpdf_password'] = ( isset($_POST['fgpdf_password']) ) ? $_POST['fgpdf_password'] : '';
            $fgpdf_page_att_options['fgpdf_form_checks']['fgpdf_select'] = ( isset($_POST['fgpdf_select']) ) ? $_POST['fgpdf_select'] : '';
            $fgpdf_page_att_options['fgpdf_form_checks']['fgpdf_textarea'] = ( isset($_POST['fgpdf_textarea']) ) ? $_POST['fgpdf_textarea'] : '';
            $fgpdf_page_att_options['fgpdf_form_checks']['fgpdf_checkbox'] = ( isset($_POST['fgpdf_checkbox']) ) ? $_POST['fgpdf_checkbox'] : '';
            $fgpdf_page_att_options['fgpdf_form_checks']['fgpdf_radio'] = ( isset($_POST['fgpdf_radio']) ) ? $_POST['fgpdf_radio'] : '';
            $fgpdf_page_att_options['fgpdf_form_checks']['fgpdf_file'] = ( isset($_POST['fgpdf_file']) ) ? $_POST['fgpdf_file'] : '';
            $fgpdf_page_att_options['fgpdf_form_checks']['fgpdf_signature'] = ( isset($_POST['fgpdf_signature']) ) ? $_POST['fgpdf_signature'] : '';
            $fgpdf_page_att_options['fgpdf_form_checks']['fgpdf_tel'] = ( isset($_POST['fgpdf_tel']) ) ? $_POST['fgpdf_tel'] : '';
            $fgpdf_page_att_options['fgpdf_form_checks']['fgpdf_date'] = ( isset($_POST['fgpdf_date']) ) ? $_POST['fgpdf_date'] : '';
            $fgpdf_page_att_options['fgpdf_form_checks']['fgpdf_number'] = ( isset($_POST['fgpdf_number']) ) ? $_POST['fgpdf_number'] : '';

            $fgpdf_page_att_options['fgpdf_alternative_method'] = ( isset($_POST['fgpdf_alternative_method']) ) ? $_POST['fgpdf_alternative_method'] : '';
            if(isset($fgpdf_page_att_options['fgpdf_alternative_method']) && isset($fgpdf_page_att_options['fgpdf_form_types'])){

                foreach ($fgpdf_page_att_options['fgpdf_form_types'] as $key => $input) {
                    if($input['type'] !== 'select'){
                        if(isset($_POST['formItemInput'.$key])){
                            $fgpdf_page_att_options['fgpdf_form_types'][$key]['inputValue'][0] = $_POST['formItemInput'.$key] ;
                        }
                    }else{
                        foreach ($input['inputValue'] as $idx => $value) {
                            if(isset($_POST['formItemInputValue'.$key.$idx])){
                                $fgpdf_page_att_options['fgpdf_form_types'][$key]['inputValue'][$idx] = $_POST['formItemInputValue'.$key.$idx];
                            }
                        }
                    }
                }
            }
            $fgpdf_page_att_options_array[$current_key['current_project']] = $fgpdf_page_att_options;
            update_option('fgpdf_page_att_options', $fgpdf_page_att_options_array);
        }

        ?>
        <form action="" method="post">
        <table class="form-table">
        <tbody>
            <tr>
                <th scope="row"><label for="fgpdf_form_page_id"><?php _e('Manage Form Page', 'form-generating-pdf'); ?></label></th>
                <td>
                    <?= fgpdf_get_page_select( 'fgpdf_form_page_id', 'fgpdf_form_page_id', 'id', $fgpdf_page_att_options['fgpdf_form_page_id'] ) ?>
                    <p class="description"><?php _e('Identify which page has the targeted form.', 'form-generating-pdf'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="fgpdf_select_attachment_id"><?php _e('Select Attachment', 'form-generating-pdf'); ?></label></th>
                <td>
                    <?= fgpdf_get_attachment_select( 'fgpdf_select_attachment_id', 'fgpdf_select_attachment_id', fgpdf_slash_attachment_escape($fgpdf_page_att_options['fgpdf_select_attachment_id'])) ?>
                    <p class="description"><?php _e('This is a list of the attachment you are having in the <strong>Media Library</strong>, please select the document you want to use as template. <br />IMPORTANT: You should upload A4 (Word default paper size) paper document PDF in the media library.', 'form-generating-pdf'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="fgpdf_send_to"><?php _e('Send PDF to', 'form-generating-pdf'); ?></label></th>
                <td>
                <?php
                echo('
                    <input type="radio" id="fgpdf_admin" name="fgpdf_send_to" value="admin"'. checked( "admin", $fgpdf_page_att_options['fgpdf_send_to'], false) .' > Admin
                    <input type="radio" id="fgpdf_users" name="fgpdf_send_to" value="users"'.  checked( "users", $fgpdf_page_att_options['fgpdf_send_to'], false) .' > Users
                    <input type="radio" id="fgpdf_both" name="fgpdf_send_to" value="both"'.  checked( "both", $fgpdf_page_att_options['fgpdf_send_to'], false) .' > Both
                ');
                ?>
                </td>
            </tr>
            <tr id="fgpdf_admin_email_div" <?php if($fgpdf_page_att_options['fgpdf_send_to'] == "users"){ ?> style="display:none" <?php } ?> >
                <th scope="row"><label for="fgpdf_admin_email"><?php _e('Admin Email', 'form-generating-pdf'); ?></label></th>
                <td>
                    <input type="text" id="fgpdf_admin_email" name="fgpdf_admin_email" value="<?php echo $fgpdf_page_att_options['fgpdf_admin_email'] ?>">
                    <p class="description"><?php _e('Specify the admin email which the PDF goes to.', 'form-generating-pdf'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="fgpdf_attachment_name"><?php _e('Attachment Name', 'form-generating-pdf'); ?></label></th>
                <td>
                    <input type="text" id="fgpdf_attachment_name" name="fgpdf_attachment_name" value="<?php echo $fgpdf_page_att_options['fgpdf_attachment_name'] ?>">
                    <p class="description"><?php _e('This is the name of the PDF.', 'form-generating-pdf'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="fgpdf_email_subject"><?php _e('Email Subject', 'form-generating-pdf'); ?></label></th>
                <td>
                    <input type="text" id="fgpdf_email_subject" name="fgpdf_email_subject" value="<?php echo $fgpdf_page_att_options['fgpdf_email_subject'] ?>">
                    <p class="description"><?php _e('This is the subject of the email to be sent.', 'form-generating-pdf'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="fgpdf_email_body"><?php _e('Email body', 'form-generating-pdf'); ?></label></th>
                <td><?php
                    $settings = array( 'media_buttons' => false, 'textarea_rows'=>8 );
                    wp_editor( stripslashes(preg_replace("/&#?[a-z0-9]+;/i",'',$fgpdf_page_att_options['fgpdf_email_body'])), 'fgpdf_email_body', $settings );
                    ?><p class="description"><?php _e('This is the body of the email to be sent.', 'form-generating-pdf'); ?></p>
                </td>
            </tr>                      
            <tr>
                <th scope="row"><label>Form Elements</label></th>
                <td><?php
                echo ('<input type="checkbox" name="fgpdf_text" value="1"'. checked( "1", $fgpdf_page_att_options['fgpdf_form_checks']["fgpdf_text"], false) .' > Text
                    <input type="checkbox" name="fgpdf_email" value="1"'. checked( "1", $fgpdf_page_att_options['fgpdf_form_checks']["fgpdf_email"], false) .' > Email
                    <input type="checkbox" name="fgpdf_password" value="1"'.  checked( "1", $fgpdf_page_att_options['fgpdf_form_checks']["fgpdf_password"], false) .' > Password
                    <input type="checkbox" name="fgpdf_select" value="1"'.  checked( "1", $fgpdf_page_att_options['fgpdf_form_checks']["fgpdf_select"], false) .' > Select
                    <input type="checkbox" name="fgpdf_textarea" value="1"'. checked( "1", $fgpdf_page_att_options['fgpdf_form_checks']["fgpdf_textarea"], false) .' > Textarea
                    <input type="checkbox" name="fgpdf_checkbox" value="1"'.  checked( "1", $fgpdf_page_att_options['fgpdf_form_checks']["fgpdf_checkbox"], false) .' > CheckBox
                    <input type="checkbox" name="fgpdf_radio" value="1"'.  checked( "1", $fgpdf_page_att_options['fgpdf_form_checks']["fgpdf_radio"], false) .' > Radio
                    <input type="checkbox" name="fgpdf_file" value="1"'.  checked( "1", $fgpdf_page_att_options['fgpdf_form_checks']["fgpdf_file"], false) .' > File
                    <input type="checkbox" name="fgpdf_signature" value="1"'.  checked( "1", $fgpdf_page_att_options['fgpdf_form_checks']["fgpdf_signature"], false) .' > Signature
                    <input type="checkbox" name="fgpdf_tel" value="1"'.  checked( "1", $fgpdf_page_att_options['fgpdf_form_checks']["fgpdf_tel"], false) .' > Tel
                    <input type="checkbox" name="fgpdf_date" value="1"'.  checked( "1", $fgpdf_page_att_options['fgpdf_form_checks']["fgpdf_date"], false) .' > Date
                    <input type="checkbox" name="fgpdf_number" value="1"'.  checked( "1", $fgpdf_page_att_options['fgpdf_form_checks']["fgpdf_number"], false) .' > Number');?>
                    <p class="description"><?php _e('This section is used to identify the right form, if your page has multiple forms check some of the input types elements of the targeted form, so we can get the right one, if not just keep them unchecked, check out this <a href="https://www.xul.fr/javascript/form-objects.php">link</a> if you need information about input types and form elements.<br />
                    IMPORTANT: Please verify that you have specified the right ones, check only the ones you\'re totally sure of.', 'form-generating-pdf'); ?></p>
                </td>
            </tr>


            <tr>
                <th scope="row"><label>Alternative Method</label></th>
                <td>
                    <?php
                     echo ('<input type="checkbox" id="fgpdf_alternative_method" name="fgpdf_alternative_method" value="1" '. checked( "1", $fgpdf_page_att_options['fgpdf_alternative_method'], false) .' >');
                     ?>
                    <p class="description"><?php _e('If your website does not allow web scraping, we offer this alternative method of determining Input types.', 'form-generating-pdf'); ?></p>
                </td>
            </tr>


            <tr id="fgpdf_alternative_method_div" <?php if($fgpdf_page_att_options['fgpdf_alternative_method'] ==  0){ ?> style="display:none" <?php } ?> >
                <th scope="row"><label><?php _e('Form Inputs with Manual Method', 'form-generating-pdf'); ?></label></th>
                <td>
                <button type="button" value="text" class="formType button button-default">Text</button>
                <button type="button" value="email" class="formType button button-default">Email</button>
                <button type="button" value="password" class="formType button button-default">Password</button>
                <button type="button" value="select" class="formType button button-default">Select</button>
                <button type="button" id="fgpdf_select_value" disabled="true" class="button button-default">Select Value</button>
                <button type="button" value="textarea" class="formType button button-default">Textarea</button>
                <button type="button" value="checkbox" class="formType button button-default">CheckBox</button>
                <button type="button" value="radio" class="formType button button-default">Radio</button>
                <button type="button" value="file" class="formType button button-default">File</button>
                <button type="button" value="signature" class="formType button button-default">Signature</button>
                <button type="button" value="tel" class="formType button button-default">Tel</button>
                <button type="button" value="date" class="formType button button-default">Date</button>
                <button type="button" value="number" class="formType button button-default">Number</button>
                <button type="button" class="button button-primary" id="formValidate" >Validate</button>
                
                    <table class="tableAppendTo scrollTableItems widefat">
                        <thead>
                            <tr>
                                <th>Form Type</th>
                                <th>Input Name (Optional)</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                if(isset($fgpdf_page_att_options['fgpdf_form_types']) && (is_array($fgpdf_page_att_options['fgpdf_form_types']) || is_object($fgpdf_page_att_options['fgpdf_form_types']))){
                                    foreach ($fgpdf_page_att_options['fgpdf_form_types'] as $key => $input) {
                                        if($input['type'] !== 'select'){
                                        $nameInput = array_key_exists('inputValue', $input) ? $input['inputValue'][0] : '' ;

                                        echo("<tr><td class='row-title'>".$input['type']."</td><td><input type='text'  placeholder='Name' name='formItemInput".$key."' value='".$nameInput."'></td><td><button type='button' class='deleteItem button button-primary' key='".$key."' >Delete</button></td></tr>");
                                        }else{
                                            echo("<tr><td class='row-title'>".$input['type']."</td><td>");

                                            foreach ($input['inputValue'] as $idx => $value) {
                                                echo("<div><br>Value: <input type='text' name='formItemInputValue".$key.$idx."' value='".$value."' > <button type='button' class='deleteItemValue button button-primary' key='".$key."'  idx='".$idx."' >Delete Value</button> </div>");
                                            }
                                            
                                            echo("</td><td><button type='button' class='deleteItem button button-primary' key='".$key."'>Delete</button></td></tr>");
                                        }
                                    }
                                }


                            ?>
                        </tbody>
                    </table>
                    <p class="description"><?php _e('Make a specific list of your input elements, Note: when you click on each one of them it gets appended to this table, and second column is made to let you know which input you\'re working with in the next step by naming each one, <br>Important: Please make sure to validate after you finish, and if you have other input types just ignore them.', 'form-generating-pdf'); ?></p>
                </td>
            </tr>
        </tbody>
        </table>
        <p class="submit">
            <input type="submit" id="define-page-attachment" class="button-primary" value="<?php _e('Save Changes', 'form-generating-pdf') ?>" />
            <input type="hidden" name="page_att_form_submit" value="submit" />
            <?php wp_nonce_field(plugin_basename(__FILE__), 'page_att_nonce_name'); ?>
        </p>  
        </form>
        </div>
        <?php
    }else{
        echo('<br><div class="notice notice-error"><p>Please create a project before defining page and attachment</p></div>');
    }
    }

}



if(!function_exists('fgpdf_input_properties_default_settings')){

    function fgpdf_input_properties_default_settings() {

        /* install the default plugin options */
        if (!get_option('fgpdf_input_properties_options')) {
            add_option('fgpdf_input_properties_options', array() , '', 'no');
        }
    }

}




if(!function_exists('fgpdf_pdf_project_default_settings')){

    function fgpdf_pdf_project_default_settings() {

        /* install the default plugin options */
        if (!get_option('fgpdf_pdf_project_options')) {
            add_option('fgpdf_pdf_project_options', array('current_project' => 0) , '', 'no');
        }
    }

}



if(!function_exists('fgpdf_page_att_default_settings')){

    function fgpdf_page_att_default_settings(){

        /* install the default plugin options */
        if (!get_option('fgpdf_page_att_options')) {
            add_option('fgpdf_page_att_options', array(), '', 'no');
        }
    }

}






if(!function_exists('fgpdf_init_admin_options')){

    function fgpdf_init_admin_options() {
        /* Internationalization, first(!) */
        // ....

        if (isset($_REQUEST['page']) ) {
            /* register plugin settings */
            fgpdf_pdf_project_default_settings();
            fgpdf_page_att_default_settings();
            fgpdf_input_properties_default_settings();
        }
    }

}



// register plugin options
add_action('admin_init', 'fgpdf_init_admin_options');







if(!function_exists('fgpdf_get_attachment_select')){

    function fgpdf_get_attachment_select( $input_name="fgpdf_page", $input_id="", $selected_value="" ) {

        // get WP pages
    $args = array(
        'post_type' => 'attachment',
        'sort_order' => 'asc',
        'sort_column' => 'post_title',
        'post_mime_type' => 'application/pdf',
        'numberposts' => -1,
        'post_status' => null,
        'post_parent' => null, // any parent
        );

    $attachments = get_posts($args);
        $select = '<select name="'. $input_name .'" ';
        if( strlen($input_id) ):
            $select .= 'id="'. $input_id .'" ';
        endif;
        $select .= '><option value="">- Select One -</option>';
        foreach ( $attachments as $attachment ):
            $upload = wp_upload_dir($attachment->post_date);
            $postName = $attachment->post_title.'.pdf';
            $value = $upload['path'].'/'. $postName;
            $selected = '';
            if( strcmp($selected_value, $postName)  == 0 ):
                $selected = ' selected="selected" ';
            endif;
            $option = '<option value="' . $value . '" '. $selected .'>';
            $option .= $attachment->post_title;
            $option .= '</option>';
            $select .= $option;

        endforeach;
        $select .= '</select>';
        return $select;
    }

}




if(!function_exists('fgpdf_get_page_select')){

    function fgpdf_get_page_select( $input_name="fgpdf_page", $input_id="", $value_field="id", $selected_value="" ) {
        // get WP pages
        $pages = get_pages(
            array(
                'sort_order' => 'asc',
                'sort_column' => 'post_title',
                'post_type' => 'page',
                'status'=>array('draft','publish'),
            )
        );
        $select = '<select name="'. $input_name .'" ';
        if( strlen($input_id) ):
            $select .= 'id="'. $input_id .'" ';
        endif;
        $select .= '><option value="">- Select One -</option>';
        foreach ( $pages as &$page ):
            $value = $page->ID;
            switch( $value_field ) {
                case 'slug':
                    $value = $page->post_name;
                    break;
                case 'url':
                    $value = get_page_link( $page->ID );
                    break;
                default:
                    $value = $page->ID;
            }
            $selected = '';
            if( $selected_value == $value ):
                $selected = ' selected="selected" ';
            endif;
            $option = '<option value="' . $value . '" '. $selected .'>';
            $option .= $page->post_title;
            $option .= '</option>';
            $select .= $option;
        endforeach;
        $select .= '</select>';
        return $select;
    }

}















/* ======================== easy-wp-smtp ========================*/


//plugins_loaded action hook handler
if (!function_exists('fgpdf_plugins_loaded_handler')) {

    function fgpdf_plugins_loaded_handler() {
        load_plugin_textdomain('easy-wp-smtp', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

}


/**
 * Function to add plugin scripts
 * @return void
 */
if (!function_exists('fgpdf_admin_head')) {

    function fgpdf_admin_head() {
        wp_enqueue_style('swpsmtp_stylesheet', plugins_url('css/mail.css', __FILE__));

        if (isset($_REQUEST['page']) && 'swpsmtp_settings' == $_REQUEST['page']) {
            wp_enqueue_script('swpsmtp_script', plugins_url('inlcudes/js/mail.js', __FILE__), array('jquery'));
        }
    }

}

if (!function_exists('fgpdf_init_smtp')) {

    function fgpdf_init_smtp($phpmailer) {
        //check if SMTP credentials have been configured.
        if (!fgpdf_credentials_configured()) {
            return;
        }
        $swpsmtp_options = get_option('swpsmtp_options');
        /* Set the mailer type as per config above, this overrides the already called isMail method */
        $phpmailer->IsSMTP();
        $from_email = $swpsmtp_options['from_email_field'];
        $phpmailer->From = $from_email;
        $from_name = $swpsmtp_options['from_name_field'];
        $phpmailer->FromName = $from_name;
        $phpmailer->SetFrom($phpmailer->From, $phpmailer->FromName);
        /* Set the SMTPSecure value */
        if ($swpsmtp_options['smtp_settings']['type_encryption'] !== 'none') {
            $phpmailer->SMTPSecure = $swpsmtp_options['smtp_settings']['type_encryption'];
        }

        /* Set the other options */
        $phpmailer->Host = $swpsmtp_options['smtp_settings']['host'];
        $phpmailer->Port = $swpsmtp_options['smtp_settings']['port'];

        /* If we're using smtp auth, set the username & password */
        if ('yes' == $swpsmtp_options['smtp_settings']['autentication']) {
            $phpmailer->SMTPAuth = true;
            $phpmailer->Username = $swpsmtp_options['smtp_settings']['username'];
            $phpmailer->Password = fgpdf_get_password();
        }
        //PHPMailer 5.2.10 introduced this option. However, this might cause issues if the server is advertising TLS with an invalid certificate.
        $phpmailer->SMTPAutoTLS = false;

    }

}



if (!function_exists('fgpdf_sanitise_special_characters')) {

    function fgpdf_sanitise_special_characters($dataarray){
        foreach ($dataarray as $key => $value) {
            $reportSubtitle = stripslashes($value);
            $reportSubtitle = iconv('UTF-8', 'windows-1252', $reportSubtitle);
            $dataarray[$key] = $reportSubtitle;
        }
        return $dataarray;

    }

}




if (!function_exists('fgpdf_generateRandomString')) {
    function fgpdf_generateRandomString($length = 15) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}



if(!function_exists('fgpdf_get_all_form_pages')){

  function fgpdf_get_all_form_pages() {
    $form_pages_links = array();
    $fgpdf_page_att_options_array = get_option('fgpdf_page_att_options');
    foreach ($fgpdf_page_att_options_array as $key => $fgpdf_page_att_options) {
        $link = get_page_link($fgpdf_page_att_options['fgpdf_form_page_id']);
        array_push($form_pages_links, substr($link, strpos($link, "/") + 1));
    }
    return $form_pages_links;
  }

}




add_action( 'wp_footer', 'fgpdf_useful_array_var_footer' );

if(!function_exists('fgpdf_useful_array_var_footer')){

  function fgpdf_useful_array_var_footer() {

    $fgpdf_get_all_form_pages = fgpdf_get_all_form_pages();
    
    ?><script type='text/javascript'>
      var fgpdf_get_all_form_pages = <?php echo json_encode( $fgpdf_get_all_form_pages );?> ;
    </script><?php
  }

}











if (!function_exists('fgpdf_get_attachment')) {

    function fgpdf_get_attachment($current_key, $dataarray, $postedFiles, $canvas) {
        include_once(plugin_dir_path( __FILE__ )."libs/fpdf/fpdf.php"); 
        include_once(plugin_dir_path( __FILE__ )."libs/fpdi/fpdi.php");
        $web_scraping_form = fgpdf_get_input_types($current_key);
        $fgpdf_page_att_options_array = get_option('fgpdf_page_att_options');
        $fgpdf_page_att_options = $fgpdf_page_att_options_array[$current_key];
        $input_types = ($fgpdf_page_att_options['fgpdf_alternative_method']) ? $fgpdf_page_att_options['fgpdf_form_types'] : $web_scraping_form ;

        $pdfoption_array   = get_option('fgpdf_input_properties_options');
        $pdfoption   = $pdfoption_array[$current_key];
        $total_page = $pdfoption['total-pages']+1 ;
        $dataarray = fgpdf_sanitise_special_characters($dataarray);
        try {
        // initiate FPDI 
        $pdf = new FPDI(); 
        // set the sourcefile
        $pdf->setSourceFile($fgpdf_page_att_options['fgpdf_select_attachment_id']); 
        $same = 1;
        $filecounter = 0;
        $canvascounter = 0;
        $tplIdx = $pdf->importPage(1);
        $pdf->AddPage(); 
        $pdf->useTemplate($tplIdx, 0, 0); 
        for($k=1; $k<$total_page; $k++){
            foreach ($input_types as $key => $input) {
            if($pdfoption['pageN'.$key] == $k){
                if($same != $pdfoption['pageN'.$key]){
                $tplIdx = $pdf->importPage($k);
                $pdf->AddPage();
                $pdf->useTemplate($tplIdx, 0, 0); 
                }
            $same=$pdfoption['pageN'.$key];
            switch($input['type']){
                case 'text':
                case 'email':
                case 'password':
                case 'textarea':
                case 'date':
                case 'number':
                case 'tel':
                case 'time':
                case 'url':
                    // now write some text above the imported page 
                    if($pdfoption['x'.$key] && $pdfoption['y'.$key]){
                        $pdf->SetFont($pdfoption['font-family'.$key], $pdfoption["font-weight".$key.'B'].$pdfoption["font-weight".$key.'I'].$pdfoption["font-weight".$key.'U'], $pdfoption['font-size'.$key]); 
                        list($r, $g, $b) = sscanf($pdfoption["color".$key], "#%02x%02x%02x");
                        $pdf->SetTextColor($r,$g,$b); 
                        $effectivePosx = (($pdfoption['x'.$key]*785)/485)*0.264583333 - 1; 
                        $effectivePosy = (($pdfoption['y'.$key]*1007.5)/621)*0.264583333 - 3; 
                        $pdf->SetXY($effectivePosx, $effectivePosy);

                            if(isset($dataarray[$key+1]))
                                $data = $dataarray[$key+1];
                            elseif(isset($dataarray['status']))
                                $data = 'Element '.($key+1);

                        if($input['type'] == 'textarea' && $pdfoption['width'.$key]){
                            $pdf->MultiCell((($pdfoption['width'.$key]*785)/485)*0.264583333,5,$data, 0, $pdfoption['text-alignment'.$key]);
                        }
                        else{
                            $pdf->MultiCell(0,5,$data, 0, $pdfoption['text-alignment'.$key]);
                        }

                    }
                    break;
                case 'radio':
                case 'checkbox':
                    if($pdfoption['x'.$key] && $pdfoption['y'.$key]){
                        if(isset($dataarray[$key+1])){
                            if($dataarray[$key+1] == 'checked'){                
                                $effectivePosx = (($pdfoption['x'.$key]*800)/500)*0.264583333 - 1; 
                                $effectivePosy = (($pdfoption['y'.$key]*990)/615)*0.264583333 - 2;
                                $pdf->Cell(5,5,$pdf->Image(plugin_dir_path( __FILE__ ) . "images/icon1.png", $effectivePosx, $effectivePosy, 4.23), 0, 0, 'L', false );
                            }
                        }
                        elseif(isset($dataarray['status'])){
                                $effectivePosx = (($pdfoption['x'.$key]*800)/500)*0.264583333 - 1; 
                                $effectivePosy = (($pdfoption['y'.$key]*990)/615)*0.264583333 - 2;
                                $pdf->Cell(5,5,$pdf->Image(plugin_dir_path( __FILE__ ) . "images/icon1.png", $effectivePosx, $effectivePosy, 4.23), 0, 0, 'L', false );
                        }
                    }
                  break;
                case 'select':
                      if(isset($dataarray[$key+1])){
                          foreach ($input['inputValue'] as $i => $value) {
                            if($pdfoption['x'.$key.$i] && $pdfoption['y'.$key.$i]){
                              if($dataarray[$key+1] == $i){
                                  $effectivePosx = (($pdfoption['x'.$key.$i]*800)/500)*0.264583333 - 1;  
                                  $effectivePosy = (($pdfoption['y'.$key.$i]*990)/615)*0.264583333 - 2;
                                  $pdf->Cell(5,5,$pdf->Image(plugin_dir_path( __FILE__ ) . "images/icon1.png", $effectivePosx, $effectivePosy, 4.23), 0, 0, 'L', false );
                              }
                            }
                          }
                      }
                      elseif(isset($dataarray['status']) ){
                        foreach ($input['inputValue'] as $i => $value) {
                            if($pdfoption['x'.$key.$i] && $pdfoption['y'.$key.$i]){
                              if($dataarray['status'] == 'test'){
                                  $effectivePosx = (($pdfoption['x'.$key.$i]*800)/500)*0.264583333 - 1;  
                                  $effectivePosy = (($pdfoption['y'.$key.$i]*990)/615)*0.264583333 - 2;
                                  $pdf->Cell(5,5,$pdf->Image(plugin_dir_path( __FILE__ ) . "images/icon1.png", $effectivePosx, $effectivePosy, 4.23), 0, 0, 'L', false );
                              }
                            }
                          }

                      }
                    break;
                case 'file':

                    if($pdfoption['x1'] && $pdfoption['y1'] && $pdfoption['larg'.$key] && $pdfoption['haut'.$key]){
                        if($postedFiles !== array()){
                            // check if the file is an image by using $postedFiles['type'][$filecounter]
                           move_uploaded_file($postedFiles['tmp_name'][$filecounter], plugin_dir_path( __FILE__ ) . 'uploads/' . $postedFiles['name'][$filecounter]);
                            $effectivePosx = (($pdfoption['x'.$key]*800)/500)*0.264583333 - 1; 
                            $effectivePosy = (($pdfoption['y'.$key]*990)/615)*0.264583333 - 2;
                            $largeur       = (($pdfoption['larg'.$key]*800)/500)*0.264583333 - 1;
                            $hauteur       = (($pdfoption['haut'.$key]*990)/615)*0.264583333 - 2;
                            $pdf->Cell($largeur, $hauteur, $pdf->Image(plugin_dir_path( __FILE__ ) . 'uploads/' . $postedFiles['name'][$filecounter], $effectivePosx, $effectivePosy, $largeur, $hauteur), 0, 0, 'L', false );
                            $filecounter++;
                        }
                        elseif(isset($dataarray['status'])){
                                $effectivePosx = (($pdfoption['x'.$key]*800)/500)*0.264583333 - 1; 
                                $effectivePosy = (($pdfoption['y'.$key]*990)/615)*0.264583333 - 2;
                                $largeur       = (($pdfoption['larg'.$key]*800)/500)*0.264583333 - 1;
                                $hauteur       = (($pdfoption['haut'.$key]*990)/615)*0.264583333 - 2;
                                $pdf->Cell($largeur,$hauteur,$pdf->Image(plugin_dir_path( __FILE__ ) . "images/Bold.png", $effectivePosx, $effectivePosy, $largeur, $hauteur), 0, 0, 'L', false );
                        }
                    }
                    break;
                case 'signature':

                    if($pdfoption['x1'] && $pdfoption['y1'] && $pdfoption['larg'.$key] && $pdfoption['haut'.$key]){
                        if($canvas !== array()){
                            
                            $img = $canvas[$canvascounter];
                            $canvascounter++;
                            $img = str_replace('data:image/png;base64,', '', $img);
                            $img = str_replace(' ', '+', $img);
                            $fileData = base64_decode($img);
                            //saving
                            $fileName = plugin_dir_path( __FILE__ ) . 'uploads/'.fgpdf_generateRandomString().'.png';
                            file_put_contents($fileName, $fileData);
                            $effectivePosx = (($pdfoption['x'.$key]*800)/500)*0.264583333 - 1; 
                            $effectivePosy = (($pdfoption['y'.$key]*990)/615)*0.264583333 - 2;
                            $largeur       = (($pdfoption['larg'.$key]*800)/500)*0.264583333 - 1;
                            $hauteur       = (($pdfoption['haut'.$key]*990)/615)*0.264583333 - 2;
                            $pdf->Cell($largeur, $hauteur, $pdf->Image($fileName, $effectivePosx, $effectivePosy, $largeur, $hauteur), 0, 0, 'L', false );
                        }
                        if(isset($dataarray['status'])){
                            $effectivePosx = (($pdfoption['x'.$key]*800)/500)*0.264583333 - 1; 
                            $effectivePosy = (($pdfoption['y'.$key]*990)/615)*0.264583333 - 2;
                            $largeur       = (($pdfoption['larg'.$key]*800)/500)*0.264583333 - 1;
                            $hauteur       = (($pdfoption['haut'.$key]*990)/615)*0.264583333 - 2;
                            $pdf->Cell($largeur,$hauteur,$pdf->Image(plugin_dir_path( __FILE__ ) . "images/Bold.png", $effectivePosx, $effectivePosy, $largeur,$hauteur), 0, 0, 'L', false );
                        }
                    }
                    break;



                } // switch
            }// if
            }// foreach
        }// for

        return array('output' => $pdf->Output('newpdf.pdf', 'S'), 'error' => ''); 
        } catch (Exception $e) {

            $exception = "<b>Caught exception: </b>\n<blockquote>"
            .$e->getMessage()
            ."</blockquote>"
            ."\n"."on line <b>"
            .$e->getLine()
            ."</b> of <i>"
            .$e->getFile()
            ."</i>";


        return array('output' => '', 'error' => $exception);
        }

    }

}



if (!function_exists('fgpdf_test_mail')) {

    function fgpdf_test_mail($to_email, $subject, $message, $postedata, $postedFiles, $canvas, $attachmentName, $current_key) {
        if (!fgpdf_credentials_configured()) {
            return;
        }
        $errors = '';

        $swpsmtp_options = get_option('swpsmtp_options');

        require_once( ABSPATH . WPINC . '/class-phpmailer.php' );
        $mail = new PHPMailer();

        $charset = get_bloginfo('charset');
        $mail->CharSet = $charset;

        $from_name = $swpsmtp_options['from_name_field'];
        $from_email = $swpsmtp_options['from_email_field'];

        $mail->IsSMTP();

        /* If using smtp auth, set the username & password */
        if ('yes' == $swpsmtp_options['smtp_settings']['autentication']) {
            $mail->SMTPAuth = true;
            $mail->Username = $swpsmtp_options['smtp_settings']['username'];
            $mail->Password = fgpdf_get_password();
        }

        /* Set the SMTPSecure value, if set to none, leave this blank */
        if ($swpsmtp_options['smtp_settings']['type_encryption'] !== 'none') {
            $mail->SMTPSecure = $swpsmtp_options['smtp_settings']['type_encryption'];
        }

        /* PHPMailer 5.2.10 introduced this option. However, this might cause issues if the server is advertising TLS with an invalid certificate. */
        $mail->SMTPAutoTLS = false;

        /* Set the other options */
        $mail->Host = $swpsmtp_options['smtp_settings']['host'];
        $mail->Port = $swpsmtp_options['smtp_settings']['port'];
        $mail->SetFrom($from_email, $from_name);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->MsgHTML($message);
        $mail->AddAddress($to_email);
        $mail->SMTPDebug = 0;
        $attachment = fgpdf_get_attachment($current_key, $postedata, $postedFiles, $canvas);
        if($attachment['error'] !== ''){
            $errors = $attachment['error'];
        }else{
            $mail->AddStringAttachment($attachment['output'], $attachmentName.'.pdf');
        }

        /* Send mail and return result */
        if (!$mail->Send())
            $errors = $mail->ErrorInfo;
        
        $mail->ClearAddresses();
        $mail->ClearAllRecipients();

        if (!empty($errors)) {
            return $errors;
        } else {
            return 'Test mail was sent';
        }
    }

}

if (!function_exists('fgpdf_get_password')) {

    function fgpdf_get_password() {
        $swpsmtp_options = get_option('swpsmtp_options');
        $temp_password = $swpsmtp_options['smtp_settings']['password'];
        $password = "";
        $decoded_pass = base64_decode($temp_password);
        /* no additional checks for servers that aren't configured with mbstring enabled */
        if (!function_exists('mb_detect_encoding')) {
            return $decoded_pass;
        }
        /* end of mbstring check */
        if (base64_encode($decoded_pass) === $temp_password) {  //it might be encoded
            if (false === mb_detect_encoding($decoded_pass)) {  //could not find character encoding.
                $password = $temp_password;
            } else {
                $password = base64_decode($temp_password);
            }
        } else { //not encoded
            $password = $temp_password;
        }
        return $password;
    }

}

if (!function_exists('fgpdf_credentials_configured')) {

    function fgpdf_credentials_configured() {
        $swpsmtp_options = get_option('swpsmtp_options');
        $credentials_configured = true;
        if (!isset($swpsmtp_options['from_email_field']) || empty($swpsmtp_options['from_email_field'])) {
            $credentials_configured = false;
        }
        if (!isset($swpsmtp_options['from_name_field']) || empty($swpsmtp_options['from_name_field'])) {
            $credentials_configured = false;
            ;
        }
        return $credentials_configured;
    }

}

/**
 * Performed at uninstal.
 * @return void
 */
if (!function_exists('fgpdf_send_uninstall')) {

    function fgpdf_send_uninstall() {
        /* delete plugin options */
        delete_site_option('swpsmtp_options');
        delete_option('swpsmtp_options');

        delete_site_option('fgpdf_page_att_options');
        delete_option('fgpdf_page_att_options');

        delete_site_option('fgpdf_input_properties_options');
        delete_option('fgpdf_input_properties_options');

    }

}




/**
 * Add all hooks
 */
//add_filter('plugin_action_links', 'swpsmtp_plugin_action_links', 10, 2);
add_action('plugins_loaded', 'fgpdf_plugins_loaded_handler');
//add_filter('plugin_row_meta', 'swpsmtp_register_plugin_links', 10, 2);

add_action('phpmailer_init', 'fgpdf_init_smtp');

//add_action('admin_menu', 'swpsmtp_admin_default_setup');

add_action('admin_init', 'fgpdf_admin_init');
add_action('admin_enqueue_scripts', 'fgpdf_admin_head');
//add_action('admin_notices', 'swpsmtp_admin_notice');

register_uninstall_hook(plugin_basename(__FILE__), 'fgpdf_send_uninstall');