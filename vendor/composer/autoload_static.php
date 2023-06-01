<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7dd773df3f677315ab58e43a10777809
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7dd773df3f677315ab58e43a10777809::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7dd773df3f677315ab58e43a10777809::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7dd773df3f677315ab58e43a10777809::$classMap;

        }, null, ClassLoader::class);
    }
}