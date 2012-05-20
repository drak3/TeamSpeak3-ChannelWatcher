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
class DeleteCommand extends ProfileDependentCommand
{
    
    public function configure() {
        parent::configure();
        $this
            ->setName('delete')
            ->addOption('force', 'f', InputOption::VALUE_NONE)
            ->addArgument('time' , InputArgument::OPTIONAL , 'the time' , null);
    }
    
    public function execute(InputInterface $in, OutputInterface $out) {
        $force = $in->getOption('force');
        if($in->getArgument('time') === null) {
            $time = $this->c['delete_time'];
        }
        else {
            $time = new \DateInterval($in->getArgument('time'));
        }
        $out->writeln('going to delete the following channels:');
        
        $cmd = $this->getApplication()->find('printUnused');
        $args = array('command'=>'printUnused');
        $input = new \Symfony\Component\Console\Input\ArrayInput($args);
        $cmd->run($input, $out);
        if($force || $this->getHelper('dialog')->askConfirmation($out, '<question>are you sure you want to delete this channels (y/n)?</question> ')) {
            $out->writeln('deleting...');
            $this->c['deleter']->delete($time);
        } else {
            $out->writeln('aborting');
        }
        
    }
    
}

?>
