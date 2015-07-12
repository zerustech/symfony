<?php
// attribute-as-key-without-value.php

$loader = require_once __DIR__.'/autoload.php';

use Symfony\Component\Config\Definition\Builder\TreeBuilder; 
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Parser as YamlParser;

$builder = new TreeBuilder();

$configs = array();
$parser = new YamlParser();
$files = array(
    __DIR__.'/data/yml/attribute-as-key-without-value.yml',
);

$rootName = 'app';
$root = $builder->root($rootName);
$root
    ->fixXmlConfig('host')
    ->children()
        ->arrayNode('hosts')
            ->useAttributeAsKey('name')
            ->prototype('array')
                ->children()
                    ->scalarNode('name')->end()
                    ->scalarNode('ip')->end()
                    ->scalarNode('username')->end()
                    ->scalarNode('password')->end()
                ->end()
        ->end()
    ->end();

/*
-- attribute-as-key-without-value.yml --
app:
    hosts:
        - name: web.local
          ip: 192.168.1.2
          username: admin
          password: 4xx63TC61
        - name: db.local
          ip: 192.168.1.3
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
