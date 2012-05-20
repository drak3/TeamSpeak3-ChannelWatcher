<?php

namespace devmx\ChannelWatcher\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 * @author drak3
 */
class ProfileDependentCommand extends ContainerAwareCommand
{
    public function __construct($name=null) {
        parent::construct($name);
        $this->addArgument('config', InputArgument::REQUIRED, 'the config of the server to interact with');
    }
    
    protected function initialize(InputInterface $in, OutputInterface $out) {
        $this->c['app.profile'] = $in->getArgument('config');
        $this->c['app.profile.loader']();
    }
}

?>
