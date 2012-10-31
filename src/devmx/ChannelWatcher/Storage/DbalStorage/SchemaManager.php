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
        $sql = $this->getMigrateStatements();
        foreach ($sql as $statement) {
            $this->connection->executeQuery($statement);
        }
    }
    
    protected function getMigrateStatements() {
        $schema = static::getSchema($this->getChannelTableName(), $this->getCrawlDateTableName());
        $currentSchema = clone $this->connection->getSchemaManager()->createSchema();
        return $currentSchema->getMigrateToSql($schema, $this->connection->getDatabasePlatform());
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
            $expectedSchema->getTable('foo_crawl_data')
                           ->getColumn('id')
                           ->setAutoincrement(false);
        }
        
        if($this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSqlPlatform) {
            $expectedSchema->createSequence('foo_crawl_data_id_seq');
        }
        
        $diff = \Doctrine\DBAL\Schema\Comparator::compareSchemas($this->connection->getSchemaManager()->createSchema(), $expectedSchema);
       
        $emptyDiff = new \Doctrine\DBAL\Schema\SchemaDiff();
        
        return $diff == $emptyDiff;
        
    }

    public function getChannelTableName()
    {
        return $this->connection->quoteIdentifier($this->prefix.'channels');
    }

    public function getCrawlDateTableName()
    {
        return $this->connection->quoteIdentifier($this->prefix.'crawl_data');
    }

}
