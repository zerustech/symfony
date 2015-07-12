<?php
// parse-single-yml.php

$loader = require_once __DIR__.'/autoload.php';

use Symfony\Component\Yaml\Parser as YamlParser;

$parser = new YamlParser();
$config = $parser->parse(file_get_contents(__DIR__.'/data/yml/basic-workflow.yml'));
print_r($config);
