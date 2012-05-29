<?php

namespace devmx\ChannelWatcher\Rule;

require_once dirname( __FILE__ ) . '/../../../../../src/devmx/ChannelWatcher/Rule/SaveParentRule.php';

/**
 * Test class for SaveParentRule.
 * Generated by PHPUnit on 2012-05-21 at 13:54:53.
 */
class SaveParentRuleTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers devmx\ChannelWatcher\Rule\SaveParentRule::filter
     * @todo Implement testFilter().
     */
    public function testFilter_simple()
    {
        $rule = new SaveParentRule();
        $channelList = array(
            1 => array(
                'cid' => 1,
                'pid' => 0,
                '__delete' => false,
            ),
            2 => array(
                'cid' => 2,
                'pid' => 1,
                '__delete' => true,
            ),
            3 => array(
                'cid' => 3,
                'pid' => 1,
                '__delete' => true,
            )
        );
        $expected = $channelList;
        $expected[2]['__delete'] = false;
        $expected[3]['__delete'] = false;
        $this->assertEquals($expected, $rule->filter($channelList));
    }
    
    /**
     * @covers devmx\ChannelWatcher\Rule\SaveParentRule::filter
     * @todo Implement testFilter().
     */
    public function testFilter_nested_noExplicitLevel()
    {
        $rule = new SaveParentRule();
        $channelList = array(
            1 => array(
                'cid' => 1,
                'pid' => 0,
                '__delete' => false,
            ),
            2 => array(
                'cid' => 2,
                'pid' => 1,
                '__delete' => true,
            ),
            3 => array(
                'cid' => 3,
                'pid' => 2,
                '__delete' => true,
            )
        );
        $expected = $channelList;
        $expected[2]['__delete'] = false;
        $expected[3]['__delete'] = false;
        $this->assertEquals($expected, $rule->filter($channelList));
    }
    
    /**
     * @covers devmx\ChannelWatcher\Rule\SaveParentRule::filter
     * @todo Implement testFilter().
     */
    public function testFilter_nested_ExplicitNoLevel()
    {
        $rule = new SaveParentRule();
        $channelList = array(
            1 => array(
                'cid' => 1,
                'pid' => 0,
                '__delete' => false,
            ),
            2 => array(
                'cid' => 2,
                'pid' => 1,
                '__delete' => true,
            ),
            3 => array(
                'cid' => 3,
                'pid' => 2,
                '__delete' => true,
            )
        );
        $rule->setLevel(-1);
        $expected = $channelList;
        $expected[2]['__delete'] = false;
        $expected[3]['__delete'] = false;
        $this->assertEquals($expected, $rule->filter($channelList));
    }
    
    /**
     * @covers devmx\ChannelWatcher\Rule\SaveParentRule::filter
     * @todo Implement testFilter().
     */
    public function testFilter_nested_level()
    {
        $rule = new SaveParentRule();
        $channelList = array(
            1 => array(
                'cid' => 1,
                'pid' => 0,
                '__delete' => false,
            ),
            2 => array(
                'cid' => 2,
                'pid' => 1,
                '__delete' => true,
            ),
            3 => array(
                'cid' => 3,
                'pid' => 2,
                '__delete' => true,
            )
        );
        $rule->setLevel(1);
        $expected = $channelList;
        $expected[2]['__delete'] = false;
        $expected[3]['__delete'] = true;
        $this->assertEquals($expected, $rule->filter($channelList));
    }
    
    /**
     * @covers devmx\ChannelWatcher\Rule\SaveParentRule::filter
     * @todo Implement testFilter().
     */
    public function testFilter_nested_deepLevel()
    {
        $rule = new SaveParentRule();
        $channelList = array(
            1 => array(
                'cid' => 1,
                'pid' => 0,
                '__delete' => false,
            ),
            2 => array(
                'cid' => 2,
                'pid' => 1,
                '__delete' => true,
            ),
            3 => array(
                'cid' => 3,
                'pid' => 2,
                '__delete' => true,
            ),
            4 => array(
                'cid' => 4,
                'pid' => 3,
                '__delete' => true,
            ),
            5 => array(
                'cid' => 5,
                'pid' => 4,
                '__delete' => true
            ),
            6 => array(
                'cid' => 6,
                'pid' => 4,
                '__delete' => true,
            ),
            7 => array(
                'cid' => 7,
                'pid' => 2,
                '__delete' => true,
            ),
        );
        $rule->setLevel(2);
        $expected = $channelList;
        $expected[2]['__delete'] = false;
        $expected[3]['__delete'] = false;
        $expected[7]['__delete'] = false;
        $this->assertEquals($expected, $rule->filter($channelList));
    }
    
    /**
     * @covers devmx\ChannelWatcher\Rule\SaveParentRule::filter
     */
    public function testFilter_doNotSaveChild() {
        $channelList = array(
            1 => array(
                'cid' => 1,
                'pid' => 0,
                '__delete' => false,
            ),
            2 => array(
                'cid' => 2,
                'pid' => 1,
                '__delete' => true
            )
        );
        $rule = new SaveParentRule();
        $this->assertEquals($channelList, $rule->filter($channelList), 'Rule must not change anything if child is going to be deleted');
    }

}

?>
