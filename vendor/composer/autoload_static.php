<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite6e0c5488e217cbc2459710900013e24
{
    public static $prefixLengthsPsr4 = array (
        's' => 
        array (
            'setasign\\Fpdi\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'setasign\\Fpdi\\' => 
        array (
            0 => __DIR__ . '/..' . '/setasign/fpdi/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'FPDF' => __DIR__ . '/..' . '/setasign/fpdf/fpdf.php',
        'FPDM' => __DIR__ . '/..' . '/tmw/fpdm/src/fpdm.php',
        'FilterASCII85' => __DIR__ . '/..' . '/tmw/fpdm/src/filters/FilterASCII85.php',
        'FilterASCIIHex' => __DIR__ . '/..' . '/tmw/fpdm/src/filters/FilterASCIIHex.php',
        'FilterFlate' => __DIR__ . '/..' . '/tmw/fpdm/src/filters/FilterFlate.php',
        'FilterLZW' => __DIR__ . '/..' . '/tmw/fpdm/src/filters/FilterLZW.php',
        'FilterStandard' => __DIR__ . '/..' . '/tmw/fpdm/src/filters/FilterStandard.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite6e0c5488e217cbc2459710900013e24::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite6e0c5488e217cbc2459710900013e24::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInite6e0c5488e217cbc2459710900013e24::$classMap;

        }, null, ClassLoader::class);
    }
}
