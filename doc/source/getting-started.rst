Getting Started
===============

Installation
------------
Simply download and unpack the archive provided here_ and make sure that all prerequisites are met:
 - php version greater than 5.3.2
 - enabled fsockopen()
 - ability to run php on the command line 

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
After making sure that the crawl command works you should do regular crawls. This can for example be done by a cron job.
We propose one crawl each 5 minutes. 


Deleting channels
~~~~~~~~~~~~~~~~~


.. _here: http://devmx.de/software/teamspeak3-channel-watcher