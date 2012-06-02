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

use devmx\Teamspeak3\Query\Transport\TransportInterface;
use devmx\Teamspeak3\Query\Command;
use devmx\ChannelWatcher\Storage\StorageInterface;
use devmx\ChannelWatcher\AccessControl\AccessControlerInterface;
use devmx\Teamspeak3\Query\CommandAwareQuery;

/**
 *
 * @author drak3
 */
class ChannelCrawler {

    /**
     * @var \devmx\Teamspeak3\Query\CommandAwareQuery
     */
    protected $query;

    /**
     * @var \devmx\ChannelWatcher\Storage\StorageInterface
     */
    protected $storage;
    protected $ignoreQueryClients;

    /**
     * @var \devmx\ChannelWatcher\AccessControl\AccessControlerInterface
     */
    protected $accessControler;

    public function __construct(TransportInterface $transport, $ignoreQueryClients = true) {
        if ($transport instanceof CommandAwareQuery) {
            $this->query = $transport;
        } else {
            $this->query = new CommandAwareQuery($transport);
        }
        $this->query->exceptionOnError(true);
        $this->ignoreQueryClients = $ignoreQueryClients;
    }

    public function setControlList(AccessControlerInterface $a) {
        $this->accessControler = $a;
    }

    public function crawl() {
        $channelResponse = $this->query->channelList();
        $channels = $channelResponse->getItems();
        if ($this->ignoreQueryClients) {
            $channels = $channelResponse->toAssoc('cid');
            $clients = $this->query->clientList();
            $clients = $clients->getItems();
            foreach ($clients as $client) {
                if ($client['client_type'] === 1) {
                    $channels[$client['cid']]['total_clients']--;
                }
            }
        }
        $this->currentChannels = $channels;
        return $channels;
    }

    public function updateStorage(StorageInterface $storage, \DateTime $time = null, array $channels = array()) {
        if ($time === null) {
            $time = new \DateTime('now');
        }
        if ($channels === array()) {
            $channels = $this->currentChannels;
        }
        foreach ($channels as $channel) {
            if ($this->canAccess($channel)) {
                $storage->update($channel['cid'], $this->hasClients($channel, $channels), $time);
            }
        }
    }

    protected function canAccess($channel) {
        if ($this->accessControler instanceof AccessControlerInterface) {
            return $this->accessControler->canAccess($channel['cid']);
        }
        return true;
    }

    protected function hasClients($channel, $allChannels) {
        if ($channel['total_clients'] > 0) {
            return true;
        }
        return false;
    }

}

?>
