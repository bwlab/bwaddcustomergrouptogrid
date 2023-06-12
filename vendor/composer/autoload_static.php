<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0d531b72a8596a5fd3744aff343f1bc7
{
    public static $prefixLengthsPsr4 = array (
        'B' => 
        array (
            'Bwlab\\CustomerGroup\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Bwlab\\CustomerGroup\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0d531b72a8596a5fd3744aff343f1bc7::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0d531b72a8596a5fd3744aff343f1bc7::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit0d531b72a8596a5fd3744aff343f1bc7::$classMap;

        }, null, ClassLoader::class);
    }
}