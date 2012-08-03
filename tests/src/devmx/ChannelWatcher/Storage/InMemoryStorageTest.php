<?php

namespace devmx\ChannelWatcher\Storage;

require_once dirname( __FILE__ ) . '/../../../../../src/devmx/ChannelWatcher/Storage/InMemoryStorage.php';

/**
 * Test class for InMemoryStorage.
 * Generated by PHPUnit on 2012-01-30 at 11:40:37.
 */
class InMemoryStorageTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \devmx\ChannelWatcher\Storage\InMemoryStorage
     */
    protected $storage;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->storage = new InMemoryStorage();
    }

    /**
     * @covers devmx\ChannelWatcher\Storage\InMemoryStorage::update
     * @covers devmx\ChannelWatcher\Storage\InMemoryStorage::getChannels
     */
    public function testUpdate()
    {
        //first run
        $time = new \DateTime();
        $time->setTimeStamp(123);
        $this->storage->update(1, true, $time);
        $this->storage->update(2, false, $time);
        $this->assertEquals(array(1=>$time, 2=>$time), $this->storage->getChannels());
        //second run
        $time2 = new \DateTime();
        $time2->setTimeStamp(234);
        $this->storage->update(1, false, $time2);
        $this->storage->update(2, true, $time2);
        $this->assertEquals(array(1=>$time, 2=>$time2), $this->storage->getChannels());
    }

    /**
     * @covers devmx\ChannelWatcher\Storage\InMemoryStorage::getChannelsEmptyFor
     * @todo Implement testGetChannelsEmptyFor().
     */
    public function testGetChannelsEmptyFor()
    {
        $time = new \DateTime();
        $time->setTimeStamp(123);
        $time2 = new \DateTime();
        $time2->setTimeStamp(1234);
        $this->storage->update(12, true, $time);
        $this->storage->update(13, true, $time2);
        $interval = new \DateInterval('PT1H5S');
        $this->assertEquals(array(12), $this->storage->getChannelsEmptyFor($interval,  $time2));
    }

}
