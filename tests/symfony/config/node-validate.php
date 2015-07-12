<?php
// node-validate-required.php
$loader = require_once __DIR__.'/autoload.php';

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Parser as YamlParser;

$builder = new TreeBuilder();

$configs = array();
$parser = new YamlParser();
$files = array(
    __DIR__.'/data/yml/node-validate.yml',
);

$rootName = 'foo_extension';
$root = $builder->root($rootName);
$root
    ->children()
        ->scalarNode('param1')->isRequired()->end() 
        ->integerNode('positive_value')->min(0)->end()
        ->floatNode('big_value')->max(5E45)->end()
        ->integerNode('value_inside_a_range')->min(-50)->max(50)->end()
        ->enumNode('gender')
            ->values(array('male', 'female'))
        ->end()
        ->scalarNode('driver')
            ->validate()
                ->ifNotInArray(array('mysql', 'sqlite', 'mssql'))
                ->thenInvalid('Invalid driver "%s"')
            ->end()
        ->end()
    ->end();

/*
-- node-validate-required.yml --

foo_extension:
    param1: value of param1 
    positive_value: 10
    big_value: 99999
    value_inside_a_range: 50
    gender: male
    driver: mysql

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
