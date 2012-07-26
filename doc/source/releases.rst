Releases
========

Versioning
----------

The TeamSpeak3 ChannelWatcher is versioned using the `Semantic Versioning (SemVer) Scheme`_
In short, that means that version numbers will have the form major.minor.release, where the major number will be increased 
on backwards compability (BC) breaks, the minor number is increased when new functionality is added, and the release is updated if a bugfix is applied.
There might be also a stability postfix (beta.3, rc.1, ...) for nonstable or preview versions.

.. _Semantic Versioning (SemVer) Scheme: http://semver.org

Release Cycle
-------------



Roadmap
-------

You can keep track of currently planned and already implemented features on github_

Note that the following is all subject to change (expect of the 1.0.0 release maybe). If we get any feedback or feature requests indicating 
that other features are more important, we'll probably change the roadmap.

.. _github: http://github.com

1.0.0
~~~~~

The current 1.0 release is 1.0.1, it is planned to support this version until 1.2.0 is released.
    
1.1.0
~~~~~

1.1.0 will be mostly a release that improves the overall usability and introduces some internal chances to be a stable base for the following releases.
It is planned to include a SaveDefaultChannelRule, improve the CLI interface and a cleanup mechanism for the database (automaticaly deleting old records).

1.2.0
~~~~~

1.2.0 will introduce features that make the ChannelWatcher more reliable.
This will include a logging/reporting mechanism, as well as improvements on the crawl-data validation.
Maybe there will be also some basic backup/restore utilities

1.3.0
~~~~~

1.3.0 will hopefully improve the ability to easily crawl multiple server at once. 
This might include simpler configuration files and commands for crawling multiple specified servers at once, 
as well as automaticaly crawling all virtualservers on a serverinstance. 