<?php
$loader = require_once __DIR__ . '/autoload.php';

use Symfony\Component\Yaml\Parser as YamlParser;

$configs = array();

$parser = new YamlParser();
$config = $parser->parse(file_get_contents(__DIR__.'/data/yml/schema.yml'));
$configs[] = $config['foo_extension'];
print_r($configs);
