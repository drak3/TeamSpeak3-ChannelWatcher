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


Blacklist
---------


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

