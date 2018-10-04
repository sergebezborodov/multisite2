<?php


namespace multisite2;


/**
 * Base rule for matching
 */
abstract class BaseRule extends \yii\base\BaseObject
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
