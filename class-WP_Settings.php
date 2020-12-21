<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WP_Settings' ) ):
    class WP_Settings {

        private $settings_api;

        function __construct() {
            $this->settings_api = new WP_Settings_API();

            add_action( 'admin_init', array( $this, 'admin_init' ) );
            add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        }

        function admin_init() {
            //set the settings
            $this->settings_api->set_sections( $this->get_settings_sections() );
            $this->settings_api->set_fields( $this->get_settings_fields() );

            //initialize settings
            $this->settings_api->admin_init();
        }

        function admin_menu() {
            add_options_page( 'WP Settings Page', 'WP Settings Page', 'manage_options', 'settings_page_test', array(
                $this,
                'plugin_page'
            ) );
        }

        function get_settings_sections() {
            $sections = array(
                array(
                    'id'    => 'wpxpress_basics',
                    'title' => __( 'Basic Settings', 'wpxpress' )
                ),
                array(
                    'id'    => 'wpxpress_advanced',
                    'title' => __( 'Advanced Settings', 'wpxpress' )
                )
            );

            return $sections;
        }

        /**
         * Returns all the settings fields
         *
         * @return array settings fields
         */
        function get_settings_fields() {
            $settings_fields = array(
                'wpxpress_basics'   => array(
                    array(
                        'name'              => 'text_val',
                        'label'             => __( 'Text Input', 'wpxpress' ),
                        'desc'              => __( 'Text input description', 'wpxpress' ),
                        'placeholder'       => __( 'Text Input placeholder', 'wpxpress' ),
                        'type'              => 'text',
                        'default'           => 'Title',
                        'sanitize_callback' => 'sanitize_text_field'
                    ),
                    array(
                        'name'              => 'number_input',
                        'label'             => __( 'Number Input', 'wpxpress' ),
                        'desc'              => __( 'Number field with validation callback `floatval`', 'wpxpress' ),
                        'placeholder'       => __( '1.99', 'wpxpress' ),
                        'min'               => 0,
                        'max'               => 100,
                        'step'              => '0.01',
                        'type'              => 'number',
                        'default'           => 'Title',
                        'sanitize_callback' => 'floatval'
                    ),
                    array(
                        'name'        => 'textarea',
                        'label'       => __( 'Textarea Input', 'wpxpress' ),
                        'desc'        => __( 'Textarea description', 'wpxpress' ),
                        'placeholder' => __( 'Textarea placeholder', 'wpxpress' ),
                        'type'        => 'textarea'
                    ),
                    array(
                        'name' => 'html',
                        'desc' => __( 'HTML area description. You can use any <strong>bold</strong> or other HTML elements.', 'wpxpress' ),
                        'type' => 'html'
                    ),
                    array(
                        'name'  => 'checkbox',
                        'label' => __( 'Checkbox', 'wpxpress' ),
                        'desc'  => __( 'Checkbox Label', 'wpxpress' ),
                        'type'  => 'checkbox'
                    ),
                    array(
                        'name'    => 'radio',
                        'label'   => __( 'Radio Button', 'wpxpress' ),
                        'desc'    => __( 'A radio button', 'wpxpress' ),
                        'type'    => 'radio',
                        'options' => array(
                            'yes' => 'Yes',
                            'no'  => 'No'
                        )
                    ),
                    array(
                        'name'    => 'selectbox',
                        'label'   => __( 'A Dropdown', 'wpxpress' ),
                        'desc'    => __( 'Dropdown description', 'wpxpress' ),
                        'type'    => 'select',
                        'default' => 'no',
                        'options' => array(
                            'yes' => 'Yes',
                            'no'  => 'No'
                        )
                    ),
                    array(
                        'name'    => 'password',
                        'label'   => __( 'Password', 'wpxpress' ),
                        'desc'    => __( 'Password description', 'wpxpress' ),
                        'type'    => 'password',
                        'default' => ''
                    ),
                    array(
                        'name'    => 'file',
                        'label'   => __( 'File', 'wpxpress' ),
                        'desc'    => __( 'File description', 'wpxpress' ),
                        'type'    => 'file',
                        'default' => '',
                        'options' => array(
                            'button_label' => 'Choose Image'
                        )
                    )
                ),
                'wpxpress_advanced' => array(
                    array(
                        'name'    => 'color',
                        'label'   => __( 'Color', 'wpxpress' ),
                        'desc'    => __( 'Color description', 'wpxpress' ),
                        'type'    => 'color',
                        'default' => ''
                    ),
                    array(
                        'name'    => 'password',
                        'label'   => __( 'Password', 'wpxpress' ),
                        'desc'    => __( 'Password description', 'wpxpress' ),
                        'type'    => 'password',
                        'default' => ''
                    ),
                    array(
                        'name'    => 'wysiwyg',
                        'label'   => __( 'Advanced Editor', 'wpxpress' ),
                        'desc'    => __( 'WP_Editor description', 'wpxpress' ),
                        'type'    => 'wysiwyg',
                        'default' => ''
                    ),
                    array(
                        'name'    => 'multicheck',
                        'label'   => __( 'Multile checkbox', 'wpxpress' ),
                        'desc'    => __( 'Multi checkbox description', 'wpxpress' ),
                        'type'    => 'multicheck',
                        'default' => array( 'one' => 'one', 'four' => 'four' ),
                        'options' => array(
                            'one'   => 'One',
                            'two'   => 'Two',
                            'three' => 'Three',
                            'four'  => 'Four'
                        )
                    ),
                )
            );

            return $settings_fields;
        }

        function plugin_page() {
            echo '<div class="wrap">';

            $this->settings_api->show_navigation();
            $this->settings_api->show_forms();

            echo '</div>';
        }

        /**
         * Get all the pages
         *
         * @return array page names with key value pairs
         */
        function get_pages() {
            $pages         = get_pages();
            $pages_options = array();
            if ( $pages ) {
                foreach ( $pages as $page ) {
                    $pages_options[ $page->ID ] = $page->post_title;
                }
            }

            return $pages_options;
        }

    }
endif;

new WP_Settings();