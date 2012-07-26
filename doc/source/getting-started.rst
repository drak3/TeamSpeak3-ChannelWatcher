Getting Started
===============

What is the TeamSpeak3-ChannelWatcher?
--------------------------------------

The TeamSpeak3-ChannelWatcher is a tool to automatically delete unused channels.
It consists mainly of two parts: the crawler, and the deleter. The crawler visits your server and records which channels are visited. 
The deleter uses this data to savely remove unused channels.

Installation
------------
Simply download and unpack the archive provided here_ and make sure that all prerequisites are met:
 - php version greater than 5.3.3 (details for upgrading see :ref:`update-php`)
 - enabled fsockopen()
 - ability to run php on the command line 

Also you need TeamSpeak3-Query access. The user of the Query needs at least the following query-permissions:
 - b_virtualserver_select (use)
 - b_serverquery_login (login)
 - b_virtualserver_client_list (clientlist)
 - b_virtualserver_channel_list (channellist)
 - b_channel_delete_permanent (channeldelete)
 - b_channel_delete_semi_permanent (channeldelete)
 - b_channel_delete_temporary (channeldelete)
 - b_channel_delete_flag_force (channeldelete)

Configuration
-------------
A configuration file is a plain php file where all important options can be set. (See :doc:`The configuration documentation <configuration>` for more details.)
The easiest way to create a new configuration is to rename/copy the example configuration at config/example.php.
Then you must at least specify the teamspeak3 host, queryport (10011 in most cases), virtualserverport, the time after which channels should deleted and a database configuration:

Configuring the Teamspeak connection
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
You must set ``$c['ts3']['host']`` to your Teamspeak3-Server's host or ip, ``$c['ts3']['query.port']`` to the queryport (10011) and ``$c['ts3']['vserver.port']`` 
to specify the virtual server being watched.

Configuring the Watcher
~~~~~~~~~~~~~~~~~~~~~~~
To specify how long a channel can be unvisited before it is deleted edit the ``$c['watcher']['time_to_live']`` option.
To apply rules, like "Do not delete spacers" uncomment the corresponding line at the ``$c['watcher']['rules']`` configuration.

Configuring the Database
~~~~~~~~~~~~~~~~~~~~~~~~
The TeamSpeak3-ChannelWatcher can run with nearly every major relational database as a backend.
The easyest way to get things running is to simply enable the SQLite configuration in the config file.

.. Warning::
    The database IS NOT identical to the database used by the TeamSpeak3-Server. 
     
    The TeamSpeak3 ChannelWatcher maintains it own database, so normally there is no need to edit the default settings of the sqlite database.

For other databases see :doc:`The configuration documentation <configuration>`.

The first run
-------------
After configuring everything, type the following command into the command line: ``php app.php crawl <config_name>``
where ``<config_name>`` must be replaced with the name of the configuration file created in the previous step but WITHOUT the .php suffix.

.. Tip::
   When on windows, it might be neccessary to invoke php with its full path e.g ``C:\xampp\php\php.exe app.php ...``.

The command should take some time and then end without printing anything. When the app throws any errors see the :doc:`Troubleshooting page<troubleshooting>`


Regular crawls
~~~~~~~~~~~~~~
After making sure that the crawl command works you should do regular crawls. (This can for example be done on Linux by a cron job.)
We propose one crawl each 5 minutes so the deleter has enough data to safely decide which channels should be deleted.


Deleting channels
~~~~~~~~~~~~~~~~~

To delete channels simply run ``php app.php delete <config_name>``. This will print out all channels that will be deleted and ask for a confirmation.
Please make sure there are no channels listed which should not be deleted, we give no warranty for accidentally deleted channels.
Note that to be able to delete channels the TeamSpeak3 ChannelWatcher needs enough data. This data must be obtained by regulary running ``php app.php crawl <config_name>``
There will be a warning if there are not enough crawls.

Further documentation
---------------------
See :doc:`Troubleshooting <troubleshooting>` when facing any errors.
See :doc:`The configuration documentation <configuration>` for a detailed description of the configuration possibilities.

.. _here: http://devmx.de/software/teamspeak3-channel-watcher