<?php
namespace Phpcl\LaminasTools;

/**
 * Base class used by *Builder class in this namespace
 */
class Base
{
    protected $module;      // name of the module
    protected $config;
    protected $output = '';
    protected $confHeader = '';
    protected $confArray = [];
    /**
     * @param string $module == name of the module to be created or used
     * @param array $config == templates and how to inject module into list of modules for this app
     */
    public function __construct(string $module, array $config)
    {
        $this->module = $module;
        $this->config = $config;
    }
    /**
     * @return string $output + "\n"
     */
    public function getOutput()
    {
        return $this->output . PHP_EOL;
    }
    /**
     * Injects item into primary config file
     *
     * @param string $topKey = primary array key to search for
     * @param string $subKey = secondary array key to search for
     * @param string $newKey = new key to insert under $topKey => $subKey
     * @param mixed $newVal = new value to insert under $topKey => $subKey => $newKey
     * @param string $filename = filename of module config file
     * @return string $contents = modified contents
     */
    public function injectConfig(string $topKey, string $subKey, string $newKey, $newVal, string $filename)
    {
        // grab config from module
        if (!$this->confArray) {
            $this->confArray = require $filename;
        }
        // retain everything up to "return"
        if ($this->confHeader) {
            $contents = $this->confHeader;
        } else {
            $contents = '';
            $lineByLine = file($filename);
            foreach ($lineByLine as $line) {
                if (strpos($line, 'return') !== FALSE) break;
                $contents .= $line;
            }
            unset($lineByLine);
            $this->confHeader = $contents;
        }
        // add ref to "InvokableFactory" if not exists
        if (strpos($contents, 'InvokableFactory') === FALSE) {
            $contents .= PHP_EOL
                       . 'use Laminas\ServiceManager\Factory\InvokableFactory;'
                       . PHP_EOL;
        }
        // inject key
        if ($subKey) {
            $this->confArray[$topKey][$subKey][$newKey] = $newVal;
        } else {
            $this->confArray[$topKey][$newKey] = $newVal;
        }
        // return array as string
        $text = var_export($this->confArray, TRUE);
        $text = str_replace(['array (',')','\\\\',"\n\n"],['[',']','\\',"\n"],$text);
        // move open "[" to same line as $key =>
        $space = '';
        for ($x = 0; $x < 12; $x++) {
            $text = str_replace("=> \n" . $space . '[','=> [',$text);
            $space .= ' ';
        }
        return $contents . "\n" . 'return ' . "\n" . $text . ';';
    }
}