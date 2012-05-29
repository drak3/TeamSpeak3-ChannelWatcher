<?php

namespace devmx\ChannelWatcher\Rule;

/**
 * This rule saves a channel if a parent up to the specific level was visited
 * By default any parent is taken into a
 * @author drak3
 */
class SaveChildsRule implements RuleInterface
{   
    protected $level = 10E6;
    
    public function setLevel($level) {
        if($level < 0) {
            $level = 10E6;
        }
        $this->level = $level;
    }
    
    public function filter(array $channels) {
        $unchangedChannels = $channels;
        foreach($channels as $cid => $channel) {
            if($this->channelHasSaveParent($channel, $unchangedChannels, $this->level)) {
                $channels[$cid]['__delete'] = false;
            }
        }
        return $channels;
    }
    
    protected function channelHasSaveParent(array $channel, array $channels, $level) {
        $pid = $channel['pid'];
        if($pid === 0 || $level < 1) {
            return !$channel['__delete'];
        }
        return $this->channelHasSaveParent($channels[$pid], $channels, $level-1);
    }
}

?>
