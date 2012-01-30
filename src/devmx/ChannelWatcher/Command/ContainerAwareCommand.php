<?php
namespace devmx\ChannelWatcher\Command;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 *
 * @author drak3
 */
class ContainerAwareCommand extends Command implements ContainerAwareInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface 
     */
    protected $container;
    
    public function setContainer(ContainerInterface $c = null) {
        $this->container = $c;
    }
    
}

?>
