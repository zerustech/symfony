<?php
// parse-yml-array-node-with-one-child.php

$loader = require_once __DIR__.'/autoload.php';

use Symfony\Component\Yaml\Parser as YamlParser;

$parser = new YamlParser();
$file = __DIR__.'/data/yml/array-node-with-one-child.yml';
$config = $parser->parse(file_get_contents($file));
print_r($config);
