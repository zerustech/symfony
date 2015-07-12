<?php
// array-prototype.php

$loader = require_once __DIR__.'/autoload.php';

use Symfony\Component\Config\Definition\Builder\TreeBuilder; 
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Util\XmlUtils;

$builder = new TreeBuilder();

$configs = array();
$files = array(
    __DIR__.'/data/xml/array-prototype.xml',
);

$rootName = 'app';
$root = $builder->root($rootName);
$root
    ->fixXmlConfig('host')
    ->children()
        ->arrayNode('hosts')
            ->prototype('array')
                ->children()
                    ->scalarNode('ip')->end()
                    ->scalarNode('username')->end()
                    ->scalarNode('password')->end()
                ->end()
        ->end()
    ->end();

/*
-- array-prototype.xml --
<?xml version="1.0"?>
<root>
    <app>
        <host>
            <ip>192.168.1.2</ip>
            <username>admin</username>
            <password>4xx63TC61</password>
        </host>
        <host>
            <ip>192.168.1.2</ip>
            <username>admin</username>
            <password>9h0HO13u9</password>
        </host>
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
