<?php  // MyPlugin - Settings Page

// exit if file is called directly
if( !defined('ABSPATH')){
    exit;
}

// display the plugin settings  page
function myplugin_display_setings_page(){
    //check if user is allowed to access the page
    if(!current_user_can('manage_options')) return;
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form method="post" action="options.php">
            <?php
            //output security fields
            settings_fields('myplugin_options');

            //output setting sections
            do_settings_sections('myplugin');

            //submit button
            submit_button();
            ?>
        </form>
    </div>
    <?php
}
