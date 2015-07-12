<?php
// loader-resolver.php

$loader = require_once __DIR__.'/autoload.php';
$loader->addPsr4('ZerusTech\\Tutorial\\Symfony\\Component\\Config\\', __DIR__.'/classes/ZerusTech/Tutorial/Symfony/Component/Config');

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Config\Loader\DelegatingLoader;
use ZerusTech\Tutorial\Symfony\Component\Config\Loader\XmlFileLoader;
use ZerusTech\Tutorial\Symfony\Component\Config\Loader\YamlFileLoader;

$loaderResolver = new LoaderResolver(array(
    new YamlFileLoader(new FileLocator(array(__DIR__.'/data/yml'))),
    new XmlFileLoader(new FileLocator(array(__DIR__.'/data/xml'))),
));

$delegatingLoader = new DelegatingLoader($loaderResolver);

$files = array(
    __DIR__.'/data/yml/yaml-file-loader.yml',
    __DIR__.'/data/xml/xml-file-loader.xml',
);

$configs = array();

foreach ($files as $file) {
    $config = $delegatingLoader->load($file);
    $configs[] = $config['app'];
}

print_r($configs);
