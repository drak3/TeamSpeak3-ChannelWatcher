<?php
/*
* To change this template, choose Tools | Templates
* and open the template in the editor.
*/
namespace devmx\ChannelWatcher\DependencyInjection;
use devmx\ChannelWatcher\Command\CrawlCommand;
use devmx\ChannelWatcher\Command\CreateDataBaseCommand;
use devmx\ChannelWatcher\Command\ToDeletePrintCommand;
use devmx\ChannelWatcher\ChannelCrawler;

/**
*
* @author drak3
*/
class Container extends \Pimple
{
    
    public function __construct() {
        $this['ts3'] = new \devmx\Teamspeak3\SimpleContainer;
        $this['dbal.connection.params'] = array();
        $this['dbal.connection'] = $this->share(function($c){
            return \Doctrine\DBAL\DriverManager::getConnection($c['dbal.connection.params']);
        });
        $this['dbal.db_manager'] = $this->share(function($c) {
            return new \devmx\ChannelWatcher\Storage\DbalStorage\DataBaseManager;
        });
        
        $this['dbal.table_name'] = 'devmx_channel_deleter';
        
        $this['dbal.storage'] = $this->share(function($c){
            return new \devmx\ChannelWatcher\Storage\DbalStorage\DbalStorage($c['dbal.connection'], $c['dbal.table_name']);
        });
        
        $this['deleter'] = $this->share(function($c) {
            return new ChannelDeleter($c['ts3']['query.transport'], $c['storage']);
        });
        
        $this['crawler'] = $this->share(function($c) {
            return new ChannelCrawler($c['ts3']['query.transport'], $c['storage'], $c['ignore_query_clients']);
        });
        
        $this['ignore_query_clients'] = true;
        
        $this['command.crawl'] = function($c) {
            return new CrawlCommand($c['crawler']);
        };
        $this['command.create_db'] = function($c) {
            return new CreateDataBaseCommand($c['dbal.connection'], $c['dbal.db_manager'], $c['dbal.table_name']);
        };
        $this['command.print_deletable'] = function($c) {
          return new ToDeletePrintCommand($c['storage']);
        };
        
        $this['application'] = function($c) {
          $app = new \Symfony\Component\Console\Application($c['application.name'], $c['application.version']);
          $app->addCommands(array($c['command.crawl'], $c['command.create_db'], $c['command.print_deletable']));
          return $app;
        };
        
        $this['application.name'] = 'devmx ChannelWatcher';
        
        $this['application.version'] = '0.1';
        
        $this['storage'] = function($c) {
            return $c['dbal.storage'];
        };
        
        $this['storage.in_memory'] = $this->share(function($c){
            return new \devmx\ChannelWatcher\Storage\InMemoryStorage;
        });
    }
    
}

?>