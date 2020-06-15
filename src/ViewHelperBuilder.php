<?php
// this tool creates a Laminas modules
namespace Phpcl\LaminasTools;

class ViewHelperBuilder extends Base
{
    protected $className;  // name of the view helper to build

    /**
     * Creates everything needed for a Laminas/ZF3 view helper
     *
     * @param string $baseDir == absolute path to project base
     * @param string $moduleName == name of module to build
     * @param string $className == name of view helper to build
     */
    public function build(string $baseDir, string $moduleName, string $className)
    {

        // create "alias" name
        $alias = strtolower(substr($className, 0, 1)) . substr($className, 1);
        $alias = str_replace(Constants::SUFFIX['view-helper'], '', $alias);

        // make base directory for view helper
        $this->output .= 'Creating directory structures for new view helper' . "\n";
        $ctlBase = $this->config['base'];

        // make sure mod structure exists
        if (!file_exists($ctlBase)) {
            $this->output .= Constants::ERROR_MOD . "\n";
            return FALSE;
        }

        // build view helper base directory structure (if it doesn't exist)
        $dirs    = explode('/', $this->config['templates']['view-helper']['path']);
        foreach ($dirs as $dir) {
            $ctlBase = str_replace('//', '/', $ctlBase . '/' . $dir);
            if (!file_exists($ctlBase)) mkdir($ctlBase);
        }

        // get view helper template
        $contents = $this->config['templates']['view-helper']['template'];
        // replace "%%CLASSNAME%%" with $className
        $contents = str_replace('%%CLASSNAME%%', $className, $contents);
        // write template contents out to appropriate file
        $this->output .= 'Writing out new view helper file' . "\n";
        file_put_contents(str_replace('//', '/', $ctlBase . '/' . $className . '.php'), $contents);

        // update module config file
        $this->output .= 'Backing up module config file' . "\n";
        $modConf = $this->config['base']
                 . '/' . $this->config['templates']['config']['path']
                 . '/' . $this->config['templates']['config']['filename'];
        $modConf = str_replace('//', '/', $modConf);
        copy($modConf, $modConf . '.bak');
        $this->output .= 'Updating module config file' . "\n";
        $config = require $modConf;

        // inject view helper registration + alias
        $this->output .= 'Assigning new view helper to "InvokableFactory"' . "\n";
        $this->className = $this->config['templates']['view-helper']['prefix'] . '\\' . $className;
        $contents = $this->injectConfig(
            'view_helpers',
            'factories',
            $this->className . '::class',
            'InvokableFactory::class',
            $modConf
        );
        $this->output .= "Assigning alias to new view helper: $alias\n";
        $contents = $this->injectConfig(
            'view_helpers',
            'aliases',
            $alias,
            $this->className . '::class',
            $modConf,
            $contents,
            TRUE
        );

        // write out new config file
        return file_put_contents($modConf, $contents);
    }
    /**
     * Returns built classname
     *
     * @return string $this->className
     */
    public function getClassName()
    {
        return $this->className;
    }
}
