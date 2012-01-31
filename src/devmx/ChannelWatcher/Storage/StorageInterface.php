<?php
namespace devmx\ChannelWatcher\Storage;

/**
 *
 * @author drak3
 */
interface StorageInterface
{
    /**
     * Updates the last time seen date of a chanel
     * @param $id int the id of the channel
     * @param $time int the unix timestanp of the last seen time defaults to now 
     */
    public function update($id, $isVisited, $time=null);
    
    /**
     * Returns all channel ids which are empty for a given time 
     * @param $time int the time in seconds 
     */
    public function getChannelsEmptyFor($time, $now=null);
}

?>
