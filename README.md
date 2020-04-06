# Laminas Tools
Tools to facilitate rapid app development using Laminas MVC.

## Installation
To get the latest version add the `--dev` flag.
```
composer require --dev phpcl/laminas-tools
```
Otherwise, specify your preferred version in the `composer.json` file:
```
{
    "require" : {
        "phpcl/laminas-tools" : "^1.0"
        ... other config not specified
    }
    ... other config not specified
}
```
and then run `composer install`.

## Usage
From a command prompt or terminal window, change to the project root directory of your ZF 3 or Laminas MVC project.

### Linux
```
vendor/bin/phpcl-laminas-tools WHAT PATH NAME
```

### Windows
```
vendor/bin/phpcl-laminas-tools WHAT PATH NAME
```

### Params
| Param | Example | Description |
| :---: | :-----: | :---------- |
| WHAT  | "module","controller" or "factory" | Describes what component you want to build |
| PATH  | "/path/to/project" | Full path to your project root directory |
| NAME  | "Test"  | Name of the module you want to create, or |
|       | "Test\\\Controller\\\ListController" | Name of the controller you want to create, or |
|       | "Test\\\Factory\\\ListServiceFactory" | Name of the factory you want to create |

## Examples
These examples assume you are running from a command prompt / terminal window, and have changed to the root directory of your project.

### Creating a Module
As an example, to create a module "Test" on a Linux server:
```
vendor/bin/phpcl-laminas-tools module `pwd` Test
```

Here is what the tool does:
* Creates the module directory structure
* Creates a file `module/Test/src/Module.php`
* Create a controller `module/Test/src/Controller/IndexController.php`
* Creates a view template `/module/Test/view/test/index/index.phtml`
* Creates a config file `module/Test/config/module.config.php`
  * Adds a route `/test[/:action]` (where `action` is the name of any additional `xxxAction()` methods created in the controller)
  * Registers the controller with the framework

### Creating a Controller
As an example, to create a controller "Test\Controller\ListController" on a Windows server:
```
vendor/bin/phpcl-laminas-tools controller "C:\path\to\project" "Test\\Controller\\ListController"
```

Here is what the tool does:
* Creates a file `C:\path\to\project\module\Test\src\Controller\ListController.php`
* Creates a view template `\C:\path\to\project\module\Test\view\test\list\index.phtml`
* Creates a config file `C:\path\to\project\module\Test\config\module.config.php`
  * Adds a route `/test-list[/:action]` (where `action` is the name of any additional `xxxAction()` methods created in the new controller)
  * Registers the new controller with the framework

### Creating a Factory
As an example, to create a factory "Test\Factory\ListServiceFactory" on a Linux server:
```
vendor/bin/phpcl-laminas-tools factory `pwd` "Test\\Factory\\ListServiceFactory"
```

The tool will then directly output the code for a generic factory named `ListServiceFactory`.  If you wish to pipe the output into a file, do this:
```
mkdir module/Test/src/Factory
vendor/bin/phpcl-laminas-tools.sh factory `pwd` "Test\\Factory\\ListServiceFactory" >module/Test/src/Factory/ListServiceFactory.php
```

IMPORTANT:
* If you wish to generate a factory for a specific class, use the already-existing Laminas CLI tool `vendor/bin/generate-factory-for-class` instead.
* The PHP-CL Laminas Tools can be used to create a factory if the `generate-factory-for-class` command fails, or if the factory class you wish to create does not have resolvable type-hints.
* If you prefer, you can also simply download the file [`laminas-tools.phar`](https://github.com/phpcl/laminas_tools/raw/master/laminas-tools.phar)
  * Usage is the same: follow the examples above, but substitute `php laminas-tools.phar` in place of `vendor/bin/phpcl-laminas-tools`
