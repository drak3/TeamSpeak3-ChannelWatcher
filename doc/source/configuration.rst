Configuration
=============

The configuration is a plain php file where every aspect of the application can be configured.
In this document the most important configuration options are covered. For a more technical overview see the :doc:`Technical Overview <technical-overview>`, for a complete list of all configuration options see `the source code`_

.. _the source code: https://github.com/devMX/TeamSpeak3-ChannelWatcher/tree/bfe40d90f9ab1bc9a1ff6cb0ba9501fbdc726338/src/devmx/ChannelWatcher/DependencyInjection

Connection
----------

.. code-block:: php

    <?php

        $c['ts3']['host'] = '';

        $c['ts3']['query.port'] = 10011;

        $c['ts3']['vserver.port'] = 9987;

        //$c['ts3']['login.name'] = '';
        //$c['ts3']['login.pass'] = '';

    ?>

``$c['ts3']['host'] = '<your-data>';``
    Contains the ip adress or the hostname of your TeamSpeak3 Server.
    If your TeamSpeak3 Server is running on the same machine as the TeamSpeak3 ChannelWatcher does we suggest using ``localhost`` or ``127.0.0.1``.

``$c['ts3']['query.port'] = <your-port>;``
    Contains the query port of your TeamSpeak3 Server. The default value of ``10011`` should work on most machines.

``$c['ts3']['vserver.port'] = <your-v-server-port>;``
    Contains the port of the TeamSpeak3 Server which should be monitored by the TeamSpeak3 ChannelWatcher. The default port is ``9987``:

``$c['ts3']['login.name'] = '<your-login-name>';``
    If the guest account of the TeamSpeak3 Server Query has not the required permissions you need to login with a username and password (strongly suggested).
    Simply enter the username of the query user here. Be sure that you uncomment the lines ``$c['ts3']['login.name']`` and `$c['ts3']['login.pass']` by deleting the two ``//``.

``$c['ts3']['login.pass'] = '<your-password>';``
    If you need to login with a query account enter the password which belongs to the user whose name you entered in ``$c['ts3']['login.name']`` above here.



Deletion Time
-------------
You can configure the time which needs to pass until the TeamSpeak3 ChannelDeleter starts deleting channels in this block:

.. code-block:: php

    <?php

        $c['watcher']['time_to_live'] = array(
        'years'     => 0,
        'months'    => 0,
        'weeks'     => 0,
        'days'      => 0,
        'hours'     => 0,
        'minutes'   => 0,
        'seconds'   => 0,
        );

    ?>

You can see seven time intervals followed by an arrow and a number. Specify the number of the time intervals to set the deletion time. All intervals will be summed up to generate the deletion time.
For example if you set ``years`` to 1 and all others to ``0``, the ChannelDeleter will delete channels if nobody was in them for one year.
If you set ``weeks`` to ``1`` and ``months`` to ``1`` it will start deleting channels after one month and a week emptiness.

.. _blacklist:

Blacklist
---------
With the help of the blacklist option you can specify channels by their ids which should NOT be deleted by the ChannelDeleter.
Be sure that you enable the rule ``$c['watcher']['rule.acl_filter']`` as well. Unless the channels wont be ignored. See :ref:`aclfilter` for more information.

$c['watcher']['rule.acl_filter.blacklist'] = array(<channel-id>, <another-id>);
    Specify the channels by their ids and seperate them with commas. For example: ``[...]array(1, 2, 3, 4);`` will let the ChannelDeleter ignore the channels with id 1, 2, 3 and 4.

.. _aclfilter:
    
Rules
-----
Rules control the ChannelDeleter's behavior on crawling and deleting channels. For example you can specify that all spacers should be ignored. These are the currently available rules:

.. code-block:: php

    <?php
    
        $c['watcher']['rules'] = array(
                // this rule saves all channels that have visited parentes
                //$c['watcher']['rule.save_childs']  
                // This rule saves all channels that have visited childs  
                //$c['watcher']['rule.save_parent'],
                // this rule saves channels according to the specified black/whitelist
                //$c['watcher']['rule.acl_filter'],
                // this rules saves all spacers
                //$c['watcher']['rule.save_spacer'],
        );
    
    ?>
    
To enable a rule simply uncomment (remove the ``//``) the appropriate line.

``$c['watcher']['rule.save_childs']``
This rule will save any sub-channel if it parent was visited:
Consider the following example (* means visited):

  .. code-block:: text   
 
    -überclan *
        -raid1
            -healer
    
With the save_childs rule enabled, the raid1 and the healer channel won't be deleted
    
``$c['watcher']['rule.save_parent']``

This rule will save the parents of a channel if the channel itself was visited
Considering the example from above, but this time just the "healer" channel is visited:

  .. code-block:: text

    -überclan
        -raid1
            -healer*
            
With the save_parent rules enabled, the "überclan" and the "raid1" channel will be saved
    
$c['watcher']['rule.acl_filter']
    This rule enables the blacklist. To learn more about blacklists see :ref:`blacklist`.
    
$c['watcher']['rule.save_spacer']
    This rule saves all spacers from being deleted

Database
--------

The TeamSpeak3 ChannelWatcher runs with almost all common databases. For a full list of databases and their configuration can be found in the `doctrine documentation`_.
Be sure that you only uncomment (remove the ``/*...*/`` block) one database settings section.

SQLite
~~~~~~

.. code-block:: php

    <?php
    
        $c['db']['connection.params'] = array(
        'driver' => 'pdo_sqlite',
        'path' => $c['storagedir'].$c['profile'].'_db.sqlite',
        );
 
    ?>

This configuration should be kept in most cases as-is.
Moreover be sure that the directory of the ChannelWatcher is writable by the user who runs it, that the directory ``storage`` and the SQLite database can be created


MySQL
~~~~~

.. code-block:: php

    <?php
    
        $c['db']['connection.params'] = array(
        'dbname' => '<your-database>',
        'user' => '<your-username>',
        'password' => '<your-password>',
        'host' => '<your-host>',
        'port' => 3306,
        //'unix_socket' => '',
        'driver' => 'pdo_mysql',
        'charset' => 'utf8'
        );
    
    ?>

Information about the several parameters can be found in the `doctrine MySQL documentation`_.

PostgreSQL
~~~~~~~~~~

.. code-block:: php

    <?php
    
        $c['db']['connection.params'] = array(
        'dbname' => '<your-database>',
        'host' => '<your-host>',
        'port' => 0,
        'user' => '<your-username>',
        'password' => '<your-password>',
        'driver' => 'pdo_pgsql',
        );
        
    ?>

Information about the several parameters can be found in the `doctrine PostgreSQL documentation`_.

There are also configurations for more SQL-Server like the MSSql-Server or oracles oci. See the `doctrine documentation`_ for a full list. 

.. _doctrine documentation: http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html
.. _doctrine MySQL documentation: http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#pdo-mysql
.. _doctrine PostgreSQL documentation: http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#pdo-pgsql