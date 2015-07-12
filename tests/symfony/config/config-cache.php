<?php
// config-cache.php

$loader = require_once __DIR__.'/autoload.php';
$loader->addPsr4('ZerusTech\\Tutorial\\Symfony\\Component\\Config\\', __DIR__.'/classes/ZerusTech/Tutorial/Symfony/Component/Config');

use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Resource\FileResource;
use ZerusTech\Tutorial\Symfony\Component\Config\Loader\XmlFileLoader;
use ZerusTech\Tutorial\Symfony\Component\Config\Loader\YamlFileLoader;

$cachePath = __DIR__.'/cache/app.config.cache.php';

$cache = new ConfigCache($cachePath, true);

if (!$cache->isFresh()) {

    $builder = new TreeBuilder();
    $configs = array();
    $rootName = 'app';
    $root = $builder->root($rootName);
    $root
        ->fixXmlConfig('user')
        ->fixXmlConfig('host')
        ->children()
            ->arrayNode('database')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('driver')->defaultValue('mysql')->end()
                    ->scalarNode('host')->defaultValue('127.0.0.1')->end()
                    ->scalarNode('dbname')->defaultValue('symfony')->end()
                    ->scalarNode('username')->defaultValue('symfony')->end()
                    ->scalarNode('password')->defaultValue('symfony')->end()
                    ->scalarNode('charset')->defaultValue('UTF8')->end()
                    ->scalarNode('collate')->defaultValue('utf8-general-ci')->end()
                ->end()
            ->end() 
            ->arrayNode('users')
                ->prototype('array')
                    ->children()
                        ->scalarNode('login')->end()
                        ->scalarNode('email')->end()
                        ->scalarNode('group')->end()
                    ->end()
                ->end()
            ->end()
            ->arrayNode('hosts')
                ->prototype('array')
                    ->children()
                        ->scalarNode('name')->end()
                        ->scalarNode('ip')->end()
                        ->scalarNode('username')->end()
                        ->scalarNode('password')->end()
                    ->end()
                ->end()
            ->end()
        ->end();

    $loaderResolver = new LoaderResolver(
        array(
            new YamlFileLoader(new FileLocator(__DIR__.'/data/yml')),
            new XmlFileLoader(new FileLocator(__DIR__.'/data/xml'))
        )
    );

    $delegatingLoader = new DelegatingLoader($loaderResolver);

    $files = array(
        __DIR__.'/data/yml/yaml-file-loader.yml',
        __DIR__.'/data/xml/xml-file-loader.xml',
    );

    $configs = array();
    $metadata = array();
    foreach ($files as $file) {
        $metadata[] = new FileResource($file);
        $config = $delegatingLoader->load($file);
        $configs[] = $config['app'];
    }

    $processor = new Processor();
    $tree = $builder->buildTree();
    $result = $processor->process($tree, $configs);

    $code = sprintf(<<<EOF
<?php
    return %s;
EOF
    ,var_export($result, true));

    $cache->write($code, $metadata);

}

$configs = require $cachePath;

print_r($configs);
