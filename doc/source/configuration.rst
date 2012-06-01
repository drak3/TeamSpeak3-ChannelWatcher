Configuration
=============

You will find any configuration parameters of ``example.php`` in ``config/`` described here.

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

$c['ts3']['host'] = '<your-data>';
    Contains the ip adress or the hostname of your TeamSpeak3 Server.
    If your TeamSpeak3 Server is running on the same machine as the TeamSpeak3 ChannelWatcher does we suggest using ``localhost`` or ``127.0.0.1``.

$c['ts3']['query.port'] = <your-port>;
    Contains the query port of your TeamSpeak3 Server. The default value of ``10011`` should work on most machines.

$c['ts3']['vserver.port'] = <your-v-server-port>;
    Contains the port of the TeamSpeak3 Server which should be monitored by the TeamSpeak3 ChannelWatcher. The default port is ``9987``:

$c['ts3']['login.name'] = '<your-login-name>':
    If the guest account of the TeamSpeak3 Server Query has not the required permissions you need to login with a username and password (strongly suggested).
    Simply enter the username of the query user here. Be sure that you uncomment the lines ``$c['ts3']['login.name']`` and `$c['ts3']['login.pass']` by deleting the two ``//``.

$c['ts3']['login.pass'] = '<your-password>';
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

Blacklist
---------
With the help of the blacklist option you can specify channels by their ids which should NOT be deleted by the ChannelDeleter.

$c['watcher']['rule.acl_filter.blacklist'] = array(<channel-id>, <another-id>);
    Specify the channels by their ids and seperate them with commas. For example: ``[...]array(1, 2, 3, 4);`` will let the ChannelDeleter ignore the channels with id 1, 2, 3 and 4.

Rules
-----

Database
--------

SQLite
~~~~~~

MySQL
~~~~~

PostgreSQL
~~~~~~~~~~

