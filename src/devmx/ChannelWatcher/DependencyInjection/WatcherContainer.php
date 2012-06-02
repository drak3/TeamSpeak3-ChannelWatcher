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

use devmx\ChannelWatcher\ChannelCrawler;
use devmx\ChannelWatcher\DateConverter;
use devmx\ChannelWatcher\ChannelDeleter;
use devmx\ChannelWatcher\Watcher\CrawlingWatcher;

/**
 *
 * @author drak3
 */
class WatcherContainer extends \Pimple {

    public function __construct() {

        //depends on ts3.transport and storage

        /**
         * The deleter class 
         */
        $this['deleter'] = $this->share(function($c) {
                    $deleter = new ChannelDeleter($c['ts3.transport'], $c['storage']);
                    foreach ($c['rules'] as $r) {
                        $deleter->addRule($r);
                    }
                    return $deleter;
                });

        $this['delete_time'] = function($c) {
                    return DateConverter::convertArrayToInterval($c['time_to_live']);
                };

        /**
         * The server crawler 
         */
        $this['crawler'] = $this->share(function($c) {
                    return new ChannelCrawler($c['ts3.transport'], $c['storage'], $c['ignore_query_clients']);
                });
                
        $this['watcher'] = $this->share(function($c) {
            return new CrawlingWatcher($c['crawler'], $c['watcher.crawl_time']);
        });
        
        $this['watcher.crawl_time'] = new \DateInterval('PT5S');

        /**
         * If query clients should be ignored when checking if a channel is empty 
         */
        $this['ignore_query_clients'] = true;

        $this['rules'] = array();

        /**
         * This rule saves all channels that are spacers from being deleted
         */
        $this['rule.save_spacer'] = function($c) {
                    return new \devmx\ChannelWatcher\Rule\SaveSpacersRule;
                };

        /**
         * This rules saves parents which's childs were visited
         * Set rule.save_parent.max_level to specify the maximum nesting level under which the saving works
         * A rule.save_parent.max_level of -1 (default) means unlimited 
         */
        $this['rule.save_childs'] = function($c) {
                    $r = new \devmx\ChannelWatcher\Rule\SaveChildsRule();
                    if (isset($c['rule.save_parent.max_level'])) {
                        $r->setLevel($c['rule.save_parent.max_level']);
                    }
                    return $r;
                };

        $this['rule.save_parent'] = function($c) {
                    return new \devmx\ChannelWatcher\Rule\SaveParentRule();
                };

        /**
         * A rule that saves channels based on a access control list 
         */
        $this['rule.acl_filter'] = function($c) {
                    return new \devmx\ChannelWatcher\Rule\AccessControlerBasedRule($c['rule.acl_filter.acl']);
                };

        /**
         * The acl (here implemted as a white/blacklist acl) 
         */
        $this['rule.acl_filter.acl'] = function($c) {
                    return new \devmx\ChannelWatcher\AccessControl\ListBasedControler($c['rule.acl_filter.blacklist'], $c['rule.acl_filter.whitelist']);
                };

        /**
         * Includes a list of all channels which must not be deleted 
         * (null means that all channels are principally deletable)
         */
        $this['rule.acl_filter.blacklist'] = array();

        /**
         * Contains a list of all deletable channels
         */
        $this['rule.acl_filter.whitelist'] = null;
    }

}

?>
