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

.. _update-php:

Segfault on crawling (Updating PHP-Version)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
If you receive a segfault-error on trying to run the TeamSpeak3 ChannelWatcher, you have a too old PHP version prior to 5.3.7.
Please note that at least PHP 5.3.7 is required to run the ChannelWatcher. **All earlier PHP versions** are not supported.

To update do a current php-version check your current version via ``php -v`` first. If it's lower than 5.3.7 you need to upgrade your PHP-Version.
On debian based systems run ``apt-get update && apt-get upgrade`` first and try to upgrade the version automatically. If the version is still lower than 5.3.7 after that, you need to add the `dotdeb-repo`_ to get a newer version:

.. code-block:: bash

    $ deb http://packages.dotdeb.org squeeze all
    $ deb-src http://packages.dotdeb.org squeeze all
    $ wget http://dotdeb.org/dotdeb.gpg
    $ cat dotdeb.gpg | sudo apt-key add -

After that run again ``apt-get update``. Once finished run ``apt-get upgrade`` and ``apt-get dist-upgrade``. If you have installed the updates check your PHP-Version again via ``php -v``. It should now be greater than 5.3.7 (5.3.13). There should be any segfault error anymore.


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

PDOException: could not find driver
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
This error is most likely caused by a missing installation of the database driver.
Try to install the missing packages on your system and enable them in your php.ini.
(For example on debian you need the package php5-sqlite to run the TeamSpeak3-ChannelWatcher with a SQLite database)


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

Be sure that you edit the configuration file of the cli and not the one of the webserver.

Whitelisting IP-Adress
~~~~~~~~~~~~~~~~~~~~~~
If you are getting banned by the TeamSpeak3 Server very often you may need to add the IP-adress with which you are connecting to the file ``query_ip_whitelist.txt``, which is located in the TeamSpeak3 Server directory.
Open the file and enter your IP-Adress into a new line of the file (``localhost`` resp. ``127.0.0.1`` should exist already) and save it.
Normally the TeamSpeak3 Server should reload the file automatically but to he sure you can restart the TeamSpeak3 Server.

.. _dotdeb-repo: http://dotdeb.org