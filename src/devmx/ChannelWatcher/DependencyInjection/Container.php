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
        $this['ts3'] = new \devmx\Teamspeak3\SimpleContainer;
        $this['ts3']['query.transport'] = $this['ts3']->share($this['ts3']->extend('query.transport.undecorated', function($transport, $c) {
            $transport->connect();
            if(isset($c['login.name']) && $c['login.name'] !== '') {
                $transport->query('login', array('client_login_name'=>$c['login.name'], 'client_login_password' =>$c['login.pass']));
            }
            $transport->query('use', array('port'=>$c['vserver.port']), array('virtual'));
            return $transport;
        }));
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
            $command = new CrawlCommand();
            $command->setContainer($c);
            return $command;
        };
        $this['command.create_db'] = function($c) {
            $command = new CreateDataBaseCommand();
            $command->setContainer($c);
            return $command;
        };
        $this['command.print_unused'] = function($c) {
            $command = new PrintUnusedCommand();
            $command->setContainer($c);
            return $command;
        };
        $this['command.delete'] = function($c) {
            $command = new DeleteCommand();
            $command->setContainer($c);
            return $command;
        };
        
        $this['application'] = function($c) {
          $app = new \Symfony\Component\Console\Application($c['application.name'], $c['application.version']);
          $app->addCommands(array($c['command.crawl'], $c['command.create_db'], $c['command.print_unused'], $c['command.delete']));
          $app->setCatchExceptions(false);
          return $app;
        };
        
        $this['storage'] = function($c) {
            return $c['dbal.storage'];
        };
        
        $this['storage.in_memory'] = $this->share(function($c){
            return new \devmx\ChannelWatcher\Storage\InMemoryStorage;
        });
    }
    
}

?>