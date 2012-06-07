<?php

namespace devmx\ChannelWatcher;

require_once dirname( __FILE__ ) . '/../../../../src/devmx/ChannelWatcher/ChannelTree.php';

/**
 * Test class for ChannelTree.
 * Generated by PHPUnit on 2012-06-02 at 16:32:08.
 */
class ChannelTreeTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * @covers devmx\ChannelWatcher\ChannelTree::channelHasChildWith
     * @todo Implement testChannelHasChildWith().
     */
    public function testChannelHasChildWith()
    {
        $list = array(
          1 => array(
              'cid' => 1,
              'pid' => 0,
          ),
          2 => array(
              'cid' => 2,
              'pid' => 0,
          ),
          3 => array(
              'cid' => 3,
              'pid' => 1,
          ),
          4 => array(
              'cid' => 4,
              'pid' => 1,
              'foo' => true,
          ),
          5 => array(
              'cid' => 5,
              'pid' => 2,
          )
        );
        $tree = new ChannelTree($list);
        $this->assertTrue($tree->channelHasChildWith(1, function($c){
            return isset($c['foo']);
        }));
    }

    /**
     * @covers devmx\ChannelWatcher\ChannelTree::getChildsOf
     * @todo Implement testGetChildsOf().
     */
    public function testGetChildsOf()
    {
        $list = array(
          1 => array(
              'cid' => 1,
              'pid' => 0,
          ),
          2 => array(
              'cid' => 2,
              'pid' => 0,
          ),
          3 => array(
              'cid' => 3,
              'pid' => 1,
          ),
          4 => array(
              'cid' => 4,
              'pid' => 1,
          ),
          5 => array(
              'cid' => 5,
              'pid' => 2,
          )
        );
        $tree = new ChannelTree($list);
        $this->assertEquals(array(array('cid' => 3, 'pid'=>1), array('cid'=>4, 'pid'=>1)), $tree->getChildsOf(1));
    }

}

?>