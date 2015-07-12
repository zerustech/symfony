<?php
// can-be-enabled.php

$loader = require_once __DIR__.'/autoload.php';

use Symfony\Component\Config\Definition\Builder\TreeBuilder; 
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Parser as YamlParser;

$builder = new TreeBuilder();

$configs = array();
$parser = new YamlParser();
$files = array(
    __DIR__.'/data/yml/can-be-enabled.yml',
);

$rootName = 'app';
$root = $builder->root($rootName);

$root
    ->children()
        ->arrayNode('database')
            ->canBeEnabled()
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
    ->end();

// 以上的代码与下面这段代码是等价的

/*
$root
    ->children()
        ->arrayNode('database')
            ->addDefaultsIfNotSet() 
            // 如果'database'没有出现在配置文件中，则为它设置默认值
            ->treatFalseLike(array('enabled' => false)) 
            // database: false 
            // => 
            // database:
            //     enabled: false
            //
            ->treatTrueLike(array('enabled' => true))
            // database: true
            // => 
            // database:
            //     enabled: true
            //
            ->treatNullLike(array('enabled' => true))
            // database:
            // => 
            // database:
            //     enabled: true
            //     这里的逻辑是，如果database出现在配置文件中，并且没有任何显式的设置，则启用争端配置
            ->beforeNormalization()
                ->ifArray()
                ->then(function($v){
                    $v['enabled'] = isset($v['enabled']) ? : true;
                    return $v;
                })
            // 这段closure的逻辑如下：
            // 如果database节点是数组，即我们至少显示地在配置文件中为database设置了一个子节点
            // 则：
            // 如果显示设置了enabled属性，则使用这个属性
            // 否则，将enabled设置为true。
            // 因为，我们认为既然已经显示地为它设置了子节点，
            // 则我们就希望启用这段配置
            // 即
            // database:
            //     enabled: true
            //     => 
            // database:
            //     enabled: true
            //
            //
            // database:
            //     enabled: false
            //     =>
            // database:
            //     enabled: false
            //
            //
            // database:
            //     driver: mysql
            // => 
            // database:
            //     driver: mysql
            //     enabled: true
            ->end()
            ->children()
                ->booleanNode('enabled')->defaultFalse()->end() // enabled的默认值为false
                ->scalarNode('driver')->defaultValue('mysql')->end()
                ->scalarNode('host')->defaultValue('127.0.0.1')->end()
                ->scalarNode('dbname')->defaultValue('symfony')->end()
                ->scalarNode('username')->defaultValue('symfony')->end()
                ->scalarNode('password')->defaultValue('symfony')->end()
                ->scalarNode('charset')->defaultValue('UTF8')->end()
                ->scalarNode('collate')->defaultValue('utf8-general-ci')->end()
            ->end()
        ->end()
    ->end();
*/

/*
-- can-be-enabled.yml --
app:
*/

foreach($files as $file)
{ 
    $config = $parser->parse(file_get_contents($file));
    $configs[] = $config[$rootName];
}

$processor = new Processor();
$tree = $builder->buildTree();
$result = $processor->process($tree, $configs);
print_r($result);
