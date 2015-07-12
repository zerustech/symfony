<?php
// yaml-file-loader.php

$loader = require_once __DIR__.'/autoload.php';
$loader->addPsr4('ZerusTech\\Tutorial\\Symfony\\Component\\Config\\', __DIR__.'/classes/ZerusTech/Tutorial/Symfony/Component/Config');

use Symfony\Component\Config\FileLocator;
use ZerusTech\Tutorial\Symfony\Component\Config\Loader\YamlFileLoader;

$paths = array(
    __DIR__.'/data/yml',
);
$locator = new FileLocator($paths);
$loader = new YamlFileLoader($locator);
$config = $loader->load('yaml-file-loader.yml');


/*
 
-- yaml-file-loader.yml --
imports: 
    - { resource: users.yml }
    - { resource: hosts.yml }
app:
    database:
        host: db1
        dbname: symfony-tuotrial

-- users.yml --
app:
    users:
        - login: admin
          email: admin@localhost
          group: admin
        - login: staff
          email: staff@localhost
          group: staff

-- hosts.yml --
app:
    hosts:
        - name: web1
          ip: 192.168.1.2
          username: admin
          password: 4xx63TC61
        - name: db1
          ip: 192.168.1.3
          username: admin
          password: 9h0HO13u9


*/
print_r($config);
