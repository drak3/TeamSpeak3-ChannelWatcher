FAQ
===

Q: What is the TeamSpeak3-ChannelWatcher?
-----------------------------------------

**A:**

The TeamSpeak3-ChannelWatcher is a tool that is able to automatically delete unused Channels on a TeamSpeak3-Server.
The decision if a channel should be deleted or not is configurable via so called Rules, that allow for example saving of spacers or channels with visited parents.

Q: How does the ChannelWatcher work?
------------------------------------

**A:**

Every time the ``crawl`` command is run, the ChannelWatcher connects to the server, looks which channels are visited and stores this data to its database.
When running the ``delete`` command, the ChannelWatcher uses this data to decide which channels should be deleted.

For a more in depth description of the technical details see the :doc:`Technical Overview <technical-overview>` document.

Q: How long will you support version xy?
----------------------------------------

**A:**

We will support each minor version (e.g. 1.1.0) as long as the next but one version gets released (1.3.0 in this example).

Q: How shall I update my Installation?
--------------------------------------

**A:**

When upgrading to a newer minor version (e.g from 1.0.0 to 1.1.0) simply dropping in the new release while preserving your configuration files should be enough.
A new minor release will not force you to change anything in your configuration and will not alter anything described in the official documentation (especially the commands and their params will work in the new version too).

Q: Does the ChannelWatcher need access to my TeamSpeak3-Server's database?
--------------------------------------------------------------------------

**A:**

No! The ChannelWatcher maintains own database, and is completely independent from the TeamSpeak3-Database, since it only communicates via the query.

Q: So I don't have to insert the TeamSpeak3-Database in the database configuration?
-----------------------------------------------------------------------------------

**A:**

Nope, the TeamSpeak3-Database is not accessed!

Q: Can I somehow exclude channels from being deleted?
-----------------------------------------------------

**A:**

Yes, this is possible, you have to enter its id in the $c['watcher']['rule.acl_filter.blacklist'] array (line 63 in the example.php)
So it looks like this:

.. code-block:: php
   
    <?php
    //... line 63:
    $c['watcher']['rule.acl_filter.blacklist'] = array(<channel_id_1>, <channel_id_2>);


The easiest way to get the channel ids is with `this`_ plugin. (You will see the channelid in brackets when you select the channel).

The ``$c['watcher']['rule.acl_filter']`` should be enabled by default. If not enable it by simply uncommenting it (remove the // in line 80 of example.php) 

.. _this: http://addons.teamspeak.com/directory/skins/stylesheets/Extended-Client-Info.html


Q: I've encountered an error, how should I fix it?
--------------------------------------------------

**A:**

First, take a look at the :doc:`Troubleshooting <troubleshooting>` page if your problem is describes there.
If not, you might post your problem to the `official forum thread`_ or to our `support forum`_. 
If you encountered a bug feel free to open a issue on `github`_

.. _official forum thread: http://forum.teamspeak.com/showthread.php/74307-Release-devMX-TeamSpeak3-ChannelWatcher-Auto-delete-unvisited-Channels
.. _support forum: http://forum.devmx.de
.. _github: https://github.com/devMX/TeamSpeak3-ChannelWatcher/issues


Q: Will the ChannelWatcher support PHP 5.2?
-------------------------------------------

**A:**

No. The TeamSpeak3-ChannelWatcher and all used Libraries rely heavily on the great new features introduced in 5.3.

We encourage you to update from 5.2 which is not longer officially supported by the php-devs to a newer version such as 5.3 or 5.4.
