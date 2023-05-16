<?php

class EnqueueAnything
{
    private $enqueue_anything_options;

    public function __construct()
    {
        add_action('admin_menu', array($this, 'enqueue_anything_add_plugin_page'));
        add_action('admin_init', array($this, 'enqueue_anything_page_init'));
    }

    public function enqueue_anything_add_plugin_page()
    {
        add_menu_page(
            'Enqueue Anything', // page_title
            'Enqueue Anything', // menu_title
            'manage_options', // capability
            'enqueue-anything', // menu_slug
            array($this, 'enqueue_anything_create_admin_page'), // function
            'dashicons-download', // icon_url
            60 // position
        );
    }

    public function enqueue_anything_create_admin_page()
    {
        $this->enqueue_anything_options = get_option('enqueue_anything_option_name'); ?>

        <div class="wrap">
            <h2>Enqueue Anything</h2>
            <p>Quickly add scripts, styles or fonts to your site</p>
            <?php settings_errors(); ?>
            <?php include 'partials/saved-table-page.php'; ?>
        </div>
    <?php }

    public function enqueue_anything_page_init()
    {
        register_setting(
            'enqueue_anything_option_group', // option_group
            'enqueue_anything_option_name', // option_name
            array($this, 'enqueue_anything_sanitize') // sanitize_callback
        );

        add_settings_section(
            'enqueue_anything_setting_section', // id
            'Settings', // title
            array($this, 'enqueue_anything_section_info'), // callback
            'enqueue-anything-admin' // page
        );

        add_settings_field(
            'name_of_asset', // id
            'Name of Asset', // title
            array($this, 'enqueue_anything_name_of_asset'), // callback
            'enqueue-anything-admin', // page
            'enqueue_anything_setting_section' // section
        );

        add_settings_field(
            'url_or_location_of_script_or_style', // id
            'Url or location of script or style', // title
            array($this, 'enqueue_anything_url_or_location_of_script_or_style'), // callback
            'enqueue-anything-admin', // page
            'enqueue_anything_setting_section' // section
        );
    }

    public function enqueue_anything_sanitize($input)
    {
        $sanitary_values = array();
        if (isset($input['name_of_asset'])) {
            $sanitary_values['name_of_asset'] = sanitize_text_field($input['name_of_asset']);
        }

        if (isset($input['url_or_location_of_script_or_style'])) {
            $sanitary_values['url_or_location_of_script_or_style'] = sanitize_text_field($input['url_or_location_of_script_or_style']);
        }

        return $sanitary_values;
    }

    public function enqueue_anything_section_info()
    {
    }

    public function enqueue_anything_name_of_asset()
    {
        printf(
            '<input class="regular-text" type="text" name="enqueue_anything_option_name[name_of_asset]" id="name_of_asset" value="%s">',
            isset($this->enqueue_anything_options['name_of_asset']) ? esc_attr($this->enqueue_anything_options['name_of_asset']) : ''
        );
    }

    public function enqueue_anything_url_or_location_of_script_or_style()
    {
        printf(
            '<input class="regular-text" type="text" name="enqueue_anything_option_name[url_or_location_of_script_or_style]" id="url_or_location_of_script_or_style" value="%s">',
            isset($this->enqueue_anything_options['url_or_location_of_script_or_style']) ? esc_attr($this->enqueue_anything_options['url_or_location_of_script_or_style']) : ''
        );
    }
}

if (is_admin()) {
    $enqueue_anything = new EnqueueAnything();
}

/**
 * Retrieve this value with:
 * $enqueue_anything_options = get_option( 'enqueue_anything_option_name' ); // Array of All Options
 * $name_of_asset = $enqueue_anything_options['name_of_asset']; // Name of Asset
 * $url_or_location_of_script_or_style = $enqueue_anything_options['url_or_location_of_script_or_style']; // Url or location of script or style
 * $in_footer_2 = $enqueue_anything_options['in_footer_2']; // In Footer
 **/