<?php
// parse-xml-array-node-with-one-child.php

$loader = require_once __DIR__.'/autoload.php';

use Symfony\Component\Config\Util\XmlUtils;

$file = __DIR__.'/data/xml/array-node-with-multiple-children.xml';

$dom = XmlUtils::loadFile($file);

$config = XmlUtils::convertDomElementToArray($dom->documentElement);

print_r($config);
