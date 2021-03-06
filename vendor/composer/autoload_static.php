<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1fab326eda715b3bf465583be630434a
{
    public static $prefixLengthsPsr4 = array (
        'N' => 
        array (
            'Nanga\\' => 6,
        ),
        'D' => 
        array (
            'DrewM\\MailChimp\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Nanga\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
        'DrewM\\MailChimp\\' => 
        array (
            0 => __DIR__ . '/..' . '/drewm/mailchimp-api/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1fab326eda715b3bf465583be630434a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1fab326eda715b3bf465583be630434a::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
