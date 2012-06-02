<?php
<<<<<<< HEAD

/**
 * This file is part of the Teamspeak3 ChannelWatcher.
 * Copyright (C) 2012 drak3 <drak3@live.de>
 * Copyright (C) 2012 Maxe <maxe.nr@live.de>
 * 
 * The Teamspeak3 ChannelWatcher is free software: you can redistribute it and/or modify
=======
/**
 * This file is part of the Teamspeak3-ChannelWatcher.
 * 
 * The Teamspeak3-ChannelWatcher is free software: you can redistribute it and/or modify
>>>>>>> feature/save_delete
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
<<<<<<< HEAD
 * The Teamspeak3 ChannelWatcher is distributed in the hope that it will be useful,
=======
 * The Teamspeak3-ChannelWatcher is distributed in the hope that it will be useful,
>>>>>>> feature/save_delete
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
<<<<<<< HEAD
 * along with the Teamspeak3 ChannelWatcher.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */

=======
 * along with the Teamspeak3-ChannelWatcher.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */
>>>>>>> feature/save_delete
namespace devmx\ChannelWatcher\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 * @author drak3
 */
class ProfileDependentCommand extends ContainerAwareCommand {

    protected function configure() {
        $this->addArgument('config', InputArgument::REQUIRED, 'the config of the server to interact with');
    }

    protected function initialize(InputInterface $in, OutputInterface $out) {
        $in->validate();
        $this->c['profile'] = $in->getArgument('config');
        $this->c['profile.loader']();
    }

}

?>
