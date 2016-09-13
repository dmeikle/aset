<?php
/*
 *  This file is part of the Quantum Unit Solutions development package.
 * 
 *  (c) Quantum Unit Solutions <http://github.com/dmeikle/>
 * 
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

/** *
 * Author: dave
 * Date: 9/13/2016
 * Time: 12:00 PM
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

date_default_timezone_set('America/Vancouver');

$site_path =  realpath(dirname(__FILE__));// strip the /web from it

define ('__SITE_PATH', $site_path);
define('__CACHE_DIRECTORY', $site_path . '/cache/');

//include_once('phpunit.configuration.php');
require_once(__SITE_PATH . '/../vendor/composer/ClassLoader.php');
 $loader = new Composer\Autoload\ClassLoader();

      // register classes with namespaces
      $loader->add('Gossamer\\Caching', __SITE_PATH.'/../vendor/gossamer/caching/src');
      $loader->add('Gossamer', __SITE_PATH .'/../src');
      $loader->add('components', __SITE_PATH .'/src');
      $loader->add('core', __SITE_PATH .'/app');
      $loader->add('Monolog', __SITE_PATH.'/../vendor/monolog/monolog/src');
      $loader->add('triagens', __SITE_PATH.'/../vendor/triagens/arangodb/lib');


      // activate the autoloader
      $loader->register();

      // to enable searching the include path (eg. for PEAR packages)
      $loader->setUseIncludePath(true);