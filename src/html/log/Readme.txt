NeDi 1.4
========

Introduction
------------
NeDi discovers, maps and inventories your network devices and tracks connected end-nodes.
It contains a lot of features in a user-friendly GUI for managing enterprise networks.
For example: MAC address mapping/tracking, traffic & error graphing, uptime monitoring,
correlate collected syslog & trap messages with customizable notification, drawing
network maps, extensive reporting features such as device software, PoE usage, disabled
interfaces, link errors, switch usage and many more. It's modular architecture allows for
simple integration with other tools. For example Cacti graphs can be created purely based
on discovered information. Due to NeDi's versatility things like printer resources can be
monitored as well...

Changes from 1.0.9
------------------

What happened to 1.1? Well, after the 1.1beta a decision was made to completely change node
handling ahead of schedule. The impact on the backend and NeDi is worth a minor revision
change by itself! But it gets better! The reason why this was done; nodes can have more
than 1 IP and IPv6 address now! This meant splitting nodes, their IPs and names into
separate tables (hence all the DB changes). But wait, there's more! Since DNS names no
longer depend on the nodes, NeDi can be used to scan DNS names and compare the results to
the real world. This allows for finding DNS names pointing to unused IPs etc. Another big
item is asset management, which required changes in the menu structure and inventory table.
Last but not least 1.4 aligns with 2014 and the intension is to keep the minor update per
year schedule... 

There are quite some changes in nedi.conf, thus it might be a good idea to copy & paste
your settings over to the new nedi.conf!

"under-the-hood" Enhancements:
- Complete rewrite of node discovery and separation of MAC, IP and DNS information
- Changed skipping (-S) options for easier handling. Please review your crontab, if used!
- Modules no longer require a slot, but a description OID to make it more flexible
- Enhanced arpwatch support to factor in timestamp and support ouidev
- Changed discovery behaviour to not try other communities or fall back to SNMP v1 on existing devices upon failure
- Added version override option to allow for changing SNMP version of existing devices
- Support MAC addresses as ouidev for more flexibility
- Updated wlan MAC samples. Let me know, if you see false positives in Reports-Wlan now!
- Adding ignoreconf to nedi.conf for skipping backup of sensitive information
- Added support for handling available memory, in case total- and used-memory OIDs need to be read
- If DB init is called with "-i nodrop", no mysqladmin is required and existing DB is used
- Changed behavior of -t which now expects granular test-options and relies on -a or -A etc.
- Added support for read-only prompts, which can show log or mac-address tables...
- Added actions like CLI commands (optinal alert on output change) or adding to inventory to be performed after discovery
- Moved CLI commands & output away from html, to protect it from unauthorized access
- Added CLI support for Zyxel ZynOS switches
- Added 2nd dependency for monitoring to cover typical network designs (should be network infrastructure)
- Monitoring is a 2 stage process now (brought back non-blocking uptime test and have other services checked afterwards)
- Added level argument to debug option -d for granular DB, CLI or system usage investigation
- Removed contrib folder as most of them are obsolete. Please use the forum to keep them alive!
- Removed html/test folder copy from NeDi 1.0.9, if you need them

GUI Enhancements:
- Replaced >10y old javascript menu with css. Also shows active menu in bold
- Refactored css to move away from group based properties to header, body, footer styling
- Removed Other-Plot and dependencies as they're not properly sanitized (and obsolete)
- Added memory usage and timestamp to GUI debug output for profiling
- Improved Cisco PoE support. You can toggle delivery in Devices-Status (experimental, check if it selects proper port!)
- Removed map-level "snmpdev" as it can be handled with one of the 4 filters now
- Changed behavior of lists in print preview, with that single clicks toggle highlighting
- Replaced "Fast" mode in System-NeDi with a text box for granular skip options and added some templates
- Removed limit of 100 in System-NeDi, since the session won't be blocked anymore (same for Nodes-Toolbox and Devices-Write)
- Supporting multiple buildings per address (i.e. HQroad_Bld1) in Topology-Table and dropped router indicator
- Added inventory (formerly stock) info to Devices-Status and -Reports-Inventory
- Leveraging inventory table for patch panels (and unmanaged servers) in racks (TODO move to infrastructure?)
- Devices-Status allows for adding Clouds (e.g. ISPs) with several interfaces
- Added 7th location field to define device size (also works for maplo on ESXi for example)
- Added inclusion of log/iftools.php on each interface of Devices-Status.
- Added Monitor button to Topology-Maps, which adds an <img> link of the current map in log/montools.php.
- Changed Monitoring-Health to include log/montools.php instead of the topology-table, if #columns is set to 0.
- Supporting stacks > 9
- Added "newIT" theme with fixed header, removed old ones (set yours to default prior upgrade to avoid mess)
- Redesigned Nodes-Status for a more "helpdesk" oriented view
- Numerous changes for html5 compliance (ongoing process)
- Many smaller bug fixes and optimizations

