NeDi 1.1
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
"under-the-hood" Enhancements:
- Enhanced arpwatch support to factor in timestamp and support ouidev
- Changed discovery behaviour to not try other communities or fall back to SNMP v1 on existing devices upon failure
- Added version override option to allow for changing SNMP version of existing devices
- Support MAC addresses as ouidev for more flexibility
- Updated wlan MAC samples. Let me know, if you see false positives in Reports-Wlan now!
- Adding ignoreconf to skip backup of sensitive information
- Added actions like CLI commands or adding to inventory to be performed after discovery
- Added 2nd dependency for monitoring (not really tested yet) to cover typical network designs

GUI Enhancements:
- Replaced >10y old javascript menu with css
- Removed Other-Plot and dependencies as they're not properly sanitized (and obsolete)
- Added memory usage and timestamp to GUI debug output for profiling
- Improved Cisco PoE support. You can toggle delivery in Devices-Status (experimental, check if it selects proper port!)
- Removed map-level "snmpdev" as it can be handled with one of the 4 filters now
- Changed behavior of lists in print preview, with that single clicks toggle highlighting
- Replaced "Fast" mode in System-NeDi with a text box for granular skip options and added some templates
- Removed limit of 100 in System-NeDi, since the session won't be blocked anymore (same for Nodes-Toolbox and Devices-Write)
- Supporting multiple buildings per address (i.e. HQroad_Bld1) in Topology-Table and dropped router indicator
- Added inventory (formerly stock) info to Devices-Status and -Reports-Inventory
- Leveraging inventory table for patch panels (and unmanaged servers) in racks
- Supporting stacks > 9
- Added "newIT" theme with fixed header
- Numerous changes for html5 compliance (ongoing process)
- Many smaller bug fixes and optimizations

Hints
-----
Quick run to add network infrastructure to the inventory:
# nedi.pl -Aall -SOApjedibatfwgov -Ysm


DB Changes from 1.0.9
---------------------
Copy & paste the commands below into System-Export (line by line):

-- Moving away from stock tracking to real asset mgmt. (Delete Devices-Stock.php and adjust nedi.conf)
RENAME TABLE stock TO inventory;


-- Cover redundant dependencies and some cleanup
ALTER TABLE monitoring modify COLUMN depend VARCHAR(64) DEFAULT '';
ALTER TABLE monitoring ADD COLUMN depend2 VARCHAR(64) DEFAULT '' AFTER depend;
ALTER TABLE monitoring change COLUMN lostalert noreply TINYINT UNSIGNED DEFAULT 2;

-- Indexes are no longer limited to 8 characters. While this improves performance on enterprise networks, it's not mandatory to alter existing DBs.

-- Not mandatory, but is needed on Nexus devices with ridiculously high indexes to avoid false module changes:
ALTER TABLE modules MODIFY COLUMN modidx INT UNSIGNED DEFAULT 0;
