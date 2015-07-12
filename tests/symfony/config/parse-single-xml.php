<?php
// parse-single-xml.php

$loader = require_once __DIR__.'/autoload.php';

use Symfony\Component\Config\Util\XmlUtils;

// 将XML文件的内容解析为DomDocument对象
$dom = XmlUtils::loadFile(__DIR__.'/data/xml/basic-workflow.xml');

/*
-- basic-workflow.xml --
<?xml version="1.0" ?>
<root>
    <app>
        <database>
            <user>symfony</user>
            <password>symfony</password>
            <dbname>symfony-config</dbname>
        </database>
    </app>
</root>
*/

// 将DomElemnet对象转化为php数组
// 注意：转化出的php数组不包含XML文件的根节点
$config = XmlUtils::convertDomElementToArray($dom->documentElement);

print_r($config);
