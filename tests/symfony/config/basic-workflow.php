<?php
// basic-workflow.php

$loader = require_once __DIR__.'/autoload.php';

use Symfony\Component\Config\Definition\Builder\TreeBuilder; 
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Parser as YamlParser;
use Symfony\Component\Config\Util\XmlUtils;

// 创建配置树构造器
$builder = new TreeBuilder(); // 构造器

$configs = array(); // 保存配置文件内容的数组
$parser = new YamlParser(); // YAML文件解析器
// 在实际工作中，我们会从多个配置文件中读取配置
// 例如：basic-workflow.yml为全局的配置文件
// basic-workflow-dev.yml为开发模式的配置文件
// 并将配置与配置树合并
$files = array(
    __DIR__.'/data/yml/basic-workflow.yml',
    __DIR__.'/data/yml/basic-workflow-dev.yml',
);

// 开始创建配置树构造器。
// 配置树构造器可以用来创建一棵包含所有默认值的配置树
$rootName = 'app';
$root = $builder->root($rootName); // 根节点
$root // 添加各种子节点
    ->children()
        ->arrayNode('database')
            ->children()
                ->scalarNode('driver')->defaultValue('pdo_mysql')->end() // 添加子节点并设置默认值
                ->scalarNode('host')->defaultValue('127.0.0.1')->end()
                ->scalarNode('port')->defaultValue('3306')->end()
                ->scalarNode('dbname')->defaultValue('symfony')->end()
                ->scalarNode('user')->defaultValue('root')->end()
                ->scalarNode('password')->defaultValue(null)->end()
                ->scalarNode('charset')->defaultValue('UTF8')->end()
                ->scalarNode('collate')->defaultValue('utf8-general-ci')->end()
            ->end()
        ->end()
    ->end();
// 构造器创建完毕

// 配置文件的内容如下：
// 注意：用户只设置了需要修改的参数，其余的参数最终将使用配置树的默认配置
/*
-- basic-workflow.yml --
app:
    database:
        dbname: symfony-config 
        user: symfony
        password: symfony

-- basic-workflow-dev.yml --
app:
    database:
        user: symfony-dev
        password: symfony-dev
*/

foreach($files as $file)
{ 
    $config = $parser->parse(file_get_contents($file));
    $configs[] = $config[$rootName];
}

$processor = new Processor(); // 创建配置树处理器
$tree = $builder->buildTree(); // 通过构造器创建配置树
$result = $processor->process($tree, $configs); // 将用户的配置依次与包含默认值的配置树合并
print_r($result); // 输出结果
