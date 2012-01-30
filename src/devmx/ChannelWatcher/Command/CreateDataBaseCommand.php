<?php
namespace devmx\ChannelWatcher\Command;
use devmx\ChannelWatcher\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
/**
 *
 * @author drak3
 */
class CreateDataBaseCommand extends ContainerAwareCommand
{
    protected function configure() {
        $this->setName('database:create_table');
    }
    
    protected function execute(InputInterface $in, OutputInterface $out) {
        $manager = $this->container->get('dbal.manager');
        $manager->createTable($this->container->get('dbal.connection'), $this->container->getParameter('dbal.tablename'));
    }
}

?>
