<?php
// merge-array-prototype-nodes.php

$loader = require_once __DIR__.'/autoload.php';

use Symfony\Component\Config\Definition\Builder\TreeBuilder; 
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Parser as YamlParser;

$builder = new TreeBuilder();

$configs = array();
$parser = new YamlParser();
$files = array(
    __DIR__.'/data/yml/merge-array-prototype-nodes-left-values.yml',
    __DIR__.'/data/yml/merge-array-prototype-nodes-right-values.yml',
);

$rootName = 'app';
$root = $builder->root($rootName);
$root
    ->children()
        ->arrayNode('users')
            ->useAttributeAsKey('login')
            ->prototype('array')
                ->children()
                    ->scalarNode('login')->end()
                    ->scalarNode('email')->end()
                    ->scalarNode('group')->end()
                ->end()
        ->end()
    ->end();

/*
-- merge-array-prototype-nodes-left-values.yml --
app:
    users:
        - login: admin
          email: admin@localhost
          group: admin
        - login: staff
          email: staff@localhost
          group: staff

-- merge-array-prototype-nodes-right-values.yml --
app:
    users:
        - login: admin
          email: admin1@localhost
          group: admin
        - login: staff
          email: staff1@localhost
          group: staff

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
