<?php
/**
 * Part of CodeIgniter Doctrine
 *
 * @author     Kenji Suzuki <https://github.com/kenjis>
 * @license    MIT License
 * @copyright  2015 Kenji Suzuki
 * @link       https://github.com/kenjis/codeigniter-doctrine
 */

error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';

define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');
define('BASEPATH', __DIR__ . '/vendor/codeigniter/framework/system/');
define('APPPATH', __DIR__ . '/application/');

