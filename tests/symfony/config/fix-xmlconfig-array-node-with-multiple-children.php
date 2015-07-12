<?php
// fix-xmlconfig-array-node-with-multiple-children.php

$loader = require_once __DIR__.'/autoload.php';

use Symfony\Component\Config\Definition\Builder\TreeBuilder; 
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Util\XmlUtils;

$builder = new TreeBuilder();

$configs = array();
$files = array(
    __DIR__.'/data/xml/array-node-with-multiple-children.xml',
);

$rootName = 'app';
$root = $builder->root($rootName);
$root
    ->fixXmlConfig('host') 
    // fixXmlConfig可以把指定的节点名称替换成复数形式
    // 如果省略第二个参数，则默认情况下会在节点名称后追加's'
    // 如果项目的复数时特殊形式，例如child => children
    // 则需要传递第二个参数：fixXmlConfig('child', 'children')
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
-- array-node-with-multiple-children.xml --
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
