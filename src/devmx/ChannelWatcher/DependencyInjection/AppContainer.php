<?php

/**
 * This file is part of the Teamspeak3 ChannelWatcher.
 * Copyright (C) 2012 drak3 <drak3@live.de>
 * Copyright (C) 2012 Maxe <maxe.nr@live.de>
 *
 * The Teamspeak3 ChannelWatcher is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * The Teamspeak3 ChannelWatcher is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with the Teamspeak3 ChannelWatcher.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace devmx\ChannelWatcher\DependencyInjection;

use devmx\ChannelWatcher\Command\CrawlCommand;
use devmx\ChannelWatcher\Command\DeleteCommand;
use devmx\ChannelWatcher\Command\PrintUnusedCommand;
use devmx\ChannelWatcher\Command\CreateDataBaseCommand;
use devmx\ChannelWatcher\Initializer;

/**
 *
 * @author drak3
 */
class AppContainer extends \Pimple
{
    public function __construct()
    {
        $that = $this;
        /**
         * The name of the app (used by the symfony console)
         */
        $this['name'] = 'Teamspeak3 ChannelWatcher';

        /**
         * Current app version
         */
        $this['version'] = '1.0.0';

        $this['debug'] = false;

        $this['db'] = new DbalContainer();

        /**
         * The ts3-subcontainer contains the preconfigured ts3container
         */
        $this['ts3'] = new \devmx\Teamspeak3\SimpleContainer();

        $this['ts3']['debug'] = function($c) use ($that) {
            return $that['debug'];
        };

        $this['ts3']['query.transport.decorators']['order'] = function($c) use ($that) {
            if ($that['debug']) {
                return array('debugging', 'caching.in_memory', 'profiling');
            } else {
                return array('caching.in_memory');
            }
        };

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
        $this['ts3']['query.transport'] = $this['ts3']->share($this['ts3']->extend('query.transport', function($transport, $c) {
            $transport->connect();
            if (isset($c['login.name']) && $c['login.name'] !== '') {
                $transport->query('login', array('client_login_name'=>$c['login.name'], 'client_login_password' =>$c['login.pass']));
            }
            $transport->query('use', array('port'=>$c['vserver.port']), array('virtual'));

            return $transport;
        }));

        /**
         * The table name for the dbal storage
         */
        $this['db']['prefix'] = $this->share(function($c) use ($that) {
                    return 'devmx_ts3_channelwatcher_'.$that['profile'].'__';
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
                    return $c['root_dir'] . '/storage/' . $c['profile'] . '/';
                };

        /**
         * The profile loader
         * The profile loader is a function that tries to include the specific configuration
         */
        $this['profile.loader'] = function($c) {
                    //we cannot use $c here directly because of https://bugs.php.net/bug.php?id=54367 (probably)
                    return function($c) {
                                if (file_exists($c['profile.path']) && is_readable($c['profile.path'])) {
                                    include($c['profile.path']);
                                } else {
                                    throw new \RuntimeException('Unknown configuration ' . $c['profile']);
                                }
                            };
                };

        /**
         * The path to the current profiles configuration
         */
        $this['profile.path'] = function($c) {
                    return $c['root_dir'] . '/config/' . $c['profile'] . '.php';
                };
        
        $this['initer'] = $this->share(function($c){
            return new Initializer($c['storage'], $c['storagedir']);
        });
                
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
                    $command = new CreateDataBaseCommand('db:migrate');
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

        $this['command.init'] = function($c) {
                    $command = new \devmx\ChannelWatcher\Command\InitCommand('init');
                    $command->setContainer($c);

                    return $command;
                };

        $this['command.watch'] = function($c) {
            $command = new \devmx\ChannelWatcher\Command\WatchCommand('watch');
            $command->setContainer($c);

            return $command;
        };

        /**
         * The whole application
         */
        $this['application'] = function($c) {
                    $app = new \Symfony\Component\Console\Application($c['name'], $c['version']);
                    $app->addCommands(array($c['command.crawl'], $c['command.create_db'], $c['command.print_unused'], $c['command.delete'], $c['command.init'], $c['command.watch']));
                    //as of here, the app is responsible for error handling
                    if ($c['debug']) {
                        error_reporting(-1);
                        $app->setCatchExceptions(false);
                    } else {
                        error_reporting(0);
                    }

                    return $app;
                };
    }

}
