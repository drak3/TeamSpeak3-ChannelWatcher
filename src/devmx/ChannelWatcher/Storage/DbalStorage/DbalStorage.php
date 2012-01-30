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
    
    public function __construct(Connection $c) {
        $this->connection = $c;
    }
    
    /**
     * Updates the last time seen date of a chanel
     * @param $id int the id of the channel
     * @param $time int the unix timestanp of the last seen time defaults to now 
     */
    public function update($id, $time=null) {
        
    }
    
    /**
     * Returns all channel ids which are empty for a given time 
     * @param $time int the time in seconds 
     */
    public function getChannelsEmptyFor($time) {
        
    }
}

?>
