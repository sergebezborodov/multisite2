<?php


namespace multisite2;

use yii\base\Object;

/**
 * Multisite unit
 */
class Unit extends Object
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string type name
     */
    public $type;

    /**
     * @var array
     */
    public $attributes = array();

    /**
     * Init unit instance
     */
    public function init()
    {}
}