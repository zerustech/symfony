<?php
// array-prototype.php

$loader = require_once __DIR__.'/autoload.php';

use Symfony\Component\Config\Definition\Builder\TreeBuilder; 
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Parser as YamlParser;

$builder = new TreeBuilder();

$configs = array();
$parser = new YamlParser();
$files = array(
    __DIR__.'/data/yml/array-prototype.yml',
);

$rootName = 'app';
$root = $builder->root($rootName);
$root
    ->children()
        ->arrayNode('hosts')
            ->prototype('array')
            // 创建一个array类型的prototype节点
            // prototype节点没有名称，只有类型。
            // 此处的'array'为类型名。'array' => ArrayNodeDefinition 
            // 这个prototype节点将被用作parameter数组所有子节点的模版
                ->children()
                    ->scalarNode('ip')->end()
                    ->scalarNode('username')->end()
                    ->scalarNode('password')->end()
                ->end()
        ->end()
    ->end();

/*
-- array-prototype.yml --
app:
    hosts:
        - ip: 192.168.1.2
          username: admin
          password: 4xx63TC61
        - ip: 192.168.1.3
          username: admin
          password: 9h0HO13u9
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
