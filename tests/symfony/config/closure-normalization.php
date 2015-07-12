<?php
// closure-normalization.php

$loader = require_once __DIR__.'/autoload.php';

use Symfony\Component\Config\Definition\Builder\TreeBuilder; 
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Util\XmlUtils;

$builder = new TreeBuilder();

$configs = array();
$files = array(
    __DIR__.'/data/xml/closure-normalization.xml',
);

$rootName = 'app';
$root = $builder->root($rootName);
$root
    ->children()
        ->arrayNode('connection')
            ->children()
                ->scalarNode('ip')->end()
                ->scalarNode('username')->end()
                ->scalarNode('password')->end()
                ->scalarNode('timeout')
                    ->beforeNormalization()
                        ->ifTrue(function($value){return !is_numeric($value);})
                        ->then(function($value){return 300;})
                    ->end()
                ->end()
            ->end()
        ->end()
    ->end();

/*
-- closure-normalization.xml --
<?xml version="1.0"?>
<root>
    <app>
        <connection>
            <ip>192.168.1.2</ip>
            <username>admin</username>
            <password>4xx63TC61</password>
            <timeout>300 seconds</timeout>
        </connection>
    </app>
</root>
*/

foreach($files as $file)
{ 
    $dom = XmlUtils::loadFile($file);
    $config = XmlUtils::convertDomElementToArray($dom->documentElement);
    $configs[] = $config[$rootName];
}

$processor = new Processor();
$tree = $builder->buildTree();
$result = $processor->process($tree, $configs);
print_r($result);
