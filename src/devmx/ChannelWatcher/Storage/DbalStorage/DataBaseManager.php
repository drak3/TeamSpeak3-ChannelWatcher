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
namespace devmx\ChannelWatcher\Storage\DbalStorage;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;

/**
 *
 * @author drak3
 */
class DataBaseManager
{
    public function createTable(Connection $c, $tableName) {
        $schema = $this->getSchema($tableName);
        $currentSchema = clone $c->getSchemaManager()->createSchema();
        $sql = $currentSchema->getMigrateToSql($schema, $c->getDatabasePlatform());
        foreach($sql as $statement) {
            $c->executeQuery($statement);
        }   
    }
    
    public function deleteTable(Connection $c, $tableName) {
        $schema = $this->getSchema($tableName)->dropTable( $tableName );
        $currentSchema = clone $c->getSchemaManager()->createSchema();
        $sql = $currentSchema->getMigrateToSql($schema, $c->getDatabasePlatform());
        foreach($sql as $statement) {
            $c->executeQuery($statement);
        } 
    }
    
    public function getSchema($tablename) {
        $schema = new Schema();
        $table = $schema->createTable($tablename);
        $table->addColumn('id', 'integer', array('unsinged'=> true));
        $table->addColumn('last_seen', 'datetime');
        $table->setPrimaryKey(array('id'));
        return $schema;
    }
}

?>
