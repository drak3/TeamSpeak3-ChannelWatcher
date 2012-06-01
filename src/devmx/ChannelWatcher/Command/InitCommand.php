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
