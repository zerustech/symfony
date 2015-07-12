<?php
// merge-array-prototype-nodes-with-key-in-yml.php

$loader = require_once __DIR__.'/autoload.php';

use Symfony\Component\Config\Definition\Builder\TreeBuilder; 
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Parser as YamlParser;

$builder = new TreeBuilder();

$configs = array();
$parser = new YamlParser();
$files = array(
    __DIR__.'/data/yml/merge-array-prototype-nodes-with-key-in-yml-left-values.yml',
    __DIR__.'/data/yml/merge-array-prototype-nodes-with-key-in-yml-right-values.yml',
);

$rootName = 'app';
$root = $builder->root($rootName);
$root
    ->children()
        ->arrayNode('users')
            ->useAttributeAsKey('any text')
            // 如果在YML文件中为prototype数组的子节点显式定义了key
            // 则useAttriubteAsKey()的参数可以是任何非空的字符串
            // 此时调用useAttributeAsKey()方法的作用只是告诉系统
            // 当前prototype数组的子节点是有key的
            
            //->useAttributeAsKey('login')
            // 如果使用了真实的属性作为key，例如: 'login'
            // 则useAttributeAsKey中的key会覆盖YML中的key
            
            // 如果不使用useAttributeAsKey()，则右值数组中的key会被忽略
            // 系统会使用数字作为数组元素的key
            ->prototype('array')
                ->children()
                    ->scalarNode('login')->end()
                    ->scalarNode('email')->end()
                    ->scalarNode('group')->end()
                ->end()
        ->end()
    ->end();

/*
-- merge-array-prototype-nodes-with-key-in-yml-left-values.yml --
app:
    users:
        user1: 
            login: admin
            email: admin@localhost
            group: admin
        user2: 
            login: staff
            email: staff@localhost
            group: staff

-- merge-array-prototype-nodes-with-key-in-yml-right-values.yml --
app:
    users:
        user1: 
            login: admin
            email: admin1@localhost
            group: admin
        user2:
            login: staff
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
