<?php
namespace devmx\ChannelWatcher\Command;
use devmx\ChannelWatcher\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
/**
 *
 * @author drak3
 */
class ToDeletePrintCommand extends ContainerAwareCommand
{
    protected function configure() {
        $this->setName('print_to_delete');
        $this->getDefinition()->addArgument(new InputArgument('time' , self::optional , 'the time' , 60));
    }
    
    protected function execute(InputInterface $in, OutputInterface $out) {
        var_dump($this->container->get('storage')->getChannelsEmptyFor($in->getArgument('time')));
    }
}

?>
