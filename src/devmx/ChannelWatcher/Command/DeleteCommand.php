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
            ->addOption('force', 'f', InputOption::VALUE_NONE);
    }
    
    public function execute(InputInterface $in, OutputInterface $out) {
        $force = $in->getOption('force');
        $time = $this->c['delete_time'];
        $out->writeln('going to delete the following channels:');
        
        $cmd = $this->c['command.print_unused'];
        $args = array('config'=>$this->c['app.profile']);
        $input = new \Symfony\Component\Console\Input\ArrayInput($args);
        $cmd->run($input, $out);
        if($force || $this->getHelper('dialog')->askConfirmation($out, '<question>are you sure you want to delete this channels (y/N)?</question> ', false)) {
            $out->writeln('deleting...');
            $this->c['deleter']->delete($time);
        } else {
            $out->writeln('aborting');
        }
        
    }
    
}

?>
