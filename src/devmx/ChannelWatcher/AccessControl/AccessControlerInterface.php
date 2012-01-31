<?php
namespace devmx\ChannelWatcher\AccessControl;

/**
 *
 * @author drak3
 */
interface AccessControlerInterface
{
    public function canAccess($item);
}

?>
