<?php
// array-prototype-node-default-value.php

$loader = require_once __DIR__.'/autoload.php';

use Symfony\Component\Config\Definition\Builder\TreeBuilder; 
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Parser as YamlParser;

$builder = new TreeBuilder();

$configs = array();
$parser = new YamlParser();
$files = array(
    __DIR__.'/data/yml/array-prototype-node-default-value.yml',
);

$rootName = 'app';
$root = $builder->root($rootName);
$root
    ->fixXmlConfig('host')
    ->children()
        ->arrayNode('hosts')
            // defaultValue()只能应用于prototype数组节点，并且它的参数必须也是数组
            ->defaultValue(
                array(
                    array( 'ip' => '127.0.0.1', 'username' => 'admin', 'password' => 'admin'),
                )
            )
            ->prototype('array')
                ->children()
                    ->scalarNode('ip')->end()
                    ->scalarNode('username')->end()
                    ->scalarNode('password')->end()
                ->end()
        ->end()
    ->end();

/*
-- array-prototype-node-default-value.yml --
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
