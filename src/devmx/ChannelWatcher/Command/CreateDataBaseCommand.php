<?php

/**
 * This file is part of the Teamspeak3 ChannelWatcher.
 * Copyright (C) 2012 drak3 <drak3@live.de>
 * Copyright (C) 2012 Maxe <maxe.nr@live.de>
 *
 * The Teamspeak3 ChannelWatcher is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * The Teamspeak3 ChannelWatcher is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with the Teamspeak3 ChannelWatcher.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
namespace devmx\ChannelWatcher\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
/**
 *
 * @author drak3
 */
class CreateDataBaseCommand extends ProfileDependentCommand
{

    protected function execute(InputInterface $in, OutputInterface $out)
    {
        $out->writeln('Warning: This command is deprecated, run the <info>init</info> command instead.');
        
        return $this->runInitCommand($out);
    }
    
    private function runInitCommand($out) {
        $command = $this->c['command.init'];
        
        $arguments = array(
            'command' => $command->getName(),
            'profile' => $this->c['profile'],
        );

        $input = new ArrayInput($arguments);
        
        return $command->run($input, $out);
    }
}
