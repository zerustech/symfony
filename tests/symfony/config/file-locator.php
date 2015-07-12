<?php
// file-locator.php

$loader = require_once __DIR__.'/autoload.php';

use Symfony\Component\Config\FileLocator;

$configDirectories = array(__DIR__.'/data/yml');

$locator = new FileLocator($configDirectories);

$files = $locator->locate('basic-workflow.yml', null, false);

print_r($files);
