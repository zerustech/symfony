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
use Symfony\Component\Config\Util\XmlUtils;
use Doctrine\Common\Proxy\Exception\InvalidArgumentException;

/**
 * XmlFileLoader loads XML files and parses all 'imports'.
 * 
 * This file is for tutorial purpose.
 *
 * @author Michael Lee <michael.lee@zerustech.com>
 */
class XmlFileLoader extends FileLoader 
{

    /**
     * {@inheritdoc}
     */
    public function load($resource, $type = null)
    {
        $path = $this->locator->locate($resource);
    
        $dom = $this->parseFileToDOM($path);

        $imported = $this->parseImports($dom);

        $contents = XmlUtils::convertDomElementToArray($dom->documentElement);
    
        return array_merge_recursive($contents, $imported);
    
    }

    
    /**
     * {@inheritdoc}
     */
    private function parseImports($dom)
    {
        $imported = array();

        $xpath = new \DOMXPath($dom);
        if (false === $imports = $xpath->query('//imports/import')) {
            return $imported;
        }

        foreach ($imports as $import) {
           $ret = $this->import($import->getAttribute('resource'));
           $imported = array_merge_recursive($imported, $ret);
        }
        
        $imports = $xpath->query('//imports');
        foreach ($imports as $import) {
           $import->parentNode->removeChild($import);
        }

        return $imported;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && in_array(pathinfo($resource, PATHINFO_EXTENSION), array('xml'), true);
    }
    
    /**
     * {@inheritdoc}  
     */
    private function parseFileToDOM($file)
    {
        if (!file_exists($file)) {
           throw new InvalidArgumentException(sprintf('The given file "%s" does not exist.'), $file); 
        }

        return XmlUtils::loadFile($file);
        
    }

}
