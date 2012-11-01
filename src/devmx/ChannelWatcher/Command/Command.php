<?php

namespace devmx\ChannelWatcher\Command;

use Symfony\Component\Console\Command\Command as BaseCommand;

/**
 * Description of Command
 *
 * @author drak3
 */
class Command extends BaseCommand {
    
    public function getSynopsis() {
        return 'Usage: '.parent::getSynopsis();
    }
}

?>
