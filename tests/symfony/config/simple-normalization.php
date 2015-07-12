<?php
// simple-normalization.php

$loader = require_once __DIR__.'/autoload.php';

use Symfony\Component\Config\Definition\Builder\TreeBuilder; 
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Util\XmlUtils;

$builder = new TreeBuilder();

$configs = array();
$files = array(
    __DIR__.'/data/xml/simple-normalization.xml',
);

$rootName = 'app';
$root = $builder->root($rootName);
$root
    ->children()
        ->arrayNode('host_parameters')
            ->children()
                ->scalarNode('host_ip')->end()
                ->scalarNode('host_username')->end()
                ->scalarNode('host_password')->end()
            ->end()
        ->end()
    ->end();

/*
-- simple-normalization.xml --
<?xml version="1.0"?>
<root>
    <app>
        <host-parameters>
            <host-ip>192.168.1.2</host-ip>
            <host-username>admin</host-username>
            <host-password>4xx63TC61</host-password>
        </host-parameters>
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
