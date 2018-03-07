<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitee3bc1a1903ae96c57d3db954b9ee95e
{
    public static $files = array (
        'fc73bab8d04e21bcdda37ca319c63800' => __DIR__ . '/..' . '/mikecao/flight/flight/autoload.php',
        '5b7d984aab5ae919d3362ad9588977eb' => __DIR__ . '/..' . '/mikecao/flight/flight/Flight.php',
    );

    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'Aura\\Session\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Aura\\Session\\' => 
        array (
            0 => __DIR__ . '/..' . '/aura/session/src',
        ),
    );

    public static $fallbackDirsPsr0 = array (
        0 => __DIR__ . '/../..' . '/src',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitee3bc1a1903ae96c57d3db954b9ee95e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitee3bc1a1903ae96c57d3db954b9ee95e::$prefixDirsPsr4;
            $loader->fallbackDirsPsr0 = ComposerStaticInitee3bc1a1903ae96c57d3db954b9ee95e::$fallbackDirsPsr0;

        }, null, ClassLoader::class);
    }
}