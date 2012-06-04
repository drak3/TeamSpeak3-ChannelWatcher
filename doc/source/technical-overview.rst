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

All these libraries are tied together by `composer`_ an dependency manager for php

.. _The Doctrine DBAL database abstraction layer: http://www.doctrine-project.org/projects/dbal.html
.. _The Symfony Console Component: http://symfony.com/doc/current/components/console.html
.. _The Pimple Dependency Injection Container: https://github.com/fabpot/Pimple
.. _The TeamSpeak3-Library: https://github.com/devMX/TeamSpeak3-Library-dev
.. _composer: http://getcomposer.org/

Directory Structure
~~~~~~~~~~~~~~~~~~~

The source code of the ChannelWatcher can be found in the ``src/`` directory, the (few) unittesets can be found in ``test/``. All classes follwo the psr-0_ naming convention.
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

All container definitions (except of the ``ts3`` container, which is part of the TeamSpeak3-Library) can be found in the \devmx\ChannelWatcher\DependencyInjection namespace


The ChannelCrawler
------------------
to be written...

The ChannelWatcher
~~~~~~~~~~~~~~~~~~
to be written...

The ChannelDeleter
------------------
to be written...

The Storage
-----------
to be written...

The Database
~~~~~~~~~~~~
to be written...
