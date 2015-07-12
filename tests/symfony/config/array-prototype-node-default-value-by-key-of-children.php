<?php
// array-prototype-node-default-value-by-key-of-children.php

$loader = require_once __DIR__.'/autoload.php';

use Symfony\Component\Config\Definition\Builder\TreeBuilder; 
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Parser as YamlParser;

$builder = new TreeBuilder();

$configs = array();
$parser = new YamlParser();
$files = array(
    __DIR__.'/data/yml/array-prototype-node-default-value-by-key-of-children.yml',
);

$rootName = 'app';
$root = $builder->root($rootName);
$root
    ->fixXmlConfig('task')
    ->children()
        ->arrayNode('tasks')
            ->addDefaultChildrenIfNoneSet(array('task1', 'task2')) // 此时参数必须是数组，每个数组的元素代表一个子节点的key
            ->useAttributeAsKey('any text')
            // 并且此时必须调用useAttributeAsKey，参数可以是任何非空的字符串
            // 此时useAttributeAsKey的语义其实已经发生了变化，它的作用只是告诉系统应该为
            // 默认的子节点分配key，而key的来源并不是子节点自身的数据，而是来源于我们提供的数组
            ->prototype('array')
                ->children()
                    ->scalarNode('name')->defaultValue('task.sleep')->end()
                    ->scalarNode('type')->defaultValue('sleep')->end()
                    ->scalarNode('timeout')->defaultValue(300)->end()
                ->end()
        ->end()
    ->end();

/*
-- array-prototype-node-default-value-by-key-of-children.yml --
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
