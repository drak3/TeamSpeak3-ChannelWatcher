<?php
namespace devmx\ChannelWatcher\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 * @author drak3
 */
class CrawlCommand extends ProfileDependentCommand
{
    
    protected function execute(InputInterface $in, OutputInterface $out) {
        $crawler = $this->c['watcher']['crawler'];
        $time = new \DateTime('now');
        $crawler->crawl();
        $this->c['ts3']['query']->quit();
        $crawler->updateStorage($this->c['watcher']['storage'], $time);
    }
}

?>
