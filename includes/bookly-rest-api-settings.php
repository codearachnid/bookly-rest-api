<?php

/**
 * Used to add the plugin specific settings to the current theme.
 *
 * @since      1.0.0
 * @package    bookly
 * @subpackage Bookly_Rest_Api/includes
 * @author     PDG Solutions <info@pdg.solutions>
 */
class Bookly_Rest_Api_Settings
{
    /**
     * Add plugin necessary custom fields to collect specific data.
     * @param  mixed $wp_customize
     * @return void
     */
    public function addSettings($wp_customize)
    {
        $wp_customize->add_section('bookly_rest_api', [
          'title'       => 'Bookly REST API',
          'description' => esc_attr__('Setup your Bookly REST API instance.', 'bookly-api'),
          'priority'    => 120,
        ]);

        $wp_customize->add_setting('bookly_rest_api_purchased_code', [
          'default'    => '',
          'capability' => 'edit_theme_options'
        ]);

        $wp_customize->add_control('bookly_rest_api_purchased_code', [
          'label'    => esc_attr__('Envato Purchase Code', 'bookly-api'),
          'settings' => 'bookly_rest_api_purchased_code',
          'section'  => 'bookly_rest_api',
          'type'     => 'text'
        ]);
    }
}
