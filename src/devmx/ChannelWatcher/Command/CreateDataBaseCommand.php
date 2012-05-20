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
    protected function configure() {
        parent::configure();
        $this->setName('database:create_table');
    }
    
    protected function execute(InputInterface $in, OutputInterface $out) {
        $manager = $this->c['dbal.db_manager'];
        $manager->createTable($this->c['dbal.connection'], $this->c['dbal.table_name']);
    }
}

?>
