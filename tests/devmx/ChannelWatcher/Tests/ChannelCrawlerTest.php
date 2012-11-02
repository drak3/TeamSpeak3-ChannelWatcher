<?php
namespace devmx\ChannelWatcher\Tests;
use devmx\ChannelWatcher\Storage\InMemoryStorage;
use devmx\Teamspeak3\Query\Transport\QueryTransportStub;
use devmx\Teamspeak3\Query\Response\CommandResponse;
use devmx\Teamspeak3\Query\Command;
use devmx\ChannelWatcher\ChannelCrawler;


/**
 * Test class for Crawler.
 * Generated by PHPUnit on 2012-01-30 at 16:43:36.
 */
class CrawlerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \devmx\ChannelWatcher\ChannelCrawler
     */
    protected $crawler;

    /**
     * @var \devmx\Teamspeak3\Query\Transport\QueryTransportStub;
     */
    protected $query;

    /**
     * @var \devmx\ChannelWatcher\Storage\InMemoryStorage;
     */
    protected $storage;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->storage = new InMemoryStorage();
        $this->query = new QueryTransportStub();

    }

     /**
      * @covers devmx\ChannelWatcher\ChannelCrawler
      */
    public function testCrawl()
    {
        $this->crawler  = new ChannelCrawler($this->query, false);
        $r = new CommandResponse(new Command('channellist'), $this->getChannelListItems());
        $this->query->addResponse($r,2);
        $this->query->connect();

        //first run
        $this->crawler->crawl();
        $time = new \DateTime();
        $time->setTimeStamp(123);
        $this->crawler->updateStorage($this->storage, $time);
        $this->assertEquals(array(
            1 => $time,
            2 => $time,
            3 => $time,
            4 => $time,
        ), $this->storage->getChannels());
        //second run
        $this->crawler->crawl();
        $time2 = new \DateTime();
        $time2->setTimestamp(234);
        $this->crawler->updateStorage($this->storage, $time2);
        $this->assertEquals(array(
           1 => $time,
           2 => $time2,
           3 => $time,
           4 => $time2
        ), $this->storage->getChannels());
    }
    
    /**
     * @covers devmx\ChannelWatcher\ChannelCrawler
     */
    public function testCrawl_ignoreQueryClients()
    {
        $this->crawler  = new ChannelCrawler($this->query, true);
        $r = new CommandResponse(new Command('channellist'), $this->getChannelListItems());
        $r2 = new CommandResponse(new Command('clientlist'), $this->getClientListItems());
        $this->query->addResponse($r, 2);
        $this->query->addResponse($r2, 2);
        $this->query->connect();
        $this->crawler->crawl();
        $time = new \DateTime();
        $time->setTimeStamp(123);
        $this->crawler->updateStorage($this->storage, $time);
        $this->assertEquals(array(
            1 => $time,
            2 => $time,
            3 => $time,
            4 => $time,
        ), $this->storage->getChannels());
        //second run
        $this->crawler->crawl();
        $time2 = new \DateTime();
        $time2->setTimestamp(234);
        $this->crawler->updateStorage($this->storage,$time2);
        $this->assertEquals(array(
           1 => $time,
           2 => $time2,
           3 => $time,
           4 => $time
        ), $this->storage->getChannels());
    }

    protected function getChannelListItems()
    {
        return array(
          array(
              'cid' => 1,
              'total_clients' => 0,
              'pid' => 0,
          ),
          array(
              'cid' => 2,
              'total_clients' => 3,
              'pid' => 0,
          ),
          array(
              'cid' => 3,
              'total_clients' => 0,
              'pid' => 0
          ),
          array(
              'cid' => 4,
              'total_clients' => 1,
              'pid' => 3
          )
        );
    }

    protected function getClientListItems()
    {
        return array(
            array(
                'clid' => 12,
                'cid' => 4,
                'client_type' => 1
            ),
            array(
                'clid' => 13,
                'cid' => 2,
                'client_type' => 0,
            ),
            array(
                'clid' => 14,
                'cid' => 2,
                'client_type' => 0,
            ),
            array(
                'clid' => 15,
                'cid' => 2,
                'client_type' => 0,
            ),
            array(
                'clid' => 16,
                'cid' => 2,
                'client_type' => 0,
            )
        );
    }

}
