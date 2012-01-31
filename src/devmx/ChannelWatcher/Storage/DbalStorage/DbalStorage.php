<?php
namespace devmx\ChannelWatcher\Storage\DbalStorage;
use Doctrine\DBAL\Connection;
/**
 *
 * @author drak3
 */
class DbalStorage implements \devmx\ChannelWatcher\Storage\StorageInterface
{
    
    protected $connection;
    protected $tableName;
    
    public function __construct(Connection $c, $tablename) {
        $this->connection = $c;
        $this->tableName = $c->quoteIdentifier($tablename);
    }
    
    /**
     * Updates the last time seen date of a chanel
     * @param $id int the id of the channel
     * @param $time int the unix timestanp of the last seen time defaults to now 
     */
    public function update($id, $hasClients, $time=null) {
        $id = (int) $id;
        $lastSeen = new \DateTime('now');
        if($time !== null) {
            $lastSeen->setTimestamp($time);
        }
        
        if($this->has($id)) {
            if($hasClients) {
                $update = $this->connection->prepare('UPDATE '.$this->tableName.' SET last_seen = ? WHERE id = ?');
                $update->bindValue(1, $lastSeen, 'datetime');
                $update->bindValue(2, $id, 'integer');
                $update->execute();
            }        
        }
        else {
            $update = $this->connection->prepare('INSERT INTO'.$this->tableName.' (id, last_seen) VALUES (?, ?)');
            $update->bindValue(1, $id, 'integer');
            $update->bindValue(2, $lastSeen, 'datetime');
            $update->execute();
        }
    }
    
    public function has($id) {
        $statement = $this->connection->prepare('SELECT id FROM '.$this->tableName.' WHERE id=?');
        $statement->bindValue(1, $id, 'integer');
        $statement->execute();
        return count($statement->fetchAll()) > 0;
    }
    
    /**
     * Returns all channel ids which are empty for a given time 
     * @param $time int the time in seconds 
     */
    public function getChannelsEmptyFor($time, $now=null) {
        if($now === null) {
            $now = \time();
        }
        $maxLastSeen = $now - $time;
        $maxLastSeenTime = new DateTime();
        $maxLastSeenTime->setTimestamp($maxLastSeen);
        $query = $this->connection->prepare('SELECT id FROM '.$this->tableName.' WHERE last_seen < ?');
        $query->bindValue(1, $maxLastSeenTime, 'datetime');
        $query->execute();
        return $query->fetchAll();
    }
}

?>
