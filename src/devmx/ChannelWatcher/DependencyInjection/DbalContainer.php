<?php

namespace devmx\ChannelWatcher\DependencyInjection;

/**
 *
 * @author drak3
 */
class DbalContainer extends \Pimple
{
        public function __construct() {
           /**
            * The db connection
            * depends on connection.params 
            */
            $this['connection'] = $this->share(function($c){
                return \Doctrine\DBAL\DriverManager::getConnection($c['connection.params']);
            });
        
            /**
             * Manager to create and configure database/tables
             */
            $this['db_manager'] = $this->share(function($c) {
                return new \devmx\ChannelWatcher\Storage\DbalStorage\DataBaseManager;
            });
        
            /**
             * The tablename under which the data should be stored 
             */
            $this['table_name'] = 'devmx_channel_deleter';
        
            /**
             * The DbalStorage that provides a simple interface to the channel stats 
             */
            $this['storage'] = $this->share(function($c){
                return new \devmx\ChannelWatcher\Storage\DbalStorage\DbalStorage($c['connection'], $c['table_name']);
            });
        }
        
}

?>
