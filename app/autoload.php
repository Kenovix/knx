<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/**
 * @var $loader ClassLoader
 */
$loader = require __DIR__.'/../vendor/autoload.php';

// intl
if (!function_exists('intl_get_error_code')) {
    require_once __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/Locale/Resources/stubs/functions.php';

    $loader->add('', __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/Locale/Resources/stubs');
}

include_once __DIR__.'/../vendor/yepsua/jquery4php/YepSua/Labs/RIA/jQuery4PHP/YsJQueryAutoloader.php';

YsJQueryAutoloader::register();

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

return $loader;
