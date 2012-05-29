<?php
namespace devmx\ChannelWatcher\Rule;

/**
 *
 * @author drak3
 */
class SaveParentRule implements RuleInterface
{
    public function filter(array $list) {
        $channels = $list;
        foreach($list as $cid => $channel) {
            if($this->channelHasSaveChild($channel, $channels)) {
                $list[$cid]['__delete'] = false;
            }
        }
        return $list;
    }
    
    protected function channelHasSaveChild(array $channel, array $channels) {
        foreach($this->getChannelsWithPID($channels , $channel['cid']) as $child) {
            if($this->isSave($child) || $this->channelHasSaveChild( $child, $channels )) {
                return true;
            }
        }
        return false;
    }
    
    protected function isSave(array $channel) {
        return !$channel['__delete'];
    }
    
    protected function getChannelsWithPID($channels, $pid) {
        $ret = array();
        foreach($channels as $channel) {
            if($channel['pid'] === $pid) {
                $ret[] = $channel;
            }
        }
        return $ret;
    }
}

?>
