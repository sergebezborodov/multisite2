<?php

define('DS', DIRECTORY_SEPARATOR);
defined('YII_ENABLE_EXCEPTION_HANDLER') or define('YII_ENABLE_EXCEPTION_HANDLER', false);
defined('YII_ENABLE_ERROR_HANDLER') or define('YII_ENABLE_ERROR_HANDLER', false);
defined('YII_DEBUG') or define('YII_DEBUG', true);

$_SERVER['SCRIPT_NAME']     = '/' . basename(__FILE__);
$_SERVER['SCRIPT_FILENAME'] = __FILE__;


define('ROOT', realpath(__DIR__.'/..'));

require ROOT.'/../../../../yii2/framework/yii/Yii.php';



require ROOT.'/Exception.php';
require ROOT.'/Unit.php';
require ROOT.'/BaseRule.php';
require ROOT.'/Manager.php';
require ROOT.'/MultiSite.php';
require ROOT.'/UrlRule.php';

$config = require 'config.php';

$application = new yii\console\Application($config);
Yii::setAlias('@yiiunit', __DIR__);
