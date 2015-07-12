<?php
// array-prototype-node-default-value-by-number-of-children.php

$loader = require_once __DIR__.'/autoload.php';

use Symfony\Component\Config\Definition\Builder\TreeBuilder; 
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Parser as YamlParser;

$builder = new TreeBuilder();

$configs = array();
$parser = new YamlParser();
$files = array(
    __DIR__.'/data/yml/array-prototype-node-default-value-by-number-of-children.yml',
);

$rootName = 'app';
$root = $builder->root($rootName);
$root
    ->fixXmlConfig('task')
    ->children()
        ->arrayNode('tasks')
            ->addDefaultChildrenIfNoneSet(3) // 设置默认有3个子节点
            ->prototype('array')
                ->children()
                    ->scalarNode('name')->defaultValue('task.sleep')->end()
                    ->scalarNode('type')->defaultValue('sleep')->end()
                    ->scalarNode('timeout')->defaultValue(300)->end()
                ->end()
        ->end()
    ->end();

/*
-- array-prototype-node-default-value-by-number-of-children.yml --
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
