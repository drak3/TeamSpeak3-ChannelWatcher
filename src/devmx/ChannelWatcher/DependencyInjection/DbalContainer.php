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

namespace devmx\ChannelWatcher\DependencyInjection;

/**
 *
 * @author drak3
 */
class DbalContainer extends \Pimple {

    public function __construct() {

        //depends on table_name

        /**
         * The db connection
         * depends on connection.params 
         */
        $this['connection'] = $this->share(function($c) {
                    return \Doctrine\DBAL\DriverManager::getConnection($c['connection.params']);
                });

        /**
         * Manager to create and configure database/tables
         */
        $this['db_manager'] = $this->share(function($c) {
                    return new \devmx\ChannelWatcher\Storage\DbalStorage\DataBaseManager;
                });

        /**
         * The DbalStorage that provides a simple interface to the channel stats 
         */
        $this['storage'] = $this->share(function($c) {
                    return new \devmx\ChannelWatcher\Storage\DbalStorage\DbalStorage($c['connection'], $c['table_name']);
                });
    }

}

?>
