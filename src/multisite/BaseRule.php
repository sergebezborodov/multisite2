<?php


namespace multisite;

use yii\base\Object;

/**
 * Base rule for matching
 */
abstract class BaseRule extends Object
{
    /**
     * Init instance
     */
    public function init()
    {}

    /**
     * @return bool is current request math rule
     */
    public abstract function getIsMath();
}