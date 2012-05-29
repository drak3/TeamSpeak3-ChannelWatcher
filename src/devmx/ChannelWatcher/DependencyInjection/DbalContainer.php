<?php

namespace devmx\ChannelWatcher\DependencyInjection;

/**
 *
 * @author drak3
 */
class DbalContainer extends \Pimple
{
        public function __construct() {
            
            //depends on table_name
            
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
             * The DbalStorage that provides a simple interface to the channel stats 
             */
            $this['storage'] = $this->share(function($c){
                return new \devmx\ChannelWatcher\Storage\DbalStorage\DbalStorage($c['connection'], $c['table_name']);
            });
        }
        
}

?>
