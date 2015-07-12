<?php
// append-nodes.php

$loader = require_once __DIR__.'/autoload.php';

use Symfony\Component\Config\Definition\Builder\TreeBuilder; 
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Parser as YamlParser;


$configs = array();
$parser = new YamlParser();
$files = array(
    __DIR__.'/data/yml/append-nodes.yml',
);


$userBuilder = new TreeBuilder();
$users = $userBuilder->root('users');
$users
    ->useAttributeAsKey('login')
    ->prototype('array')
        ->children()
            ->scalarNode('login')->end()
            ->scalarNode('email')->end()
            ->scalarNode('group')->end()
        ->end()
    ->end();

$serverBuilder = new TreeBuilder();
$servers = $serverBuilder->root('hosts');
$servers
    ->useAttributeAsKey('name')
    ->prototype('array')
        ->children()
            ->scalarNode('name')->end()
            ->scalarNode('ip')->end()
            ->scalarNode('username')->end()
            ->scalarNode('password')->end()
        ->end()
    ->end();


// 可以将其它的定义树追加为当前定义树的子节点
$builder = new TreeBuilder();
$rootName = 'app';
$root = $builder->root($rootName);
$root
    ->fixXmlConfig('user')
    ->fixXmlconfig('host')
    ->append($users)
    ->append($servers);

/*
-- append-nodes.yml --
app:
    users:
        - login: admin
          email: admin@localhost
          group: admin
        - login: staff
          email: staff@localhost
          group: staff
    hosts:
        - name: web1
          ip: 192.168.1.2
          username: admin
          password: 4xx63TC61
        - name: db1
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
