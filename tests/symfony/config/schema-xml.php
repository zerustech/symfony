<?php
// schema-xml.php

$loader = require_once __DIR__ . '/autoload.php';

use Symfony\Component\Config\Util\XmlUtils;

$configs = array();

$dom = XmlUtils::loadFile(__DIR__.'/data/xml/schema.xml');
$config = XmlUtils::convertDomElementToArray($dom->documentElement);
$configs[] = $config['foo_extension'];
print_r($configs);
