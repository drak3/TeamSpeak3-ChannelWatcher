<?php
namespace devmx\ChannelWatcher\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArgvInput;

/**
 *
 * @author drak3
 */
class DeleteCommand extends ContainerAwareCommand
{
    
    public function configure() {
        $this
            ->setName('delete')
            ->addOption('force', 'f', InputOption::VALUE_NONE)
            ->addArgument('time' , InputArgument::OPTIONAL , 'the time' , null);
    }
    
    public function execute(InputInterface $in, OutputInterface $out) {
        $force = $in->getOption('force');
        if($in->getArgument('time') === null) {
            $time = $this->container->getParameter('channelwatcher.deletetime');
        }
        else {
            $time = new \DateInterval($in->getArgument('time'));
        }
        $out->writeln('going to delete the following channels:');
        
        $this->getApplication()->run(new ArgvInput(array('foo', 'printUnused')), $out);
        if($force || $this->getHelper('dialog')->askConfirmation($out, '<question>are you sure you want to delete this channels (y/n)?</question> ')) {
            $out->writeln('deleting...');
            $this->container->get('deleter')->delete($time);
        }
    }
    
}

?>
