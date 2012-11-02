<?php

namespace devmx\ChannelWatcher\Tests;

use devmx\Teamspeak3\Query\Command;
use devmx\Teamspeak3\Query\Response\CommandResponse;
use devmx\ChannelWatcher\ChannelDeleter;

/**
 * Test class for ChannelDeleter.
 * Generated by PHPUnit on 2012-05-20 at 18:34:09.
 */
class ChannelDeleterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ChannelDeleter
     */
    protected $deleter;

    protected $storage;

    /**
     *
     * @var \devmx\Teamspeak3\Query\Transport\QueryTransportStub
     */
    protected $transport;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->transport = new \devmx\Teamspeak3\Query\Transport\QueryTransportStub();
        $this->storage = $this->getMockForAbstractClass('\devmx\ChannelWatcher\Storage\StorageInterface');
        $this->deleter = new ChannelDeleter($this->transport, $this->storage);
    }

    protected function getRule()
    {
        return $this->getMockForAbstractClass('\devmx\ChannelWatcher\Rule\RuleInterface');
    }

    /**
     * @covers devmx\ChannelWatcher\ChannelDeleter
     */
    public function testAddRule()
    {
        $r = $this->getRule();
        $this->deleter->addRule($r);
        $this->assertEquals(array($r), $this->deleter->getRules());
    }

    /**
     * @covers devmx\ChannelWatcher\ChannelDeleter
     */
    public function testSetRules()
    {
        $rules = array($this->getRule(), $this->getRule());
        $this->deleter->setRules($rules);
        $this->assertEquals($rules, $this->deleter->getRules());
    }

    /**
     * @covers devmx\ChannelWatcher\ChannelDeleter
     */
    public function testRemoveRule()
    {
        $r1 = $this->getRule();
        $r2 = $this->getRule();
        $this->deleter->setRules(array($r1, $r2));
        $this->deleter->removeRule($r2);
        $this->assertEquals(array($r1), $this->deleter->getRules());
    }

    /**
     * @covers devmx\ChannelWatcher\ChannelDeleter
     */
    public function testGetIdsToDelete_noFilter()
    {
        $this->storage->expects($this->once())
                      ->method('getChannelsEmptyFor')
                      ->will($this->returnValue(array(1,2)));
        $this->assertEquals(array(1,2), $this->deleter->getIdsToDelete(new \DateInterval('P1Y')));
    }
    
    /**
     * @covers devmx\ChannelWatcher\ChannelDeleter
     */
    public function testGetIdsToDelete_Filter()
    {
        $idsToDelete = array(2,6);
        $channelList = array(
            array(
                'cid' => 2,
            ),
            array(
                'cid' => 5,
            ),
            array(
                'cid' => 6,
            ),
            array(
                'cid' => 8,
            )
        );
        $expectedToFilter = array(
            2 => array(
                'cid' => 2,
                '__delete' => true
            ),
            5 => array(
                'cid' => 5,
                '__delete' => false
            ),
            6 => array(
                'cid' => 6,
                '__delete' => true
            ),
            8 => array(
                'cid' => 8,
                '__delete' => false
            )
        );
        $filtered = $expectedToFilter;
        $filtered[5]['__delete'] = true;
        $filtered[2]['__delete'] = false;
        $expectedIds = array(5,6);
        $rule = $this->getRule();
        $this->deleter->addRule($rule);

        $this->storage->expects($this->once())
                      ->method('getChannelsEmptyFor')
                      ->will($this->returnValue($idsToDelete));
        $this->transport->addResponse(new CommandResponse(new Command('channellist'), $channelList));
        $this->transport->connect();
        $rule->expects($this->once())
             ->method('filter')
             ->with($this->equalTo($expectedToFilter))
             ->will($this->returnValue($filtered));
        $this->assertEquals($expectedIds, $this->deleter->getIdsToDelete(new \DateInterval('P1Y')));
    }

}
