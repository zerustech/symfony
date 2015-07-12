<?php
// basic-workflow-with-advanced-features.php 

$loader = require_once __DIR__.'/autoload.php';

use Symfony\Component\Config\Definition\Builder\TreeBuilder; // TreeBuilder是构造配置树构造器的入口类
use Symfony\Component\Config\Definition\Processor; // Processor类负责正规化、合并用户定义的配置内容
use Symfony\Component\Config\Util\XmlUtils; // 负责解析XML文件
use Symfony\Component\Yaml\Parser as YamlParser; // 解析YAML文件

$builder = new TreeBuilder(); 
// 首先构造一个TreeBuilder对象
// TreeBuilder是创建配置树构造器与配置树的入口类

$configs = array();
$parser = new YamlParser();
$files = array(
    __DIR__.'/data/yml/basic-workflow-with-advanced-features.yml',
);

$rootName = 'foo_extension';
$root = $builder->root($rootName); 
// 构造配置树构造器的根节点。这个根节点是TreeBuilder的属性，因此TreeBuilder可以访问到这个根节点。
// 配置树构造器也是一个树状结构，实际上它的节点与真正的配置树的节点是一一对应的。
// 根节点的类型是Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition，即数组类型的节点
// 不难理解，如果根节点不是数组类型的，它也就不可能包含任何子节点，也就无法构造树状结构。
// 因此，根节点必然是数组类型的节点

$root
    ->children() // 这个方法返回一个Symfony\Component\Config\Definition\Builder\NodeBuilder类型的对象
                 // 它的父节点是创建它的那个数组节点
                 // 它的作用是为父节点创建各种类型的子节点
                 // 创建好的子节点会被加入数组父节点中，并且然逻辑上这些子节点是直接从属于这个数组父节点的
                 // 但是为了支持流畅的链式调用，会将创建好的子节点的父节点临时设置为NodeBuilder节点
                 // 在构造真正的配置树的时候，NodeBuilder节点将会消失，因为它是一种类似于脚手架一样的中间产物
                 // 并且它创建的那些子节点的父节点也会被重新设置（我们在后面的章节会详细讨论）

        // 为根节点创建一个名称为param1的ScalarNodeDefinition类型的子节点
        // scalarNode() 方法返回一个ScalarNodeDefinition类型的对象
        ->scalarNode('param1') 
            ->defaultValue('default-param1-value') // 设置param1节点的默认值，返回当前节点，从而支持链式调用
        ->end() 
        // end()方法永远返回当前节点的父节点。
        // 根据前面的讨论，目前，当前节点是param1节点，它的父节点是那个NodeBuilder节点，而不是根节点
        
        ->scalarNode('param2') // 创建另外一个名字为param2的ScalarNodeDefinition类型的节点
            ->defaultValue('default-param2-value')
        ->end() // 返回NodeBuilder对象

        ->scalarNode('param3')->end() // 创建param3节点，不设置默认值

        ->scalarNode('param4')->end()

    ->end(); 
    // 调用NodeBuilder对象的end()方法，返回它的父节点。在本例中为根节点。

// 到目前为止，我们定义了一棵配置树的构造器，它包含一个根节点foo_extension。
// 这个根节点下面允许有4个子节点：param1, param2, param3和param4
// 数据结构如下：
// foo_extension
//     |
//     + param1 (默认值：default-param1-value)
//     + param2 (默认值：default-param2-value)
//     + param3
//     + param4

/*
-- basic-workflow-with-advanced-features.yml --
foo_extension:
    param2: custom-param2-value
    param3: custom-param3-value
*/

foreach($files as $file)
{ 
    $config = $parser->parse(file_get_contents($file));
    $configs[] = $config[$rootName];

}

// buildTree()方法从配置树构造器的根节点开始，构造一棵完整的、干净的配置树，并返回这棵配置树。
// 配置树节点为Symfony\Component\Config\Definition\NodeInterface类型的对象
$configTree = $builder->buildTree();

// Processor是负责使用配置树对用户定义的配置数据进行处理的类
$processor = new Processor();

// 通过配置树对用户数据进行正规化，合并处理
$result = $processor->process($configTree, $configs);
// 具体步骤如下：
// 当前的配置数据，默认为空
// $currentConfig = array();
// 因为假定用户数据永远被封装为数组，即使只有一组数据
// 所以此处对所有用户数据进行遍历
// foreach ($configs as $config) {
//     调用配置树的normalize()方法对当前这一组用户数据进行正规化
//     返回结果为正规化之后的数据
//     $config = $configTree->normalize($config);
//     调用配置树的merge()方法将当前这一组用户数据合并到$currentConfig中
//     合并的时候，按照节点递归合并
//     $currentConfig = $configTree->merge($currentConfig, $config);
// }
//
// 到这里为止，$currentConfig所包含的数据是所有的用户数据合并之后的结果
// 调用配置树的finalize()方法再将合并后的用户数据与配置树中节点的默认值进行合并，并返回合并的结果。
// 最终获得的是一个包含所有的默认值以及用户数据的php数组
// return $configTree->finalize($currentConfig);

print_r($result);
