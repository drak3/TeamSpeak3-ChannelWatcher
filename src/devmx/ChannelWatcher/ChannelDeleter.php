<?php
namespace devmx\ChannelWatcher;
use devmx\Teamspeak3\Query\Transport\TransportInterface;
use devmx\ChannelWatcher\AccessControl\AccessControlerInterface;
use devmx\ChannelWatcher\Storage\StorageInterface;

/**
 *
 * @author drak3
 */
class ChannelDeleter
{
    
    protected $accessControler = null;
    
    protected $transport;
    
    protected $storage;
    
    public function __construct(TransportInterface $transport, StorageInterface $storage) {
        $this->transport = $transport;
        $this->storage = $storage;
    }
    
    public function setAccessControlList(AccessControlerInterface $list) {
        $this->accessControler = $list;
    }
    
    public function getIdsToDelete(\DateInterval $emptyFor, \DateTime $now = null) {
        $ids = $this->storage->getChannelsEmptyFor($emptyFor, $now);
        $ids = array_filter($ids, array($this, 'canAccess'));
        return $ids;
    }
    
    public function delete(\DateInterval $emptyFor, \DateTime $now = null) {
        $toDelete = $this->getIdsToDelete($emptyFor, $now);
        foreach($toDelete as $id) {
            $this->deleteChannel($id);
        }
    }
    
    protected function deleteChannel($id) {
        $this->transport->query('channeldelete', array('cid'=> $id));
    }
    
    protected function canAccess($id) {
        if($this->accessControler instanceof AccessControlerInterface) {
            return $this->accessControler->canAccess($id);
        }
        return true;
    }
    
}

?>