Hints
-----
Quick run to add network infrastructure to the inventory:
# nedi.pl -Aall -SOApjedibatfwgov -Ysm


DB Changes
----------

*** I had to completely rebuild the nodes handling. The DB changes have gotten way too complex to preserve the data.
*** I am sorry to say this again, but you'll need to initialize the DB!

You can still preserve some manually supplied information using the following steps: 

1) Stock/Inventory
Export your current stock/inventory table to XLS and save as CSV (can be done directly in System-Export as well).
Re-arrange the columns to match the invenory table and import it using System-Files.

2) Monitoring
ALTER TABLE monitoring change COLUMN depend depend1 VARCHAR(64) DEFAULT ''; (Apply when upgrading from 1.1-beta)
ALTER TABLE monitoring ADD COLUMN depend2 VARCHAR(64) DEFAULT '' AFTER depend1;
ALTER TABLE monitoring change COLUMN lostalert noreply TINYINT UNSIGNED DEFAULT 2;
It should look like this (in mysql):
+-------------+----------------------+------+-----+---------+-------+
| Field       | Type                 | Null | Key | Default | Extra |
+-------------+----------------------+------+-----+---------+-------+
| name        | varchar(64)          | NO   | PRI | NULL    |       |
| monip       | int(10) unsigned     | YES  |     | NULL    |       |
| class       | char(4)              | YES  |     | dev     |       |
| test        | char(6)              | YES  |     |         |       |
| testopt     | varchar(64)          | YES  |     |         |       |
| testres     | varchar(64)          | YES  |     |         |       |
| lastok      | int(10) unsigned     | YES  |     | 0       |       |
| status      | int(10) unsigned     | YES  |     | 0       |       |
| lost        | int(10) unsigned     | YES  |     | 0       |       |
| ok          | int(10) unsigned     | YES  |     | 0       |       |
| latency     | smallint(5) unsigned | YES  |     | 0       |       |
| latmax      | smallint(5) unsigned | YES  |     | 0       |       |
| latavg      | smallint(5) unsigned | YES  |     | 0       |       |
| uptime      | int(10) unsigned     | YES  |     | 0       |       |
| alert       | tinyint(3) unsigned  | YES  |     | 0       |       |
| eventfwd    | varchar(255)         | YES  |     |         |       |
| eventlvl    | tinyint(3) unsigned  | YES  |     | 0       |       |
| eventdel    | varchar(255)         | YES  |     |         |       |
| depend1     | varchar(64)          | YES  |     |         |       |
| depend2     | varchar(64)          | YES  |     |         |       |
| device      | varchar(64)          | NO   | MUL | NULL    |       |
| notify      | char(32)             | YES  |     |         |       |
| noreply     | tinyint(3) unsigned  | YES  |     | 2       |       |
| latwarn     | smallint(5) unsigned | YES  |     | 100     |       |
| cpualert    | tinyint(3) unsigned  | YES  |     | 75      |       |
| memalert    | int(10) unsigned     | YES  |     | 1024    |       |
| tempalert   | tinyint(3) unsigned  | YES  |     | 60      |       |
| poewarn     | tinyint(3) unsigned  | YES  |     | 75      |       |
| arppoison   | smallint(5) unsigned | YES  |     | 1       |       |
| supplyalert | tinyint(3) unsigned  | YES  |     | 5       |       |
+-------------+----------------------+------+-----+---------+-------+
30 rows in set (0.01 sec)


3) In System-Export select "Export" then pick incidents, inventory, locations, monitoring with Destination set to 'gzip' and store archive

4) Save nedi.conf and seedlist to your desktop

5) Comment nedi in crontab

6) Upload nedi-1.1.tgz with System-Files using task "Update NeDi (Replace Configuration)"

7) Put your customizations back in nedi.conf

8) Copy seedlist back (or edit in System-Files for example) 

9) Init DB (e.g. System-NeDi "Init")

10) Upload the DB export archive with System-Files task "Import DB"

11) Compare new discovery options to your crontab and uncomment nedi again
