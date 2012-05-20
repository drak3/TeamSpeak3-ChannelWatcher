<?php

namespace devmx\ChannelWatcher\Rule;
use devmx\Teamspeak3\Query\Transport\TransportInterface;

/**
 *
 * @author drak3
 */
interface RuleInterface
{
    /**
     * Filters the channellist that should be deleted
     * The filter method must change the '__delete' key of each channel entry to specify if the channel should be deleted (true<=>yes)
     * The filter method must not delete entries of the channellist
     * @param array $channelList
     * @return array the filtered Channellist
     */
    public function filter(array $channelList);
}

?>
