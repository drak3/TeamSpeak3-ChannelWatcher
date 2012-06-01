<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace devmx\ChannelWatcher\Deleter;
use devmx\Teamspeak3\Query\CommandResponse;

/**
 *
 * @author drak3
 */
class ChannelNotEmptyException extends \devmx\Teamspeak3\Query\Exception\CommandFailedException
{
    public function __construct(CommandResponse $response) {
        parent::__construct($response, 'Cannot delete channel with id '.$response->getCommand()->getParameter('cid'));
    }
}

?>
