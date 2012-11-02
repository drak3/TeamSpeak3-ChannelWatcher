<?php

namespace devmx\ChannelWatcher;
use Symfony\Component\Console\Application as BaseApp;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * Description of Application
 *
 * @author drak3
 */
class Application extends BaseApp {
    public function doRun(InputInterface $in, OutputInterface $out) {
        $name = $this->getCommandName($in);
        if (true === $in->hasParameterOption(array('--help', '-h')) && !$name) {
            $in = new ArrayInput(array('command' => 'list'));
        }
        return parent::doRun($in, $out);
    }
}

?>
