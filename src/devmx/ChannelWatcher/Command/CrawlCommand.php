<?php
namespace devmx\ChannelWatcher\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 * @author drak3
 */
class CrawlCommand extends ContainerAwareCommand
{
    protected function configure() {
        $this->setName('crawl');
    }
    
    protected function execute(InputInterface $in, OutputInterface $out) {
        $crawler = $this->c['crawler'];
        $time = new \DateTime('now');
        $crawler->crawl();
        $this->c['ts3']['query.transport']->disconnect();
        $crawler->updateStorage($this->c['storage'], $time);
    }
}

?>
