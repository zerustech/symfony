<?php
// primitive-node-default-value.php

$loader = require_once __DIR__.'/autoload.php';

use Symfony\Component\Config\Definition\Builder\TreeBuilder; 
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Parser as YamlParser;

$builder = new TreeBuilder();

$configs = array();
$parser = new YamlParser();
$files = array(
    __DIR__.'/data/yml/primitive-node-default-value.yml',
);

$rootName = 'app';
$root = $builder->root($rootName);
$root
    ->children()
        ->arrayNode('database')
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

/*
-- primitive-node-default-value.yml --
app:
    database:
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
