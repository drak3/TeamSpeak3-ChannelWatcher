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
        if(!$this->wasInited()) {
            $this->init($out);
        }
        $crawler = $this->c['watcher']['crawler'];
        $time = new \DateTime('now');
        $crawler->crawl();
        $this->c['ts3']['query']->quit();
        $crawler->updateStorage($this->c['watcher']['storage'], $time);
    }
    
    protected function wasInited() {
        return is_dir($this->c['storagedir']);
    }
    
    protected function init(OutputInterface $out) {
        $initCommand = $this->c['command.init'];
        $args = array('config'=>$this->c['profile']);
        $input = new \Symfony\Component\Console\Input\ArrayInput($args);
        $initCommand->run($input, $out);
    }
}

?>
