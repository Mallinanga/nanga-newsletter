<?php
namespace Nanga;

use DrewM\MailChimp\MailChimp;

class Newsletter
{

    private function __construct()
    {
        $newsletterSettings = NewsletterSettings::instance();
        if ($newsletterSettings->valid()) {
            add_action('wp_enqueue_scripts', [$this, 'assets']);
            add_action('wp_ajax_nopriv_nanga_newsletter', [$this, 'handle']);
            add_action('wp_ajax_nanga_newsletter', [$this, 'handle']);
        }
    }

    public static function instance()
    {
        static $instance = false;
        if ($instance === false) {
            $instance = new static();
        }

        return $instance;
    }

    public static function form($options)
    {
        $newsletterSettings = NewsletterSettings::instance();
        if ( ! $newsletterSettings->valid()) {
            return false;
        }

        return '<form class="nanga-newsletter' . (isset($options['class']) ? ' ' . $options['class'] : null) . '"><div class="form__message"></div><div class="form__fields"><input type="email" name="email" placeholder="' . (isset($options['placeholder']) ? $options['placeholder'] : 'Email') . '" required="required"><button type="submit">' . (isset($options['button']) ? $options['button'] : 'ΕΓΓΡΑΦΗ') . '</button></div>' . wp_nonce_field('nanga_newsletter', 'nanga_newsletter', false, false) . '</form>';
    }

    public function assets()
    {
        wp_enqueue_script('nanga-newsletter', get_template_directory_uri() . '/vendor/nanga/nanga-newsletter/assets/js/nanga-newsletter.js', ['jquery'], null, true);
        wp_localize_script('nanga-newsletter', 'nangaNewsletter', ['endpoint' => admin_url('admin-ajax.php')]);
    }

    public function handle()
    {
        if ( ! $this->validate($_REQUEST)) {
            wp_send_json_error(apply_filters('nanga_newsletter_error_message', 'Something went wrong.'));
        }
        $newsletterSettings = NewsletterSettings::instance();
        if ($newsletterSettings->getProvider() == 'sendy') {
            $request        = http_build_query([
                'email'   => $_REQUEST['email'],
                'list'    => $newsletterSettings->getList(),
                'boolean' => 'true',
            ]);
            $contextOptions = [
                'http' => [
                    'method'  => 'POST',
                    'header'  => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $request,
                ],
            ];
            $context        = stream_context_create($contextOptions);
            $response       = file_get_contents($newsletterSettings->getEndpoint() . '/subscribe', false, $context);
            if ($response === '1') {
                $response = 'Thanks for subscribing.';
            }
        }
        if ($newsletterSettings->getProvider() == 'mailchimp') {
            $provider = new MailChimp($newsletterSettings->getKey());
            $listId   = $newsletterSettings->getList();
            $response = $provider->post("lists/$listId/members", [
                'email_address' => $_REQUEST['email'],
                'status'        => 'subscribed',
            ]);
            write_log($response);
            $response = 'Thanks for subscribing.';
        }
        wp_send_json_success(apply_filters('nanga_newsletter_thanks_message', strtoupper($response)));
    }

    private function validate($fields)
    {
        if ( ! wp_verify_nonce($fields['nanga_newsletter'], 'nanga_newsletter')) {
            return false;
        }
        if ( ! isset($fields['email'])) {
            return false;
        }
        if ( ! filter_var($fields['email'], FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
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
