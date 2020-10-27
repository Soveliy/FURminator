<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit465156bac4075c76ebfeca8c27f86912
{
    public static $prefixLengthsPsr4 = array (
        'I' => 
        array (
            'Imagine\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Imagine\\' => 
        array (
            0 => __DIR__ . '/..' . '/imagine/imagine/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit465156bac4075c76ebfeca8c27f86912::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit465156bac4075c76ebfeca8c27f86912::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit465156bac4075c76ebfeca8c27f86912::$classMap;

        }, null, ClassLoader::class);
    }
}