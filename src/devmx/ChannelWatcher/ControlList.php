<?php
namespace devmx\ChannelWatcher;

/**
 *
 * @author drak3
 */
class ControlList
{
    
    protected $blacklist;
    protected $whitelist;
    
    /**
     * @param array $blacklist canAccess returns false for all items which are in the blacklist 
     * @param array|null $whitelist if a array is passed canAccess returns false for items which are not in the whitelist
     */
    public function __construct(array $blacklist = array(), $whitelist = null) {
        $this->blacklist = $blacklist;
        $this->whitelist = $whitelist;
    }
    
    /**
     * Tests if item is accessable
     * this is determined with the help of the black and whitelist
     * @param mixed $item
     * @return boolean 
     */
    public function canAccess($item) {
        if(is_array($this->whitelist) && !in_array( $item, $this->whitelist )) {
            return false;
        }
        if(in_array($item, $this->blacklist)) {
            return false;
        }
        return true;
    }
}

?>
