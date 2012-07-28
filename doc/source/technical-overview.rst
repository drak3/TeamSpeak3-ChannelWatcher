Technical Overview
==================
This document describes the technical structure of the ChannelWatcher
Note that this description is based on the source code of `the development-repository on github`_ and not on the distribution served on devmx.de

.. _the development-repository on github: https://github.com/devMX/TeamSpeak3-ChannelWatcher

The big picture
---------------

Used libraries
~~~~~~~~~~~~~~

The TeamSpeak3-ChannelWatcher is a relatively simple App that uses a lot of 3rd-Party libraries:

 - `The Doctrine DBAL database abstraction layer`_ for database independence, so the app is runable on alot of databases.

 - `The Symfony Console Component`_ to ease the writing of the Console Commands.

 - `The Pimple Dependency Injection Container`_ a simple but powerfol DI-Container that is thoroughly used for configuring the application.

 - `The TeamSpeak3-Library`_ for accessing the TeamSpeak3-Query.

All these libraries are tied together by `composer`_ an dependency manager for php.

.. _The Doctrine DBAL database abstraction layer: http://www.doctrine-project.org/projects/dbal.html
.. _The Symfony Console Component: http://symfony.com/doc/current/components/console.html
.. _The Pimple Dependency Injection Container: https://github.com/fabpot/Pimple
.. _The TeamSpeak3-Library: https://github.com/devMX/TeamSpeak3-Library-dev
.. _composer: http://getcomposer.org/

Directory Structure
~~~~~~~~~~~~~~~~~~~

The source code of the ChannelWatcher can be found in the ``src/`` directory, the (few) unittests can be found in ``test/``. All classes follwo the psr-0_ naming convention.
The documentation written in ReStructuredText is stored in ``doc/``. After a composer installation, the ``vendor/`` directory contains
the vendor libraries mentioned above

.. _psr-0: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md

Application Structure
~~~~~~~~~~~~~~~~~~~~~

The main entry point to the application is the ``app.php`` script. It does nothing more than instantiating the ``ApplicationContainer``, retrieving the ``application`` service from it and call the ``run()`` method.
The application then decides which command it should execute.

The command may depend on a configuration (in fact, all commands expect the ``help`` and the ``list`` command depend on a configuration)
If so, the configfile (internally sometimes called profile) is loaded.

Then, the command can retrieve various services from the Container.
The three most important services are the ``crawler``, the ``deleter`` and the ``storage``. 

Basically, the ``storage`` is responsible for storing the dates when a channel was seen the last time with members in it. By default everything is stored in a database.
To feed the ``storage`` with data, the ``crawler`` connects to the TeamSpeak3-Server and generates a list with all visited channels.
The ``deleter`` then takes this data to decide which channels could be deleted. It has also a simple heuristic to decide if there were enough crawls based on the crawl times provided by the ``storage``.

See the sections below for a more detailed explanation of these components.


The Container
-------------

The Dependency-Injection-Container (DIC) contains all object definitions and various parameters to configure this objects.
The main container is the ``AppContainer`` which aggregates multiple sub-containers (they can be accessed like this: ``$c['ts3']``):

 - The ``ts3`` container contains object definitions for TeamSpeak3 related services and is preconfigured by the TeamSpeak3-Library
 - The ``db`` container contains object definitions for Doctrine-DBAL related services. It is defined in the ``DbalContainer`` class.
 - The ``watcher`` container contains object definitions for services related to the ``deleter`` and the ``crawler`` It is defined in the ``WatcherContainer`` class.

All container definitions (except of the ``ts3`` container, which is part of the TeamSpeak3-Library) can be found in the ``\devmx\ChannelWatcher\DependencyInjection`` namespace.

Configuration
~~~~~~~~~~~~~
The config file does nothing more than changing the preconfigured Pimple container, therefore one can change nearly anything from within the configfile,
such as using a completely different storage, or even switch a library.

The ChannelCrawler
------------------
As mentioned above, the ChannelCrawler is responsible for filling the storage. It is implemented by the ``\devmx\ChannelWatcher\ChannelCrawler`` class
and relies on a storage and on TeamSpeak3-Query.

The ChannelWatcher
~~~~~~~~~~~~~~~~~~
The ChannelWatcher is currently just a thin wrapper around the ChannelCrawler: He crawls and sleeps in an infinite loop.

The ChannelDeleter
------------------
The ChannelDeleter is responsible for deleting the unvisited channels. It is called through the DeleteCommand.
First, the ChannelDeleter fetches all unvisited channels from the storage. Then it filters it with the help of all enabled rules.
The remaining channels are then (after the user confirms) deleted from the server.
As of 1.1.0 the storage is cleaned up after a delete to remove unneeded information about crawls and deleted channels. 
(This action is taken in the DeleteCommand and not in the ChannelDeleter itself)

Rules
~~~~~
A rule is a simple Class that is able to decide if a specific channel should be deleted or not.
See :doc:`Write your own rule <write-your-own-rule>` for a detailed description of how a rule works, and how to implement a new rule.

The Storage
-----------
The Storage abstracts the way data gathered by the Crawler is stored and retrieved. A storage must implement the StorageInterface (``\devmx\ChannelWatcher\Storage\StorageInterface``)
It stores information about each channel and its last visit plus data about every executed crawl.
The currently used implementation is the DbalStorage, that stores data in a relational database like SQLite or MySQL

The Database
~~~~~~~~~~~~
The DbalStorage uses the Doctrine-Dbal library to communicate with a database.
There are two tables: <prefix>channels and <prefix>crawl_data. Whereas <prefix> is specified in the AppContainer and has the following scheme: ``devmx_ts3_channelwatcher_<profile>__``
This makes it possible to reuse a database for several configurations.
