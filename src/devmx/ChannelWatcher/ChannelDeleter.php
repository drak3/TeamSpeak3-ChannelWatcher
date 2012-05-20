<?php
namespace devmx\ChannelWatcher;
use devmx\Teamspeak3\Query\Transport\TransportInterface;
use devmx\ChannelWatcher\AccessControl\AccessControlerInterface;
use devmx\ChannelWatcher\Storage\StorageInterface;
use devmx\ChannelWatcher\Rule\RuleInterface;

/**
 *
 * @author drak3
 */
class ChannelDeleter
{
    
    protected $rules = array();
    
    protected $transport;
    
    protected $storage;
    
    public function __construct(TransportInterface $transport, StorageInterface $storage) {
        $this->transport = $transport;
        $this->storage = $storage;
    }
    
    public function addRule(RuleInterface $rule) {
        $this->rules[] = $rule;
    }
    
    public function setRules(array $rules) {
        $this->rules = $rules;
    }
    
    public function removeRule(RuleInterface $rule) {
        foreach($this->rules as $name=>$r) {
            if($r === $rule) {
                unset($this->rules[$name]);
            }
        }
    }
    
    public function getIdsToDelete(\DateInterval $emptyFor, \DateTime $now = null) {
        $ids = $this->storage->getChannelsEmptyFor($emptyFor, $now);
        return $this->filter($ids);
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
    
    protected function filter(array $ids) {
        $channelList = $this->transport->query('channellist', array(), array('topic', 'flags', 'voice', 'limits'));
        $channelList->toException();
        $channelList = $channelList->toAssoc('cid');
        foreach($channelList as $id => $channel) {
            if(  in_array( $id, $ids )) {
                $channelList['id']['__delete'] = true;
            } else {
                $channelList['id']['__delete'] = false;
            }
        }
        foreach($this->rules as $rule) {
            $channelList = $rule->filter($channelList);
        }
        return array_keys($channelList);
    }
    
}

?>
