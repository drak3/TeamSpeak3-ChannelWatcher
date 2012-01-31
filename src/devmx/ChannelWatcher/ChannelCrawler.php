<?php
namespace devmx\ChannelWatcher;
use devmx\Teamspeak3\Query\Transport\TransportInterface;
use devmx\Teamspeak3\Query\Command;
use devmx\ChannelWatcher\Storage\StorageInterface;
/**
 *
 * @author drak3
 */
class ChannelCrawler
{
    /**
     * @var \devmx\Teamspeak3\Query\Transport\TransportInterface
     */
    protected $transport;
    
    /**
     * @var \devmx\ChannelWatcher\Storage\StorageInterface
     */
    protected $storage;
    
    protected $ignoreQueryClients;
    
    public function __construct(TransportInterface $transport, StorageInterface $storage, $ignoreQueryClients=true) {
        $this->transport = $transport;
        $this->storage = $storage;
        $this->ignoreQueryClients = $ignoreQueryClients;
    }

    
    public function crawl() {
        $time = \time();
        $channelResponse = $this->transport->query('channellist');
        $channelResponse->toException();
        $channels = $channelResponse->getItems();
        if($this->ignoreQueryClients) {
            $channels = $channelResponse->toAssoc('cid');
            $clients = $this->transport->query('clientlist');
            $clients->toException();
            $clients = $clients->getItems();
            foreach($clients as $client) {
                if($client['client_type'] === 1) {
                    $channels[$client['cid']]['total_clients']--;
                }
            }
        }
        foreach($channels as $channel) {
            $this->storage->update($channel['cid'], $this->hasClients($channel, $channels), $time);
        }
    }
    
      
    protected function hasClients($channel, $allChannels) {
        if($channel['total_clients'] > 0) {
            return true;
        }
        $childs = $this->getChannelsWithPID($allChannels , $channel['cid']);
        foreach($childs as $child) {
            if($this->hasClients( $child, $allChannels )) {
                return true;
            }
        }
        return false;
    }
    
    protected function getChannelsWithPID($channels, $pid) {
        $ret = array();
        foreach($channels as $channel) {
            if($channel['pid'] == $pid) {
                $ret[] = $channel;
            }
        }
        return $ret;
    }
}

?>
