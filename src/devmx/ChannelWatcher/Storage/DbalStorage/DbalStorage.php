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

namespace devmx\ChannelWatcher\Storage\DbalStorage;

use Doctrine\DBAL\Connection;

/**
 *
 * @author drak3
 */
class DbalStorage implements \devmx\ChannelWatcher\Storage\StorageInterface, \devmx\ChannelWatcher\Storage\InitableStorageInterface
{
    protected $connection;
    private $schemaManager;

    public function __construct(Connection $c, SchemaManager $manager)
    {
        $this->schemaManager = $manager;
        $this->connection = $c;
    }

    /**
     * Updates the last time seen date of a chanel
     * @param $id int the id of the channel
     * @param $time int the unix timestanp of the last seen time defaults to now
     */
    public function update($id, $hasClients, \DateTime $lastSeen = null)
    {
        $id = (int) $id;

        if ($lastSeen === null) {
            $lastSeen = new \DateTime('now');
        }

        if ($this->has($id)) {
            if ($hasClients) {
                $update = $this->connection->prepare('UPDATE ' . $this->schemaManager->getChannelTableName() . ' SET last_seen = ? WHERE id = ?');
                $update->bindValue(1, $lastSeen, 'datetime');
                $update->bindValue(2, $id, 'integer');
                $update->execute();
            }
        } else {
            $update = $this->connection->prepare('INSERT INTO ' . $this->schemaManager->getChannelTableName() . ' (id, last_seen) VALUES (?, ?)');
            $update->bindValue(1, $id, 'integer');
            $update->bindValue(2, $lastSeen, 'datetime');
            $update->execute();
        }
    }

    public function updateLastCrawlTime($now = null)
    {
        if ($now === null) {
            $now = new \DateTime('now');
        }
        $insertQuery = $this->connection->prepare('INSERT INTO '. $this->schemaManager->getCrawlDateTableName(). ' (crawl_time) VALUES (?)' );
        $insertQuery->bindValue(1, $now, 'datetime');
        $insertQuery->execute();
    }

    public function has($id)
    {
        $statement = $this->connection->prepare('SELECT id FROM ' . $this->schemaManager->getChannelTableName() . ' WHERE id=?');
        $statement->bindValue(1, $id, 'integer');
        $statement->execute();

        return count($statement->fetchAll()) > 0;
    }

    /**
     * Returns all channel ids which are empty for a given time
     * @param $time int the time in seconds
     */
    public function getChannelsEmptyFor(\DateInterval $time, \DateTime $now = null)
    {
        if ($now === null) {
            $now = new \DateTime('now');
        }
        $now = clone $now;
        $maxLastSeen = $now->sub($time);
        $query = $this->connection->prepare('SELECT id FROM ' . $this->schemaManager->getChannelTableName() . ' WHERE last_seen <= ?');
        $query->bindValue(1, $maxLastSeen, 'datetime');
        $query->execute();
        $channels = $query->fetchAll(\PDO::FETCH_NUM);
        $channels = array_map(function($item) {
                    return (int) $item[0];
                }, $channels);

        return $channels;
    }

    public function getCrawlDatesOccuredIn(\DateInterval $time, \DateTime $now = null)
    {
        if ($now === null) {
            $now = new \DateTime('now');
        }
       
        $timeSpan = clone $now;
        $timeSpan = $timeSpan->sub($time);

  	$query = $this->connection->prepare('SELECT * FROM ' . $this->schemaManager->getCrawlDateTableName() . ' WHERE crawl_time <= ? AND crawl_time >= ?');
        $query->bindValue(1, $now, 'datetime');
        $query->bindValue(2, $timeSpan, 'datetime');
        $query->execute();
        $dates = $query->fetchAll(\PDO::FETCH_NUM);
        $dates = array_map(function($item) {
                    return (int) $item[0];
                }, $dates);

        return $dates;
    }
    
    public function isInited() 
    {
        return $this->schemaManager->schemaIsCreated();
    }
    
    public function init() 
    {
        $this->schemaManager->createTables();
    }
    
    /**
     * Setter for the databasemanager (mostly for testing)
     * @param \devmx\ChannelWatcher\Storage\DbalStorage\SchemaManager $manager
     */
    public function setDataBaseManager(SchemaManager $manager) 
    {
        $this->dbManager = $manager;
    }
    
    /**
     * Returns the DataBaseManager
     * The DataBaseManager is constructed if not set
     * @return SchemaManager
     */
    public function getDataBaseManager() {
        if($this->dbManager === null) 
        {
            $this->dbManager = new SchemaManager();
        }
        return $this->dbManager;
    }

}
