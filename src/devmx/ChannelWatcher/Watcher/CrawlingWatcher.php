<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace devmx\ChannelWatcher\Watcher;
use devmx\ChannelWatcher\ChannelCrawler;
use devmx\ChannelWatcher\Storage\StorageInterface;

/**
 *
 * @author drak3
 */
class CrawlingWatcher implements WatcherInterface
{
    protected $crawlTime;
    
    /**
     * @var ChannelCrawler
     */
    protected $crawler;
    
    public function __construct(ChannelCrawler $crawler, \DateInterval $crawlTime) {
        $this->crawlTime = $crawlTime;
        $this->crawler = $crawler;
    }
    
    public function watch(StorageInterface $storage) {
        while(true) {
            $this->crawler->crawl();
            $this->crawler->updateStorage($storage);
            $this->sleep($this->crawlTime);
        }
    }
    
    protected function sleep(\DateInterval $time) {
        $seconds = \devmx\ChannelWatcher\DateConverter::convertIntervalToSeconds($time);
        \sleep($seconds);
    }
}

?>
