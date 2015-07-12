<?php
// merge-primitive-nodes.php

$loader = require_once __DIR__.'/autoload.php';

use Symfony\Component\Config\Definition\Builder\TreeBuilder; 
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Parser as YamlParser;

$builder = new TreeBuilder();

$configs = array();
$parser = new YamlParser();
$files = array(
    __DIR__.'/data/yml/merge-primitive-nodes-left-values.yml',
    __DIR__.'/data/yml/merge-primitive-nodes-right-values.yml',
);

$rootName = 'acl';
$root = $builder->root($rootName);
$root
    ->children()
        ->arrayNode('staff')
            ->children()
                ->scalarNode('login')->end()
                ->scalarNode('role')->cannotBeOverwritten()->end()
                // 不允许覆盖role属性，否则报错
                // 如果希望再次允许覆盖节点，可以使用cannotBeOverwritten(false)
                ->scalarNode('email')->end()
            ->end()
        ->end()
    ->end();

/*
-- merge-primitive-nodes-left-values.yml --
acl:
    staff:
        login: staff
        role: staff
        email: staff@localhost

-- merge-primitive-nodes-right-values.yml --
acl:
    staff:
        login: staff
        role: admin 
        email: staff@web.localhost

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
