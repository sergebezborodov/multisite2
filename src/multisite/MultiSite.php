<?php


namespace multisite2;

use yii\base\Component;

/**
 * Multisite instance
 *
 * For each multisite can exist one or more rules
 * If multisite require only one rule, for example
 * url rule - site.ru. It must be set 'rule' property.

 * Each rule can be specify with string (simply url)
 * or with array. It means as standart yii component.
 *
 * If MS requires two or more rule, set 'rules' property.
 *
 * @property string|array $rule
 * @property string|array $rules
 * @property bool $isCurrent
 * @property Unit[] $units
 */
class MultiSite extends Component
{
    /**
     * @var int multi site id
     */
    public $id;

    /**
     * @var string multi site name
     */
    public $name;

    /**
     * @var array config for rules array
     */
    protected $rulesConfig;

    /**
     * @var array rules array for checking multisite
     */
    protected $_rules;

    /**
     * @var array additional attributes
     */
    public $attributes;

    /**
     * @var array
     */
    protected $unitsConfig;

    /**
     * @var array
     */
    protected $_units;

    /**
     * @var bool
     */
    protected $_isCurrent;

    /**
     * @var Manager
     */
    protected $_manager;

    const URL_RULE_CLASS = 'UrlRule';

    public function __construct($manager, $config = [])
    {
        parent::__construct($config);
        $this->_manager = $manager;
    }

    /**
     * Init instance
     */
    public function init()
    {}

    /**
     * @return bool is current multisite
     */
    public function getIsCurrent()
    {
        $lastResult = true;
        foreach ($this->getRules() as $rule) {
            $lastResult = $lastResult && $rule->getIsMath();
            if (!$lastResult) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array|string $rule
     */
    public function setRule($rule)
    {
        $this->setRules(array($rule));
    }

    /**
     * Return first url rule
     *
     * @return BaseRule
     */
    public function getRule()
    {
        if (($rules = $this->getRules()) != null) {
            return $rules[0];
        }
        return null;
    }

    /**
     * @param array $rules
     */
    public function setRules($rules)
    {
        $this->_rules = null;
        $this->rulesConfig = $rules;
    }

    /**
     * @return BaseRule[]
     */
    public function getRules()
    {
        if ($this->_rules != null) {
            return $this->_rules;
        }

        if ($this->rulesConfig == null) {
            return null;
        }

        $rules = array();
        foreach ($this->rulesConfig as $key => $config) {
            if (is_string($config)) {
                $config = array(
                    'class' => self::URL_RULE_CLASS,
                    'url'   => $config,
                );
            } else if (empty($config['class'])) {
                $config['class'] = self::URL_RULE_CLASS;
            }

            /** @var BaseRule $rule */
            // TODO: change!!!
            $rule = Yii::createComponent($config);
            $rule->init();

            $rules[] = $rule;
        }

        return $this->_rules = $rules;
    }

    /**
     * Return array of multisite units
     * unit type => unit instance
     *
     * @return array
     */
    public function getUnits()
    {
        if ($this->_units == null) {
            $units = array();
            foreach ($this->unitsConfig as $type => $name) {
                $unit = $this->_manager->findUnitByIdOrName($name, $type);
                if ($unit == null) {
                    throw new Exception("Unit type '{$type}' with '$name' not found ".
                        "for multisite '{$this->name}'");
                }
                $units[$type] = $unit;
            }
            $this->_units = $units;
        }
        return $this->_units;
    }

    /**
     * Sets units config
     *
     * @param array $units
     */
    public function setUnits($units)
    {
        $this->_units = null;
        $this->unitsConfig = $units;
    }

    /**
     * Returns multisite unit by it type
     *
     * @param string $type
     * @return Unit|null
     */
    public function getUnit($type)
    {
        $units = $this->getUnits();
        return isset($units[$type]) ? $units[$type] : null;
    }
}