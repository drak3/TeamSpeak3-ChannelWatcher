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

namespace devmx\ChannelWatcher\Storage;

/**
 *
 * @author drak3
 */
interface StorageInterface {

    /**
     * Updates the last time seen date of a chanel
     * @param $id int the id of the channel
     * @param $time int the unix timestanp of the last seen time defaults to now 
     */
    public function update($id, $isVisited, \DateTime $time);

    /**
     * Returns all channel ids which are empty for a given time 
     * @param $time int the time in seconds 
     */
    public function getChannelsEmptyFor(\DateInterval $time, \DateTime $now = null);
}

?>
