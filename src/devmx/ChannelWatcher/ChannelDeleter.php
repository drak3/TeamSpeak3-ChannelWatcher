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
use devmx\ChannelWatcher\Storage\StorageInterface;
use devmx\ChannelWatcher\Rule\RuleInterface;
use devmx\Teamspeak3\Query\CommandAwareQuery;

/**
 *
 * @author drak3
 */
class ChannelDeleter
{
    /**
     * @var array of RuleInterface
     */
    protected $rules = array();

    /**
     * @var TransportInterface
     */
    protected $query;

    /**
     * @var StorageInterface
     */
    protected $storage;

    /**
     * Constructor
     * @param TransportInterface $transport
     * @param StorageInterface   $storage
     */
    public function __construct(TransportInterface $transport, StorageInterface $storage)
    {
        if ($transport instanceof CommandAwareQuery) {
            $this->query = $transport;
        } else {
            $this->query = new CommandAwareQuery($transport);
        }
        $this->query->exceptionOnError(true);
        $this->storage = $storage;
    }

    /**
     * Adds a rule
     * @param RuleInterface $rule
     */
    public function addRule(RuleInterface $rule)
    {
        $this->rules[] = $rule;
    }

    /**
     * Sets the rules
     * @param array $rules
     */
    public function setRules(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * Removes a specific rule
     * @param RuleInterface $rule
     */
    public function removeRule(RuleInterface $rule)
    {
        foreach ($this->rules as $name => $r) {
            if ($r === $rule) {
                unset($this->rules[$name]);
            }
        }
    }

    /**
     * Return the currently selected rules
     * @return array of RuleInterface
     */
    public function getRules()
    {
        return $this->rules;
    }

    public function getIdsToDelete(\DateInterval $emptyFor, \DateTime $now = null)
    {
        $ids = $this->storage->getChannelsEmptyFor($emptyFor, $now);

        return $this->filter($ids);
    }

    public function willDeleteNonEmptyChannel(\DateInterval $emptyFor, \DateTime $now=null)
    {
        $ids = $this->getIdsToDelete($emptyFor, $now);
        $channels = $this->query->channelList()->toAssoc('cid');
        foreach ($ids as $cid) {
            if ($this->channelHasClients($channels[$cid], $channels)) {
                return true;
            }
        }

        return false;
    }

    protected function channelHasClients(array $channel, array $channels)
    {
        $tree = new ChannelTree($channels);

        return($channel['total_clients'] > 0 || $tree->channelHasChildWith($channel['cid'], function($channel){
            $channel['total_clients'] > 0;
        }));
    }

    public function delete(\DateInterval $emptyFor, $minimumCrawlsPerHour=null, $force=false, \DateTime $now = null)
    {
        if ($minimumCrawlsPerHour !== null) {
            $this->checkDataValidity($emptyFor, $minimumCrawlsPerHour, $now);
        }
        $toDelete = $this->getIdsToDelete($emptyFor, $now);
        $list = $this->query->channelList();
        $currentIDs = array_keys($list->toAssoc('cid'));
        foreach ($toDelete as $id) {
            if (in_array($id, $currentIDs)) {
                $this->deleteChannel($id, $force);
            }
        }
    }

    protected function deleteChannel($id, $force)
    {
        try {
            $this->query->channelDelete($id, $force);
        } catch (\devmx\Teamspeak3\Query\Exception\CommandFailedException $e) {
            if ($e->getResponse()->getErrorID() === 772) {
                //catching the cause that there was someone in the channel we tried to delete
                throw new Deleter\ChannelNotEmptyException($e->getResponse());
            }
            if ($e->getResponse()->getErrorID() !== 768) {
                throw $e;
            }
            //we can savely ignore a invalid channel id, as this is most likely caused by a already deleted subchannel
        }
    }

    protected function filter(array $ids)
    {
        if ($this->rules === array()) {
            return $ids;
        }
        $channelList = $this->query->channelList()->toAssoc('cid');
        foreach ($channelList as $id => $channel) {
            if (  in_array( $id, $ids )) {
                $channelList[$id]['__delete'] = true;
            } else {
                $channelList[$id]['__delete'] = false;
            }
        }
        foreach ($this->rules as $rule) {
            $channelList = $rule->filter($channelList);
        }
        $filteredIds = array();
        foreach ($channelList as $id => $c) {
            if ($c['__delete']) {
                $filteredIds[] = $id;
            }
        }

        return $filteredIds;
    }

    public function checkDataValidity(\DateInterval $crawlsSince, $minimumCrawlsPerHour, $now=null)
    {
        $crawls = $this->storage->getCrawlDatesOccuredIn($crawlsSince , $now);
        $seconds = DateConverter::convertIntervalToSeconds($crawlsSince);
        $hours = $seconds / 60 * 60;
        $crawlsPerHour = count($crawls) / $hours;
        if ($crawlsPerHour < $minimumCrawlsPerHour) {
            throw new Deleter\NotEnoughCrawlsException(sprintf('There were just %.1f crawls per hour. At least %.1f crawls per hour are needed', $crawlsPerHour, $minimumCrawlsPerHour));
        }
    }

}
