<?php
// xml-file-loader.php

$loader = require_once __DIR__.'/autoload.php';
$loader->addPsr4('ZerusTech\\Tutorial\\Symfony\\Component\\Config\\', __DIR__.'/classes/ZerusTech/Tutorial/Symfony/Component/Config');

use Symfony\Component\Config\FileLocator;
use ZerusTech\Tutorial\Symfony\Component\Config\Loader\XmlFileLoader;

$paths = array(
    __DIR__.'/data/xml',
);
$locator = new FileLocator($paths);
$loader = new XmlFileLoader($locator);
$config = $loader->load('xml-file-loader.xml');
/*
-- xml-file-loader.xml --
<?xml version="1.0" ?>
<root>
    <imports>
        <import resource="users.xml" />
        <import resource="hosts.xml" />
    </imports>
    <app>
        <database>
            <username>symfony-mysql</username>
            <password>symfony-mysql</password>
        </database>
    </app>
</root>

-- users.xml --
<?xml version="1.0" ?>
<root>
    <app>
        <user>
            <login>root</login>
            <email>root@localhost</email>
            <group>admin</group>
        </user>
        <user>
            <login>member</login>
            <email>member@localhost</email>
            <group>member</group>
        </user>
    </app>
</root>

-- hosts.xml --
<?xml version="1.0" ?>
<root>
    <app>
        <host>
            <name>web2</name>
            <ip>192.168.1.4</ip>
            <username>admin</username>
            <password>4xx63TC61</password>
        </host>
        <host>
            <name>db2</name>
            <ip>192.168.1.5</ip>
            <username>admin</username>
            <password>9h0HO13u9</password>
        </host>
    </app>
</root>

*/
print_r($config);
