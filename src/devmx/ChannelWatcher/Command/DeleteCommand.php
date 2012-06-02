<?php
/**
 * This file is part of the Teamspeak3-ChannelWatcher.
 * 
 * The Teamspeak3-ChannelWatcher is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * The Teamspeak3-ChannelWatcher is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with the Teamspeak3-ChannelWatcher.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */
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
            ->addOption('force', 'f', InputOption::VALUE_NONE)
            ->addOption('delete-non-empty', null, InputOption::VALUE_NONE);
    }
    
    public function execute(InputInterface $in, OutputInterface $out) {
        $force = $in->getOption('force');
        $deleteNonEmpty = $in->getOption('delete-non-empty');
        
        $time = $this->c['watcher']['delete_time'];
        
        if($this->c['watcher']['deleter']->getIDsToDelete($time) === array()) {
            $out->writeln('Nothing to delete...');
            return 0;
        }       
            
        $out->writeln('going to delete the following channels:');
        $this->runPrintUnused($out);

        if($force || $this->getHelper('dialog')->askConfirmation($out, '<question>Are you sure you want to delete this channels (y/N)?</question> ', false)) {
            $out->writeln('deleting...');
            if($deleteNonEmpty) {
                $this->c['watcher']['deleter']->delete($time, true);
            } else {
                try{
                    $this->c['watcher']['deleter']->delete($time, false);
                } catch(\devmx\ChannelWatcher\Deleter\ChannelNotEmptyException $e) {
                    $out->writeln(sprintf('<error>Cannot delete non empty channel with id %s. Make sure the deletion is correct and rerun with --delete-non-empty', $e->getResponse()->getCommand()->getParameter('cid')));
                }                
            }            
        } else {
            $out->writeln('aborting');
        }
        
    }
    
    
    private function runPrintUnused(OutputInterface $out) {
        $cmd = $this->c['command.print_unused'];
        $args = array('config'=>$this->c['profile']);
        $input = new \Symfony\Component\Console\Input\ArrayInput($args);
        $cmd->run($input, $out);
    }
}

?>
