<?php
namespace devmx\ChannelWatcher\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 * @author drak3
 */
class InitCommand extends ProfileDependentCommand
{
    public function execute(InputInterface $in, OutputInterface $out) {
        //init storage dir
        if(!is_dir($this->c['storagedir'])) {
            $out->writeln('Creating storage directory');
            mkdir($this->c['storagedir'], 0775, true);
        }        
        
        $createTableCommand = $this->c['command.create_db'];
        $args = array('config'=>$this->c['profile']);
        $input = new \Symfony\Component\Console\Input\ArrayInput($args);
        $createTableCommand->run($input, $out);
        $out->writeln("Creating database table");
        $createTableCommand->run($input, $out);
    }
}

?>
