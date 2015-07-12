<?php
// attribute-as-key-left-value-only.php

$loader = require_once __DIR__.'/autoload.php';

use Symfony\Component\Config\Definition\Builder\TreeBuilder; 
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Parser as YamlParser;

$builder = new TreeBuilder();

$configs = array();
$parser = new YamlParser();
$files = array(
    __DIR__.'/data/yml/attribute-as-key-left-value-only.yml',
);

// 注意：
// 目前的symfony代码中存在一个bug，因此在处理这种数据的时候会抛出错误
// 我们已经提交了一个修正这个错误的PR #14082
// 但是还没有得到官方的回应
// 我们已经在本地修正了这个错误
$rootName = 'app';
$root = $builder->root($rootName);
$root
    ->fixXmlConfig('parameter')
    ->children()
        ->arrayNode('parameters')
            ->useAttributeAsKey('name')
            ->prototype('array')
                ->children()
                    ->scalarNode('name')->end()
                    ->scalarNode('value')->end()
                ->end()
        ->end()
    ->end();

/*
-- attribute-as-key-left-value-only.yml --
app:
    parameters:
        - name: server_name
          value: web.local
        - name: ip
          value: 192.168.1.2
        - name: username
          value: admin
        - name: password
          value: 4xx63TC61
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
