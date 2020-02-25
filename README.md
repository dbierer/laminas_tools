# Laminas Tools
Tools to facilitate rapid app development using Laminas MVC.

## Setup
Download `laminas-tools.zip`
Unzip into the `vendor/bin` folder of your ZF 3 or Laminas MVC project.

## Usage
Change to the project root directory of your ZF 3 or Laminas MVC project.

### Linux
```
vendor/bin/laminas-tools.sh WHAT PATH NAME
```

### Windows
```
vendor/bin/laminas-tools.bat WHAT PATH NAME
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
### Creating a Module
As an example, to create a module "Test" on a Linux server
```
cd /path/to/project
vendor/bin/laminas-tools.sh module `pwd` Test
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
* First, open a command prompt
* From the command prompt window, issue these commands.  If the project is on another drive, substitute that drive letter in place of `C:\`:
```
cd C:\path\to\project
vendor/bin/laminas-tools controller "C:\path\to\project" "Test\\Controller\\ListController"
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
cd /path/to/project
vendor/bin/laminas-tools.sh factory `pwd` "Test\\Factory\\ListServiceFactory"
```

The tool will then directly output the code for a generic factory named `ListServiceFactory`.  If you wish to pipe the output into a file, do this:
```
cd /path/to/project
mkdir module/Test/src/Factory
vendor/bin/laminas-tools.sh factory `pwd` "Test\\Factory\\ListServiceFactory" >module/Test/src/Factory/ListServiceFactory.php
```

IMPORTANT:
* If you wish to generate a factory for a specific class, use `vendor/bin/generate-factory-for-class` instead.
* This tool can be used to create a factory if the `generate-factory-for-class` command fails.

## TODO
* Create RESTful controllers
* Create handlers in Expressive / Mezzio

