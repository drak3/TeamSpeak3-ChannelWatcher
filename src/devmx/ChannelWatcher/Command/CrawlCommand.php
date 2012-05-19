<?php
namespace devmx\ChannelWatcher\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use \devmx\ChannelWatcher\ChannelCrawler;

/**
 *
 * @author drak3
 */
class CrawlCommand extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var ChannelCrawler 
     */
    protected $crawler;
    
    public function __construct($crawler) {
        $this->crawler = $crawler;
    }
    
    protected function configure() {
        $this->setName('crawl');
    }
    
    protected function execute(InputInterface $in, OutputInterface $out) {
        $this->crawler->crawl();
    }
}

?>
