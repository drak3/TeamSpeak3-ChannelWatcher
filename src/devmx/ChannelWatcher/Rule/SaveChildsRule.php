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

namespace devmx\ChannelWatcher\Rule;

/**
 * This rule saves a channel if a parent up to the specific level was visited
 * By default any parent is taken into a
 * @author drak3
 */
class SaveChildsRule implements RuleInterface
{
    protected $level = 10E6;

    public function setLevel($level)
    {
        if ($level < 0) {
            $level = 10E6;
        }
        $this->level = $level;
    }

    public function filter(array $channels)
    {
        $unchangedChannels = $channels;
        foreach ($channels as $cid => $channel) {
            if ($this->channelHasSaveParent($channel, $unchangedChannels, $this->level)) {
                $channels[$cid]['__delete'] = false;
            }
        }

        return $channels;
    }

    protected function channelHasSaveParent(array $channel, array $channels, $level)
    {
        $pid = $channel['pid'];
        if ($pid === 0 || $level < 1) {
            return !$channel['__delete'];
        }

        return $this->channelHasSaveParent($channels[$pid], $channels, $level - 1);
    }

}
