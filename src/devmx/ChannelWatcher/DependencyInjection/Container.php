<?php
/*
* To change this template, choose Tools | Templates
* and open the template in the editor.
*/
namespace devmx\ChannelWatcher\DependencyInjection;
use devmx\ChannelWatcher\Command\CrawlCommand;
use devmx\ChannelWatcher\Command\CreateDataBaseCommand;
use devmx\ChannelWatcher\Command\PrintUnusedCommand;
use devmx\ChannelWatcher\Command\DeleteCommand;
use devmx\ChannelWatcher\ChannelCrawler;
use devmx\ChannelWatcher\ChannelDeleter;

/**
*
* @author drak3
*/
class Container extends \Pimple
{
    
    public function __construct() {
        /**
         * The ts3-subcontainer contains the preconfigured ts3container 
         */
        $this['ts3'] = new \devmx\Teamspeak3\SimpleContainer;
        
        /**
         * The query.transport is preconfigured so it is logged in and on the right server on use 
         */
        $this['ts3']['query.transport'] = $this['ts3']->share($this['ts3']->extend('query.transport.undecorated', function($transport, $c) {
            $transport->connect();
            if(isset($c['login.name']) && $c['login.name'] !== '') {
                $transport->query('login', array('client_login_name'=>$c['login.name'], 'client_login_password' =>$c['login.pass']));
            }
            $transport->query('use', array('port'=>$c['vserver.port']), array('virtual'));
            return $transport;
        }));
        
        /**
         * The db connection
         * depends on dbal.connection.params 
         */
        $this['dbal.connection'] = $this->share(function($c){
            return \Doctrine\DBAL\DriverManager::getConnection($c['dbal.connection.params']);
        });
        
        /**
         * Manager to create and configure database/tables
         */
        $this['dbal.db_manager'] = $this->share(function($c) {
            return new \devmx\ChannelWatcher\Storage\DbalStorage\DataBaseManager;
        });
        
        /**
         * The tablename under which the data should be stored 
         */
        $this['dbal.table_name'] = 'devmx_channel_deleter';
        
        /**
         * The DbalStorage that provides a simple interface to the channel stats 
         */
        $this['dbal.storage'] = $this->share(function($c){
            return new \devmx\ChannelWatcher\Storage\DbalStorage\DbalStorage($c['dbal.connection'], $c['dbal.table_name']);
        });
        
        /**
         * The deleter class 
         */
        $this['deleter'] = $this->share(function($c) {
            return new ChannelDeleter($c['ts3']['query.transport'], $c['storage']);
        });
        
        /**
         * The server crawler 
         */
        $this['crawler'] = $this->share(function($c) {
            return new ChannelCrawler($c['ts3']['query.transport'], $c['storage'], $c['ignore_query_clients']);
        });
        
        /**
         * If query clients should be ignored when checking if a channel is empty 
         */
        $this['ignore_query_clients'] = true;
        
        /**
         * The crawl command 
         */
        $this['command.crawl'] = function($c) {
            $command = new CrawlCommand();
            $command->setContainer($c);
            return $command;
        };
        
        /**
         * The database/table creation command 
         */
        $this['command.create_db'] = function($c) {
            $command = new CreateDataBaseCommand();
            $command->setContainer($c);
            return $command;
        };
        
        /**
         * The printunused command 
         */
        $this['command.print_unused'] = function($c) {
            $command = new PrintUnusedCommand();
            $command->setContainer($c);
            return $command;
        };
        
        /**
         * The delete command 
         */
        $this['command.delete'] = function($c) {
            $command = new DeleteCommand();
            $command->setContainer($c);
            return $command;
        };
        
        /**
         * The whole application 
         */
        $this['application'] = function($c) {
          $app = new \Symfony\Component\Console\Application($c['application.name'], $c['application.version']);
          $app->addCommands(array($c['command.crawl'], $c['command.create_db'], $c['command.print_unused'], $c['command.delete']));
          $app->setCatchExceptions(false);
          return $app;
        };
        
        /**
         * The channel storage
         * (db storage by default) 
         */
        $this['storage'] = function($c) {
            return $c['dbal.storage'];
        };
        
        /**
         * A in memory storage
         * (unused) 
         */
        $this['storage.in_memory'] = $this->share(function($c){
            return new \devmx\ChannelWatcher\Storage\InMemoryStorage;
        });
    }
    
}

?>