Commandline-Interface
=====================

Here all commands with their respective parameters and options are documented.
An invocation of the ChannelWatcher follows this scheme: `php app.php <command> <options> <parameters>`
On Linux or similar systems you can also invoke `app.php` directly (e.g. `./app.php <command> <options> <parameters>`)

Global Options
--------------
The following options are availible in all commands

===================     ==========================================
      Option                              Meaning
===================     ==========================================
--help|-h               Show detailed help for the called command
--quiet|-q              Do not output anything
--verbose|-v            Increase verbosity of (error-)messages
--version|-V            Display the version
--ansi                  Force ANSI output
--no-ansi               Disable ANSI output
--no-interaction|-n     Do not ask interactive questions
===================     ==========================================

crawl
-----
The crawl command connects to the configured server and collects data about the channel-usage.

Usage:     `php app.php crawl <config>`

Arguments:

            config:  the configfile of the server to interact with (without `.php`!)

Options:

            None.


delete
------
The delete command deletes all unused channels based on the crawled data.

Usage:      `php app.php delete [-f|--force] [--delete-non-empty] [--trust-crawls] <config>`

Arguments:

            config:  the configfile of the server to interact with (without `.php`!)

Options:

            -f|--force:         do not ask for confirmation before deleting (use is discouraged)
            --delete-non-empty: delete channels that have clients in it
            --trust-crawls:     delete even if there seems to be not enough data (use is discouraged)

init
----
The init-command initializes a given config. Should normally be run automatically by the other commands.

Usage:      `php app.php init <config>`

Arguments:

            config:  the configfile of the server to interact with (without `.php`!)

Options:
            None.


print_unused
------------
The print_unused-command shows all channels of the given config that are unused.

Usage:      `php app.php print_unused <config>`

Arguments:

            config:  the configfile of the server to interact with (without `.php`!)

Options:
            None.

db:migrate
----------
.. Warning::
    This command is deprecated, use the `init` command instead.

The db:migrate command updates the database-scheme. Should normally be run automatically by the other commands.

Usage:      `php app.php db:migrate <config>`

Arguments:

            config:  the configfile of the server to interact with (without `.php`!)

Options:

            None.


help
----
The help command prints detailed help for a specific command.

Usage:      `php app.php help <command>`

Arguments:

            command: the command you want to see the help for

Options:

            --xml:  Prints the help in xml-format.

list
----
The list command outputs all available commands

Usage:      `php app.php list [--xml] [--raw] [<namespace>]`

Arguments:

            namespace: (optional) the namespace commands should be shown for (not very useful here, though)

Options:

            --xml:  output command-listing as xml.
            --raw:  output raw command-listing.


