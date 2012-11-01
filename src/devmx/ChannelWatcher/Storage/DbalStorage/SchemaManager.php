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
use Doctrine\DBAL\Schema\Schema;

/**
 *
 * @author drak3
 */
class SchemaManager
{
    
    protected $prefix;
    
    protected $connection;
    
    public function __construct(Connection $c, $prefix) {
        $this->prefix = $prefix;
        $this->connection  = $c;
    }
    
    /**
     * Creates all nessecary tables
     */
    public function createTables()
    {
        try {
            $sql = $this->getMigrateStatements();
            foreach ($sql as $statement) {
                $this->connection->executeQuery($statement);
            }
        } catch(\Doctrine\DBAL\DBALException $e) {
            if($this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\SqlitePlatform) {
                throw new \devmx\ChannelWatcher\Storage\Exception("You cannot run the channelwatcher with a non-empty sqlite-db", $e->getCode(), $e);
            }
        }
        
    }
    
    protected function getMigrateStatements() {
        $schema = static::getSchema($this->getChannelTableName(), $this->getCrawlDateTableName());
        $currentSchema = clone $this->connection->getSchemaManager()->createSchema();
        $diff = \Doctrine\DBAL\Schema\Comparator::compareSchemas($currentSchema, $schema);
        return $diff->toSaveSql($this->connection->getDatabasePlatform());
    }

    public static function getSchema($channelTableName, $crawlDataTableName)
    {
        $schema = new Schema();
        $channelTable = $schema->createTable($channelTableName);
        $channelTable->addColumn('id', 'integer', array('unsinged' => true));
        $channelTable->addColumn('last_seen', 'datetime');
        $channelTable->setPrimaryKey(array('id'));

        $crawlDataTable = $schema->createTable($crawlDataTableName);
        $crawlDataTable->addColumn('id', 'integer', array('unsinged' => true));
        $crawlDataTable->addColumn('crawl_time', 'datetime');
        $crawlDataTable->setPrimaryKey(array('id'));
        $crawlDataTable->getColumn('id')->setAutoincrement(true);

        return $schema;
    }
    
    public function schemaIsCreated() {
        $expectedSchema = static::getSchema($this->getChannelTableName(), $this->getCrawlDateTableName());
        
        //fix differences in handling autoincrement        
        if($this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\SqlitePlatform) {
            $expectedSchema->getTable($this->getCrawlDateTableName())
                           ->getColumn('id')
                           ->setAutoincrement(false);
        }
        
        if($this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSqlPlatform) {
            $expectedSchema->createSequence($this->getCrawlDateTableName().'_id_seq');
        }
        
                
        $diff = \Doctrine\DBAL\Schema\Comparator::compareSchemas($this->connection->getSchemaManager()->createSchema(), $expectedSchema);
        if(isset($diff->changedTables[$this->getChannelTableName()]) || isset($diff->changedTables[$this->getCrawlDateTableName()])) {
            return false;
        }
        if(isset($diff->newTables[$this->getChannelTableName()]) || isset($diff->newTables[$this->getCrawlDateTableName()])) {
            return false;
        }
        return true;
    }

    public function getChannelTableName()
    {
        return $this->prefix.'channels';
    }

    public function getCrawlDateTableName()
    {
        return $this->prefix.'crawl_data';
    }

}
