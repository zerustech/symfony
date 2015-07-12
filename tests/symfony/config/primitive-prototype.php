<?php
// primitive-prototype.php

$loader = require_once __DIR__.'/autoload.php';

use Symfony\Component\Config\Definition\Builder\TreeBuilder; 
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Parser as YamlParser;

$builder = new TreeBuilder();

$configs = array();
$parser = new YamlParser();
$files = array(
    __DIR__.'/data/yml/primitive-prototype.yml',
);

$rootName = 'app';
$root = $builder->root($rootName);
$root
    ->children()
        ->arrayNode('parameters')
            ->prototype('scalar')
            // 创建一个scalar类型的prototype节点
            // prototype节点没有名称，只有类型。
            // 此处的'scalar'为类型名。'scalar' => ScalarNodeDefinition 
            // 这个prototype节点将被用作parameter数组所有子节点的模版
        ->end()
    ->end();

/*
-- primitive-prototype.yml --
app:
    parameters:
        param1: value of param1
        param2: value of param2
        param3: value of param3
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
