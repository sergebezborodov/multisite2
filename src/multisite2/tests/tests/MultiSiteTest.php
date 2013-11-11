<?php

namespace tests;

use multisite2\Manager;
use multisite2\MultiSite;
use multisite2\UrlRule;

class CustomUrlRule extends UrlRule {}

/**
 * Test cases for multisite instance
 */
class TestMultiSite extends \yii\test\TestCase
{
    public function testCreate()
    {
        $multisite = new MultiSite(new Manager);
        $multisite->name = 'test-site';
        $multisite->id = 1;
        $multisite->attributes = array('attr1' => 'value1');
        $multisite->rule = 'site.ru';
        $multisite->init();

        $this->assertEquals('test-site', $multisite->name);
        $this->assertEquals(1, $multisite->id);
        $this->assertEquals(array('attr1' => 'value1'), $multisite->attributes);
    }

    public function testRule()
    {
        $multisite = new MultiSite(new Manager);
        $multisite->rule = 'site.ru';
        $multisite->init();

        $rule = $multisite->rule;
        $this->assertInstanceOf('multisite2\UrlRule', $rule);
        $this->assertEquals('site.ru', $rule->url);

        $multisite->rule = array(
            'url' => 'site1.ru',
        );
        $rule = $multisite->rule;
        $this->assertInstanceOf('multisite2\UrlRule', $rule);
        $this->assertEquals('site1.ru', $rule->url);

        $multisite->rule = array(
            'class' => 'tests\CustomUrlRule',
            'url' => 'site2.ru',
        );
        $rule = $multisite->rule;
        $this->assertInstanceOf('tests\CustomUrlRule', $rule);
        $this->assertEquals('site2.ru', $rule->url);
    }

    public function testRules()
    {
        $multisite = new MultiSite(new Manager);
        $multisite->rules = array(
            array(
                'class' => 'tests\CustomUrlRule',
                'url' => 'site1.ru',
            ),
            array(
                'class' => 'multisite2\UrlRule',
                'url' => 'site2.ru',
            ),
        );
        $multisite->init();

        $this->assertCount(2, $multisite->rules);

        list($rule1, $rule2) = $multisite->rules;
        $this->assertInstanceOf('tests\CustomUrlRule', $rule1);
        $this->assertEquals('site1.ru', $rule1->url);
        $this->assertInstanceOf('multisite2\UrlRule', $rule2);
        $this->assertEquals('site2.ru', $rule2->url);
    }

    public function testRuleMath()
    {
        $multisite = new MultiSite(new Manager);
        $multisite->rules = array(
            array(
                'class' => 'tests\CustomUrlRule',
                'url'   => 'site1.ru',
            ),
        );
        $multisite->init();

        $_SERVER['HTTP_HOST'] = 'site1.ru';
        $this->assertTrue($multisite->getIsCurrent());

        $multisite->rules = array(
            array(
                'class' => 'tests\CustomUrlRule',
                'url' => 'site1.ru',
            ),
            array(
                'class' => 'multisite2\UrlRule',
                'url' => 'site2.ru',
            ),
        );
        $this->assertFalse($multisite->getIsCurrent());
    }
}
