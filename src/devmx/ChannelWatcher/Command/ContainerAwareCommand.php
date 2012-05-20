<?php
namespace devmx\ChannelWatcher\Command;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 *
 * @author drak3
 */
class ContainerAwareCommand extends Command
{

    protected $c;
    
    public function setContainer($c) {
        $this->c = $c;
    }
    
}

?>
