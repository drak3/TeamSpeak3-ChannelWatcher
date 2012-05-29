<?php
namespace devmx\ChannelWatcher\DependencyInjection;

use devmx\ChannelWatcher\Command\CrawlCommand;
use devmx\ChannelWatcher\Command\DeleteCommand;
use devmx\ChannelWatcher\Command\PrintUnusedCommand;
use devmx\ChannelWatcher\Command\CreateDataBaseCommand;

/**
 *
 * @author drak3
 */
class AppContainer extends \Pimple
{
    public function __construct() {
        $that = $this;
        /**
         * The name of the app (used by the symfony console) 
         */
        $this['name'] = 'Teamspeak3 ChannelWatcher';
        
        /** 
         * Current app version 
         */
        $this['version'] = '0.1';
        
        $this['db'] = new DbalContainer();
        
        /**
         * The ts3-subcontainer contains the preconfigured ts3container 
         */
        $this['ts3'] = new \devmx\Teamspeak3\SimpleContainer;
        
        $this['watcher'] = new WatcherContainer();
                
        $this['watcher']['ts3.transport'] = function() use ($that) {
            return $that['ts3']['query.transport'];
        };
        
        $this['watcher']['storage'] = function() use ($that) {
            return $that['db']['storage'];
        };
        
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
         * The table name for the dbal storage
         */
        $this['db']['table_name'] = $this->share(function($c) use ($that) {
           return 'devmx_channel_watcher_channels_'.$that['profile']; 
        });
        
        /**
         * The channel storage
         * (db storage by default) 
         */
        $this['storage'] = function($c) {
            return $c['db']['storage'];
        };
        
        /**
         * The current storage dir 
         */
        $this['storagedir'] = function($c) {
            return $c['root_dir'].'/storage/'.$c['profile'].'/';
        };
        
        /**
         * The profile loader
         * The profile loader is a function that tries to include the specific configuration 
         */
        $this['profile.loader'] = function($c){
            return function() use ($c) {
                if(  file_exists($c['profile.path']) && is_readable($c['profile.path'])) {
                    include($c['profile.path']);
                } else {
                    throw new \RuntimeException('Unknown configuration '.$c['profile']);
                }
            };
        };
        
        /**
         * The path to the current profiles configuration 
         */
        $this['profile.path'] = function($c) {
            return $c['root_dir'].'/config/'.$c['profile'].'.php';
        };
        
        /**
         * The crawl command 
         */
        $this['command.crawl'] = function($c) {
            $command = new CrawlCommand('crawl');
            $command->setContainer($c);
            return $command;
        };
        
        /**
         * The database/table creation command 
         */
        $this['command.create_db'] = function($c) {
            $command = new CreateDataBaseCommand('db:create_table');
            $command->setContainer($c);
            return $command;
        };
        
        /**
         * The printunused command 
         */
        $this['command.print_unused'] = function($c) {
            $command = new PrintUnusedCommand('print_unused');
            $command->setContainer($c);
            return $command;
        };
        
        /**
         * The delete command 
         */
        $this['command.delete'] = function($c) {
            $command = new DeleteCommand('delete');
            $command->setContainer($c);
            return $command;
        };
        
        /**
         * The whole application 
         */
        $this['application'] = function($c) {
          $app = new \Symfony\Component\Console\Application($c['name'], $c['version']);
          $app->addCommands(array($c['command.crawl'], $c['command.create_db'], $c['command.print_unused'], $c['command.delete']));
          return $app;
        };
    }
}

?>
