<?php
declare(strict_types=1);
namespace Phpcl\LaminasTools;
use Phpcl\LaminasTools\Constants;
/**
 * Base class used by *Builder class in this namespace
 */
class Base
{
    protected $module;      // name of the module
    protected $config;
    protected $output = '';
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
     * @param string $newVal = new value to insert under $topKey => $subKey => $newKey
     * @param string $filename = filename of module config file
     * @param string $contents = current contents under modification; if present, contents are not read in
     * @param bool $quoteKey = TRUE if new key needs to be quoted
     * @param bool $quoteVal = TRUE if new value needs to be quoted
     * @param int $indent = how many spaces to indent 
     * @return string $contents = modified contents
     */
    public function injectConfig(string $topKey, 
								 string $subKey, 
								 string $newKey, 
								 string $newVal, 
								 string $filename,
								 string $contents = '',
								 bool   $quoteKey = FALSE,
								 bool   $quoteVal = FALSE,
								 int    $indent = 12)
    {
        // read contents of config file
        if (!$contents) {
			if (!file_exists($filename)) {
				throw new Exception(Constants::ERROR_MODCFG);
			}
			$contents = file_get_contents($filename);
		}
        // add ref to "InvokableFactory" if not exists
        if (strpos($contents, 'InvokableFactory') === FALSE) {
			$pos = stripos($contents, 'return');
			$part1 = substr($contents, 0, $pos + 1);
			$part2 = substr($contents, $pos);
            $insert = 'use Laminas\ServiceManager\Factory\InvokableFactory;';
			$contents = $part1 . $insert . $part2;
        }
        // locate position of 1st key
        $pos = strpos($contents, $topKey);
        if ($pos !== FALSE) {
			// locate position of 2nd key
			$pos = strpos($contents, $subKey, $pos + strlen($topKey));
			if ($pos !== FALSE) {
				// locate position of next LF
				$pos = strpos($contents, "\n", $pos + strlen($subKey));
				if ($pos !== FALSE) {
					$part1 = substr($contents, 0, $pos + 1);
					$part2 = substr($contents, $pos);
					$insert = str_repeat(' ', $indent);
					if ($quoteKey) {
						$insert .= "'$newKey'" . ' => ';
					} else {
						$insert .= $newKey . ' => ';
					}
					if ($quoteVal) {
						$insert .= "'$newVal',";
					} else {
						$insert .= $newVal . ',';
					}
					$contents = $part1 . $insert . $part2;
				}
			}
		}
        return $contents;
    }
}
