<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit460fb35cdacae4b4cf3bc180b8ddca4a
{
    public static $prefixLengthsPsr4 = array (
        'i' => 
        array (
            'iutnc\\deefy\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'iutnc\\deefy\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $fallbackDirsPsr4 = array (
        0 => __DIR__ . '/../..' . '/',
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit460fb35cdacae4b4cf3bc180b8ddca4a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit460fb35cdacae4b4cf3bc180b8ddca4a::$prefixDirsPsr4;
            $loader->fallbackDirsPsr4 = ComposerStaticInit460fb35cdacae4b4cf3bc180b8ddca4a::$fallbackDirsPsr4;
            $loader->classMap = ComposerStaticInit460fb35cdacae4b4cf3bc180b8ddca4a::$classMap;

        }, null, ClassLoader::class);
    }
}
