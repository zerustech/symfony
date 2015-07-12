<?php
// array-node-no-deep-merging.php

$loader = require_once __DIR__.'/autoload.php';

use Symfony\Component\Config\Definition\Builder\TreeBuilder; 
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Parser as YamlParser;

$builder = new TreeBuilder();

$configs = array();
$parser = new YamlParser();
$files = array(
    __DIR__.'/data/yml/array-node-no-deep-merging-left-values.yml',
    __DIR__.'/data/yml/array-node-no-deep-merging-right-values.yml',
);

$rootName = 'app';
$root = $builder->root($rootName);
$root
    ->children()
        ->arrayNode('database')
            ->performNoDeepMerging() // 设置数组执行'右值'直接覆盖的合并方式
            ->children()
                ->scalarNode('driver')->defaultValue('pdo_mysql')->end()
                ->scalarNode('host')->defaultValue('127.0.0.1')->end()
                ->scalarNode('port')->defaultValue('3306')->end()
                ->scalarNode('dbname')->defaultValue('symfony')->end()
                ->scalarNode('user')->defaultValue('root')->end()
                ->scalarNode('password')->defaultValue(null)->end()
                ->scalarNode('charset')->defaultValue('UTF8')->end()
                ->scalarNode('collate')->defaultValue('utf8-general-ci')->end()
            ->end()
        ->end()
    ->end();

/*
-- array-node-no-deep-merging-left-values.yml --
app:
    database:
        host: 192.168.1.2
        dbname: symfony-server1
        user: symfony-server1
        password: symfony-server1-password

-- array-node-no-deep-merging-right-values.yml --
app:
    database:
        host: 192.168.1.3

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
