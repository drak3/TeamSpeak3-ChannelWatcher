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
 *
 * @author drak3
 */
interface RuleInterface
{
    /**
     * Filters the channellist that should be deleted
     * The filter method must change the '__delete' key of each channel entry to specify if the channel should be deleted (true<=>yes)
     * The filter method must not delete entries of the channellist
     * @param  array $channelList
     * @return array the filtered Channellist
     */
    public function filter(array $channelList);
}
