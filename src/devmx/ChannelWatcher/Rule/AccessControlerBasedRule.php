<?php
namespace devmx\ChannelWatcher\Rule;
use devmx\ChannelWatcher\AccessControl\AccessControlerInterface;

/**
 *
 * @author drak3
 */
class AccessControlerBasedRule implements RuleInterface
{
    protected $saver;
    
    public function __construct(AccessControlerInterface $saveChannelControler) {
        $this->saver = $saveChannelControler;
    }
    
    public function filter(array $channelList) {
        foreach($channelList as $id => $channel) {
            if($this->saver->canAccess($channel)) {
                $channelList[$id]['__delete'] = false;
            }
        }
        return $channelList;
    }
}

?>
