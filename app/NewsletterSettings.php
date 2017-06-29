<?php

namespace Nanga;

final class NewsletterSettings
{

    protected $config;

    private function __construct()
    {
        $defaultConfig = [
            'endpoint' => get_option('options_nanga_newsletter_endpoint'),
            'key'      => get_option('options_nanga_newsletter_api_key'),
            'list'     => get_option('options_nanga_newsletter_list_id'),
            'provider' => get_option('options_nanga_newsletter_provider', 'sendy'),
        ];
        $this->config  = $defaultConfig;
        add_action('admin_notices', [$this, 'notices']);
        add_action('acf/init', [$this, 'settingsPage']);
        add_action('acf/init', [$this, 'settingsFields']);
        add_filter('nanga_settings_tabs', [$this, 'settingsTab']);
        //add_filter('get_user_option_screen_layout_toplevel_page_newsletter-settings', '__return_true');
    }

    public static function instance()
    {
        static $instance = false;
        if ($instance === false) {
            $instance = new static();
        }

        return $instance;
    }

    public function setConfiguration($config)
    {
        if ( ! empty($config)) {
            $this->config = array_replace($this->config, $config);
        }
    }

    public function getConfiguration()
    {
        return $this->config;
    }

    public function notices()
    {
        if ($this->valid()) {
            return;
        }
        echo '<div class="notice notice-error"><p><strong>Newsletter Form</strong> configuration is not complete. Things will not work as expected.</p></div>';
    }

    public function valid()
    {
        if ($this->getProvider() == 'sendy') {
            if ($this->getList() && $this->getEndpoint()) {
                return true;
            }
        }
        if ($this->getProvider() == 'mailchimp') {
            if ($this->getList() && $this->getKey()) {
                return true;
            }
        }

        return false;
    }

    public function getProvider()
    {
        return $this->config['provider'];
    }

    public function getList()
    {
        return $this->config['list'];
    }

    public function getEndpoint()
    {
        return $this->config['endpoint'];
    }

    public function getKey()
    {
        return $this->config['key'];
    }

    public function settingsPage()
    {
        if (function_exists('acf_add_options_sub_page')) {
            acf_add_options_sub_page([
                'capability'  => 'manage_options',
                'icon_url'    => 'dashicons-hammer',
                'menu_slug'   => 'newsletter-settings',
                'menu_title'  => 'Newsletter Form',
                'page_title'  => 'Newsletter Form Configuration',
                'parent_slug' => 'options-general.php',
                'position'    => false,
                'redirect'    => false,
            ]);
        }
    }

    public function settingsFields()
    {
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group([
                'key'                   => 'group_nanga_newsletter_settings',
                'title'                 => '&nbsp;',
                'fields'                => [
                    [
                        'key'               => 'field_nanga_newsletter_provider',
                        'label'             => 'Provider',
                        'name'              => 'nanga_newsletter_provider',
                        'type'              => 'select',
                        'instructions'      => '',
                        'required'          => 0,
                        'conditional_logic' => 0,
                        'wrapper'           => [
                            'width' => '50',
                            'class' => '',
                            'id'    => '',
                        ],
                        'choices'           => [
                            'sendy'     => 'Sendy',
                            'mailchimp' => 'MailChimp',
                        ],
                        'default_value'     => ['sendy'],
                        'allow_null'        => 0,
                        'multiple'          => 0,
                        'ui'                => 0,
                        'ajax'              => 0,
                        'return_format'     => 'value',
                        'placeholder'       => '',
                    ],
                    [
                        'key'               => 'field_nanga_newsletter_list_id',
                        'label'             => 'List ID',
                        'name'              => 'nanga_newsletter_list_id',
                        'type'              => 'text',
                        'instructions'      => '',
                        'required'          => 0,
                        'conditional_logic' => 0,
                        'wrapper'           => [
                            'width' => '50',
                            'class' => '',
                            'id'    => '',
                        ],
                        'default_value'     => '',
                        'placeholder'       => '',
                        'prepend'           => '',
                        'append'            => '',
                        'maxlength'         => '',
                    ],
                    [
                        'key'               => 'field_nanga_newsletter_api_key',
                        'label'             => 'API Key',
                        'name'              => 'nanga_newsletter_api_key',
                        'type'              => 'text',
                        'instructions'      => 'if applicable',
                        'required'          => 0,
                        'conditional_logic' => 0,
                        'wrapper'           => [
                            'width' => '50',
                            'class' => '',
                            'id'    => '',
                        ],
                        'default_value'     => '',
                        'placeholder'       => '',
                        'prepend'           => '',
                        'append'            => '',
                        'maxlength'         => '',
                    ],
                    [
                        'key'               => 'field_nanga_newsletter_endpoint',
                        'label'             => 'Endpoint',
                        'name'              => 'nanga_newsletter_endpoint',
                        'type'              => 'url',
                        'instructions'      => 'if applicable',
                        'required'          => 0,
                        'conditional_logic' => 0,
                        'wrapper'           => [
                            'width' => '50',
                            'class' => '',
                            'id'    => '',
                        ],
                        'default_value'     => '',
                        'placeholder'       => '',
                    ],
                    [
                        'key'               => 'field_nanga_newsletter_help',
                        'label'             => 'Usage/Help',
                        'name'              => '',
                        'type'              => 'message',
                        'instructions'      => '',
                        'required'          => 0,
                        'conditional_logic' => 0,
                        'wrapper'           => [
                            'width' => '',
                            'class' => '',
                            'id'    => '',
                        ],
                        'message'           => 'You can find usage instructions here: <a href="https://github.com/Mallinanga/nanga-newsletter" target="_blank">https://github.com/Mallinanga/nanga-newsletter</a>',
                        'new_lines'         => 'br',
                        'esc_html'          => 0,
                    ],
                ],
                'location'              => [
                    [
                        [
                            'param'    => 'options_page',
                            'operator' => '==',
                            'value'    => 'newsletter-settings',
                        ],
                    ],
                ],
                'menu_order'            => 0,
                'position'              => 'acf_after_title',
                'style'                 => 'default',
                'label_placement'       => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen'        => '',
                'active'                => 1,
                'description'           => '',
            ]);
        }
    }

    public function settingsTab($tabs)
    {
        $tabs['newsletter'] = [
            'icon'  => 'dashicons-email',
            'show'  => true,
            'slug'  => 'newsletter',
            'title' => 'Newsletter',
        ];

        return $tabs;
    }

    private function __clone()
    {
    }

    private function __sleep()
    {
    }

    private function __wakeup()
    {
    }
}
