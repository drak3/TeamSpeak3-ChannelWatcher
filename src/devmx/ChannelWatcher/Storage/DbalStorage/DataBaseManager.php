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
class DataBaseManager {

    public function createTable(Connection $c, $prefix) {
        $schema = $this->getSchema($prefix);
        $currentSchema = clone $c->getSchemaManager()->createSchema();
        $sql = $currentSchema->getMigrateToSql($schema, $c->getDatabasePlatform());
        foreach ($sql as $statement) {
            $c->executeQuery($statement);
        }
    }

    public function deleteTable(Connection $c, $tableName) {
        $schema = $this->getSchema($tableName)->dropTable($tableName);
        $currentSchema = clone $c->getSchemaManager()->createSchema();
        $sql = $currentSchema->getMigrateToSql($schema, $c->getDatabasePlatform());
        foreach ($sql as $statement) {
            $c->executeQuery($statement);
        }
    }

    public function getSchema($prefix) {
        $schema = new Schema();
        $channelTable = $schema->createTable(self::getChannelTableName( $prefix ));
        $channelTable->addColumn('id', 'integer', array('unsinged' => true));
        $channelTable->addColumn('last_seen', 'datetime');
        $channelTable->setPrimaryKey(array('id'));
        
        $crawlDataTable = $schema->createTable(self::getCrawlDateTableName($prefix));
        $crawlDataTable->addColumn('id', 'integer', array('unsinged' => true));
        $crawlDataTable->addColumn('crawl_time', 'datetime');
        $crawlDataTable->setPrimaryKey(array('id'));
        return $schema;
    }
    
    public static function getChannelTableName($prefix) {
        return $prefix.'channels';
    }
    
    public static function getCrawlDateTableName($prefix) {
        return $prefix.'crawl_data';
    }

}

?>
