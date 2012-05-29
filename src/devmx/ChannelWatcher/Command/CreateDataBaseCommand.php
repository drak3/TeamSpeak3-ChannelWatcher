<?php
namespace devmx\ChannelWatcher\Command;
use devmx\ChannelWatcher\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
/**
 *
 * @author drak3
 */
class CreateDataBaseCommand extends ProfileDependentCommand
{
    
    protected function execute(InputInterface $in, OutputInterface $out) {
        $manager = $this->c['db']['db_manager'];
        $manager->createTable($this->c['db']['connection'], $this->c['db']['table_name']);
    }
}

?>
