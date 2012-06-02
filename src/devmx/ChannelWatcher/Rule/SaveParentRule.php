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
namespace devmx\ChannelWatcher\Rule;
use devmx\ChannelWatcher\ChannelTree;

/**
 *
 * @author drak3
 */
class SaveParentRule implements RuleInterface
{
    public function filter(array $list) {
        $channels = new ChannelTree($list);
        foreach($list as $cid => $channel) {
            if($this->channelHasSaveChild($channel, $channels)) {
                $list[$cid]['__delete'] = false;
            }
        }
        return $list;
    }
    
    protected function channelHasSaveChild(array $channel, $channels) {
        return $channels->channelHasChildWith($channel['cid'], function($channel) {
            return $channel['__delete'] === false;
        });
    }
}

?>
