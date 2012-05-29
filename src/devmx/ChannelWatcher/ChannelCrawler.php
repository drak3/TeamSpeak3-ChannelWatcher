<?php
namespace devmx\ChannelWatcher;
use devmx\Teamspeak3\Query\Transport\TransportInterface;
use devmx\Teamspeak3\Query\Command;
use devmx\ChannelWatcher\Storage\StorageInterface;
use devmx\ChannelWatcher\AccessControl\AccessControlerInterface;
use devmx\Teamspeak3\Query\CommandAwareQuery;
/**
 *
 * @author drak3
 */
class ChannelCrawler
{
    /**
     * @var \devmx\Teamspeak3\Query\CommandAwareQuery
     */
    protected $query;
    
    /**
     * @var \devmx\ChannelWatcher\Storage\StorageInterface
     */
    protected $storage;
    
    protected $ignoreQueryClients;
    
    /**
     * @var \devmx\ChannelWatcher\AccessControl\AccessControlerInterface
     */
    protected $accessControler;
    
    public function __construct(TransportInterface $transport, $ignoreQueryClients=true) {
        if($transport instanceof CommandAwareQuery ) {
            $this->query = $transport;
        } else {
            $this->query = new CommandAwareQuery($transport);
        }
        $this->query->exceptionOnError(true);
        $this->ignoreQueryClients = $ignoreQueryClients;
    }
    
    public function setControlList(AccessControlerInterface $a) {
        $this->accessControler = $a;
    }

    
    public function crawl() {
        $channelResponse = $this->query->channelList();
        $channels = $channelResponse->getItems();
        if($this->ignoreQueryClients) {
            $channels = $channelResponse->toAssoc('cid');
            $clients = $this->query->clientList();
            $clients = $clients->getItems();
            foreach($clients as $client) {
                if($client['client_type'] === 1) {
                    $channels[$client['cid']]['total_clients']--;
                }
            }
        }
        $this->currentChannels = $channels;
        return $channels;
    }
    
    public function updateStorage(  StorageInterface $storage, \DateTime $time = null, array $channels=array()) {
        if($time === null) {
            $time = new \DateTime('now');
        }
        if($channels === array()) {
            $channels = $this->currentChannels;
        }
        foreach($channels as $channel) {
            if($this->canAccess( $channel )) {
                $storage->update($channel['cid'], $this->hasClients($channel, $channels) , $time);
            }
        }
    }
    
    
    protected function canAccess($channel) {
        if($this->accessControler instanceof AccessControlerInterface) {
            return $this->accessControler->canAccess($channel['cid']);
        }
        return true;
    } 
      
    protected function hasClients($channel, $allChannels) {
        if($channel['total_clients'] > 0) {
            return true;
        }
        return false;
    }
    
}

?>
