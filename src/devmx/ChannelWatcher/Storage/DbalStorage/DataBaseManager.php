<?php
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
        return $schema;
    }
}

?>
