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
    /**
     * @var array of RuleInterface 
     */
    protected $rules = array();
    
    /**
     * @var TransportInterface
     */
    protected $transport;
    
    /**
     * @var StorageInterface 
     */
    protected $storage;
    
    /**
     * Constructor
     * @param TransportInterface $transport
     * @param StorageInterface $storage 
     */
    public function __construct(TransportInterface $transport, StorageInterface $storage) {
        $this->transport = $transport;
        $this->storage = $storage;
    }
    
    /**
     * Adds a rule
     * @param RuleInterface $rule 
     */
    public function addRule(RuleInterface $rule) {
        $this->rules[] = $rule;
    }
    
    /**
     * Sets the rules
     * @param array $rules 
     */
    public function setRules(array $rules) {
        $this->rules = $rules;
    }
    
    /**
     * Removes a specific rule
     * @param RuleInterface $rule 
     */
    public function removeRule(RuleInterface $rule) {
        foreach($this->rules as $name=>$r) {
            if($r === $rule) {
                unset($this->rules[$name]);
            }
        }
    }
    
    /**
     * Return the currently selected rules
     * @return array of RuleInterface 
     */
    public function getRules() {
        return $this->rules;
    }
    
    public function getIdsToDelete(\DateInterval $emptyFor, \DateTime $now = null) {
        $ids = $this->storage->getChannelsEmptyFor($emptyFor, $now);
        return $this->filter($ids);
    }
    
    public function delete(\DateInterval $emptyFor, \DateTime $now = null) {
        $toDelete = $this->getIdsToDelete($emptyFor, $now);
        $list = $this->transport->query('channellist');
        $list->toException();
        $currentIDs = array_keys($list->toAssoc('cid'));
        foreach($toDelete as $id) {
            if(in_array($id, $currentIDs)){
                $this->deleteChannel($id);
            }
        }
    }
    
    protected function deleteChannel($id) {
        try {
            $this->transport->query('channeldelete', array('cid'=> $id, 'force'=>true))->toException();
        } catch(\devmx\Teamspeak3\Query\Exception\CommandFailedException $e) {
            if($e->getResponse()->getErrorID() !== 768) {
                throw $e;
            }
        }
        
    }
    
    protected function filter(array $ids) {
        if($this->rules === array()) {
            return $ids;
        }
        $channelList = $this->transport->query('channellist', array(), array('topic', 'flags', 'voice', 'limits'));
        $channelList->toException();
        $channelList = $channelList->toAssoc('cid');
        foreach($channelList as $id => $channel) {
            if(  in_array( $id, $ids )) {
                $channelList[$id]['__delete'] = true;
            } else {
                $channelList[$id]['__delete'] = false;
            }
        }
        foreach($this->rules as $rule) {
            $channelList = $rule->filter($channelList);
        }
        $filteredIds = array();
        foreach($channelList as $id => $c) {
            if($c['__delete']) {
                $filteredIds[] = $id;
            }
        }
        return $filteredIds;
    }
    
}

?>
