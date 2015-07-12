<?php
// fix-xmlconfig-array-node-with-one-child.php

$loader = require_once __DIR__.'/autoload.php';

use Symfony\Component\Config\Definition\Builder\TreeBuilder; 
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Util\XmlUtils;

$builder = new TreeBuilder();

$configs = array();
$files = array(
    __DIR__.'/data/xml/array-node-with-one-child.xml',
);

$rootName = 'app';
$root = $builder->root($rootName);
$root
    ->fixXmlConfig('host') 
    // 如果指定节点在XML中只出现了一次，则会生成与YAML文件不同的数据结构
    // fixXmlConfig()可以消除这种差异
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
-- array-node-with-one-child.xml --
<?xml version="1.0"?>
<root>
    <app>
        <host>
            <ip>192.168.1.2</ip>
            <username>admin</username>
            <password>4xx63TC61</password>
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
