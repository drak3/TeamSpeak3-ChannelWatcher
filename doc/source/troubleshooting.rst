Troubleshooting
===============

.. note::
    If your question or problem is not listed here, you may

        - contact us via TeamSpeak_
        - visit our Support-Board_
        - write us an E-Mail_

    .. _TeamSpeak: ts3server://devmx.de
    .. _Support-Board: http://support.devmx.de
    .. _E-Mail: http://devmx.de/impressum

Errors
------

SQLite: storage not writable
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
If you are SQLite-user and you get an error that the directory ``storage`` is not writable, be sure that you set chmod (on Linux-based machines) correctly.
Therefore the user who runs the TeamSpeak3 ChannelWatcher should be owner of the application files. You can set the owner with this script.
Replace ``<user>`` with the username of the user who runs the TeamSpeak3 Channelwatcher.

.. code-block:: bash

    $ chown -R <user>:<user> .

Also be sure that the user has writing permissions on the directory ``storage``

.. code-block:: bash

    $ chmod -R 640 ./storage


Configuration
-------------

Enabling fsockopen()
~~~~~~~~~~~~~~~~~~~~
On some PHP-Configurations the function ``fsockopen`` is disabled by default.
To enable it, open the file ``php.ini`` in ``/etc/php5/cli`` (on Debian Systems) and search for those line

.. code-block:: ini

    disable_functions = fsockopen

and remove fsockopen, that it looks like the following

.. code-block:: ini
    
    disable_functions =

Be sure that you edit the configuration file of the cli and not the webservers one.

Whitelisting IP-Adress
~~~~~~~~~~~~~~~~~~~~~~
If you are getting banned by the TeamSpeak3 Server very often you may need to add the IP-adress with which you are connecting to the file ``query_ip_whitelist.txt``, which is located in the TeamSpeak3 Server directory.
Open the file and enter your IP-Adress into a new line of the file (``localhost`` resp. ``127.0.0.1`` should exist already) and save it.
Normally the TeamSpeak3 Server should reload the file automatically but to he sure you can restart the TeamSpeak3 Server.
