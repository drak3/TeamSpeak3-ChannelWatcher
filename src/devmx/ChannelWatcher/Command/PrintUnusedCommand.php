<?php
namespace devmx\ChannelWatcher\Command;
use devmx\ChannelWatcher\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
/**
 *
 * @author drak3
 */
class PrintUnusedCommand extends ProfileDependentCommand
{
    
    protected function execute(InputInterface $in, OutputInterface $out) {
        $time = $this->c['watcher']['delete_time'];
        $unused = $this->c['watcher']['deleter']->getIdsToDelete($time);
        $channellist = $this->c['ts3']['query.transport']->query('channellist')->toAssoc('cid');
        foreach($unused as $id) {
            if(!isset($channellist[$id])) {
                $out->writeln("Channel with id $id is not on the server anymore, ignoring");
            }
            else {
                $out->writeln($channellist[$id]['channel_name'].' ('.$id.')');
            }
        }
        $out->writeln(sprintf('%d in total', count($unused)));
    }
}

?>
