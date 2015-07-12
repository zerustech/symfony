<?php

/*
 * This file is part of the ZerusTech Tutorial package.
 *
 * (c) Michael Lee <michael.lee@zerustech.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace ZerusTech\Tutorial\Symfony\Component\Config\Loader;

use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Parser as YamlParser;
use Doctrine\Common\Proxy\Exception\InvalidArgumentException;

/**
 * YamlFileLoader loads YAML files and parses all 'imports'.
 * 
 * This file is for tutorial purpose.
 *
 * @author Michael Lee <michael.lee@zerustech.com>
 */
class YamlFileLoader extends FileLoader 
{
    
    private $yamlParser;
    
    /**
     * {@inheritdoc}
     */
    public function load($resource, $type = null)
    {
        $path = $this->locator->locate($resource);
    
        $contents = $this->loadFile($path);
    
        $imported = $this->parseImports($contents);
    
        return array_merge_recursive($contents, $imported);
    
    }
    
    
    /**
     * {@inheritdoc}
     */
    private function parseImports(&$contents)
    {
        $imported = array();

        if (!isset($contents['imports'])) {
           return $imported;
        }
        
        $imports = $contents['imports'];
        unset($contents['imports']);
        
        foreach ($imports as $import) {
           $ret = $this->import($import['resource']);
           $imported = array_merge_recursive($imported, $ret);
        }
        
        return $imported;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && in_array(pathinfo($resource, PATHINFO_EXTENSION), array('yml', 'yaml'), true);
    }
    
    /**
     * {@inheritdoc}  
     */
    private function loadFile($file)
    {
        if (!file_exists($file)) {
           throw new InvalidArgumentException(sprintf('The given file "%s" does not exist.'), $file); 
        }

        if (null === $this->yamlParser) {
           $this->yamlParser = new YamlParser(); 
        }
        
        return $this->yamlParser->parse(file_get_contents($file));
        
    }

}
