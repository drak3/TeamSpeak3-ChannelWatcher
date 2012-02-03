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
        $crawler = $this->container->get('crawler');
        $time = \time();
        $crawler->crawl();
        $this->container->get('teamspeak.query')->disconnect();
        $crawler->updateStorage($this->container->get('storage'), $time);
    }
}

?>
