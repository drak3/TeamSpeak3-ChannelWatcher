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

use devmx\ChannelWatcher\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

/**
 *
 * @author drak3
 */
class PrintUnusedCommand extends ProfileDependentCommand {

    protected function execute(InputInterface $in, OutputInterface $out) {
        $time = $this->c['watcher']['delete_time'];
        $unused = $this->c['watcher']['deleter']->getIdsToDelete($time);
        $channellist = $this->c['ts3']['query']->channelList()->toAssoc('cid');
        foreach ($unused as $id) {
            if (!isset($channellist[$id])) {
                $out->writeln("Channel with id $id is not on the server anymore, ignoring");
            } else {
                $out->writeln($channellist[$id]['channel_name'] . ' (' . $id . ')');
            }
        }
        $out->writeln(sprintf('%d in total', count($unused)));
    }

}

?>
