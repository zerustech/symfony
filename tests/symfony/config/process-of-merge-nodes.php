<?php
// process-of-merging-nodes.php

$loader = require_once __DIR__.'/autoload.php';

use Symfony\Component\Config\Definition\Builder\TreeBuilder; 
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Parser as YamlParser;

$builder = new TreeBuilder();

$configs = array();
$parser = new YamlParser();
$files = array(
    __DIR__.'/data/yml/process-of-merging-nodes-left-values.yml',
    __DIR__.'/data/yml/process-of-merging-nodes-right-values.yml',
);

$rootName = 'app';
$root = $builder->root($rootName);
$root
    ->children()
        ->arrayNode('database')
            ->children()
                ->scalarNode('driver')->defaultValue('mysql')->end()
                ->scalarNode('host')->defaultValue('127.0.0.1')->end()
                ->scalarNode('dbname')->defaultValue('symfony')->end()
                ->scalarNode('username')->defaultValue('symfony')->end()
                ->scalarNode('password')->defaultValue('symfony')->end()
                ->scalarNode('charset')->defaultValue('UTF8')->end()
                ->scalarNode('collate')->defaultValue('utf8-general-ci')->end()
            ->end()
        ->end()
    ->end();

/*
-- process-of-merging-nodes-left-values.yml --
app:
    database:
        host: 192.168.1.2
        dbname: symfony-on-server-1

-- process-of-merging-nodes-right-values.yml --
app:
    database:
        host: 192.168.1.3
        dbname: symfony-on-server-2

*/

foreach($files as $file)
{ 
    $config = $parser->parse(file_get_contents($file));
    $configs[] = $config[$rootName];
}

$processor = new Processor();
$tree = $builder->buildTree();
$result = $processor->process($tree, $configs);
// 具体的合并过程如下：
/*
    namespace Symfony\Component\Config\Definition;

    class Processor
    {
        public function process(NodeInterface $configTree, array $configs)
        {
            $currentConfig = array();
            foreach ($configs as $config) {
                $config = $configTree->normalize($config);
                // 注意：所谓合并是指将来自多个配置文件的配置内容进行合并
                // 而不是将配置文件中的内容与配置树中的默认值进行合并
                // 后者是通过调用配置树的finalize()方法来实现的
                $currentConfig = $configTree->merge($currentConfig, $config);
            }

            return $configTree->finalize($currentConfig);
        }
        ...
    }

*/

print_r($result);
