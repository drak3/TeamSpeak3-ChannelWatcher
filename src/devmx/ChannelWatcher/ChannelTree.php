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

namespace devmx\ChannelWatcher;

/**
 * TODO: replace this with node implementation
 * @author drak3
 */
class ChannelTree
{
    protected $channels;
    
    public function __construct(array $channels) {
        $this->channels = $channels;
    }
    
    public function channelHasChildWith($cid, $predicate) {
        foreach($this->getChildsOf($cid) as $child) {
            if($predicate($child) || $this->channelHasChildWith( $child['cid'], $predicate)) {
                return true;
            }
        }
        return false;
    }
    
    public function getChildsOf($cid) {
        $ret = array();
        foreach($this->channels as $channel) {
            if($channel['pid'] === $cid) {
                $ret[] = $channel;
            }
        }
        return $ret;
    }
}

?>
