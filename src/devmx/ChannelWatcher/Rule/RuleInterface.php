<?php

namespace devmx\ChannelWatcher\Rule;
use devmx\Teamspeak3\Query\Transport\TransportInterface;

/**
 *
 * @author drak3
 */
interface RuleInterface
{
    public function filter(array $filteredChannelList, array $completeChannelList, TransportInterface $transport);
}

?>
