<?php
namespace devmx\ChannelWatcher\Rule;

/**
 *
 * @author drak3
 */
class SaveSpacersRule implements RuleInterface
{
    public function filter(array $channelList) {
        foreach($channelList as $id => $channel) {
            if($this->isSpacer($channel)) {
                $channelList[$id]['__delete'] = false;
            }
        }
        return $channelList;
    }
    
    protected function isSpacer(array $channel) {
        if ($channel['pid'] != 0) return false;
        else if (preg_match("#.*\[([rcl*]?)spacer(.*?)\](.*)#", $channel['channel_name']) == 0) return false;
        return true;
    }
}

?>
