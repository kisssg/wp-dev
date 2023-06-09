<?php // MyPlugin Settings Callbacks

// exit if file is called directly
if( !defined('ABSPATH')){
    exit;
}

// callback: login section 
function myplugin_callback_section_login(){
    echo '<p>Customize login page</p>';
}

// callback: admin section
function myplugin_callback_section_admin(){
    echo '<p>Customize admin area</p>';
    
}

// callback: text field
function myplugin_callbacke_field_text($args){
    
    $options = get_option('myplugin_options',myplugin_options_default());

    $id = isset( $args['id'] ) ? $args['id'] : '';
    $label = isset( $args['label'] ) ? $args['label'] : '';
    $value = isset( $options[$id] ) ? sanitize_text_field( $options[$id] ) : '';

    echo '<input id="myplugin_options_'.$id.'" name="myplugin_options['.$id.']" 
    type="text" size="40" value="'.$value.'" /><br/>';
    echo '<label for="myplugin_options_'.$id.'">'.$label.'</label>';
}

// callbck: radio field
function myplugin_callbacke_field_radio($args){
   
    $options = get_option('myplugin_options',myplugin_options_default());
    $id = isset( $args['id'] ) ? $args['id'] : '';
    $label = isset( $args['label'] ) ? $args['label'] : '';

    $selected_option = isset( $options[$id] ) ? sanitize_text_field( $options[$id] ) : '';

    $radio_options = array(
        'enable' => 'Enable custom styles',
        'disable' => 'Disable custom styles',
    );

    foreach($radio_options as $value => $label){
        $checked = checked($selected_option === $value, true, false);

        echo '<label><input name="myplugin_options['.$id.']" type="radio" value="'.$value.'" '.$checked.' />';
        echo '<span>'.$label.'</span></label><br/>';
    }
}

//callback: textarea field
function myplugin_callbacke_field_textarea($args){
    
    $options = get_option('myplugin_options',myplugin_options_default());

    $id = isset( $args['id'] ) ? $args['id'] : '';
    $label = isset( $args['label'] ) ? $args['label'] : '';
    
    $allowed_tags = wp_kses_allowed_html('post');

    $value = isset( $options[$id] ) ? wp_kses( stripslashes_deep($options[$id]), $allowed_tags ) : '';

    echo '<textarea id="myplugin_options_'.$id.'" name="myplugin_options['.$id.']" value="'.$value.'"
    rows="5" cols="50">'.$value.'</textarea><br/>';
    echo '<label for="myplugin_options_'.$id.'">'.$label.'</label>';

}

//callback: checkbox field
function myplugin_callbacke_field_checkbox($args){
    
    $options = get_option('myplugin_options',myplugin_options_default());
    $id = isset( $args['id'] ) ? $args['id'] : '';
    $label = isset( $args['label'] ) ? $args['label'] : '';
    
    $checked = isset( $options[$id] ) ? checked( $options[$id], true, false ) : '';

    echo '<input id="myplugin_options_'.$id.'" name="myplugin_options['.$id.']"
     type="checkbox" value="1" '.$checked.' />';
    echo '<label for="myplugin_options_'.$id.'">'.$label.'</label>';
    
}

//callback: select field
function myplugin_callbacke_field_select($args){
    
    $options = get_option('myplugin_options',myplugin_options_default());

    $id = isset( $args['id'] ) ? $args['id'] : '';
    $label = isset( $args['label'] ) ? $args['label'] : '';

    $selected_option = isset( $options[$id] ) ? sanitize_text_field( $options[$id] ) : '';

    $select_options = array(
        'default' => 'Default',
        'light' => 'Light',
        'blue' => 'Blue',
        'coffee' => 'Cofee',
        'ectoplasm'=> 'Ectoplasm',
        'midnight' => 'Midnight',
        'ocean' => 'Ocean',
        'sunrize' => 'Sunrize',
    );

    echo '<select id="myplugin_options_'.$id.'" name="myplugin_options['.$id.']">';

    foreach ($select_options as $value => $options){
        $selected = selected($selected_option === $value, true, false);
        echo '<option value="'.$value.'" '.$selected.'>'.$options.'</option>';
    }

    echo '</select><label for="myplugin_options_'.$id.'">'.$label.'</label>';
}



