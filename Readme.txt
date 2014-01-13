NeDi 1.0.8
==========

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

Changes from 1.0.7
------------------
The main focus lies in providing features for large networks. 

Features:
- XLS export for many lists (like Devices, Modules, Interfaces, Vlans and Nodes)
- User based timezone setting will adjust all timestamps accordingly
- Discard and Broadcast counters are added to DB to support new discard and broadcast reports and alerting on links
- Using salted sha256 instead of md5 to store passwords (all pw need to be reset and PHP requires mycrypt)
- Added "more" prompt handling for Comware3 switches
- Prefer interface description over MAC address for LLDP links
- Warning if no traffic is seen on a link
- New theme carbohydr8, revamped elec-tron and silverlite (while just cosmetics, I think network mgmt should be more fun!)
- Added stati.pl: To collect "network at a glance" statistics (e.g. every week) and announce them in the user chat.
- query.php: Interface to retrieve machine readable DB information
- Add shortcut of current view (e.g. a map) to admin message with a single click
- Several List modules can now include the last map and be limited (thus the javascript warning was removed)
- Improved System-Files to create configs for new devices which then can be auto-provisioned via dhcp/tftp
- Separate folders for maps and fotos for better handling of lots of files
- Added Topology-Link list module
- Added 95 percentile and link-status to interface graphs (only if not stacked & still experimental)
- Possibility to map a ESX-VM (modules table) to a MAC address in nodes table (experimental)
- Added Error reports showing duplicate assets, traffic errors/discards and link mismatches
- Added monitoring statistics to Device-Status
- Added basic IPv6 support for interfaces and nodes
- Added logic to select IP(v6) address and forwarding tables to Defgen as well as height property;intended for rackview (also added 1-click .def submission)
- Changed discovery notifications to be generated only if device is monitored
- Added SSH & telnet links for quicker device access in various modules
- Syslog severity 0 generates Emergencies now for more granular processing
- Added IP verification of monitored targets (better control for dhcp hosts) in Monitoring-Setup, also added a 1-click update feature to Monitoring-Setup
- Introducing new event-handler which lets you elevate and discard events based on regexps and event level.
- Improved modules to represent their physical class. This will also allow for detecting stacks! 
- Added arppoison detection to CLI based function (only works on ASA for now) and fixed arpcount tracking
- Added No IP link option to User-Profile to avoid telnet:// and ssh:// links for allowing browser addons to handle the IPs
- Noodle Search can be used with IP addresses in dotted notation now
- Rewrote WriteNodes to improve handling of large populations (~100k nodes!) Changed nodelock to show PID
- Turning VTP Domain and VTP Mode into more generic Group and Mode fields, with that those fields can be leveraged for other vendors
- Added Rackview to Topology-Table (based on snmp location ...room;rack;RU) as well as links to upper level locations
- Introducing device panels using a small jpg 250x23(per RU) and 15kB max for Rackview Topology-Map and Device-List
- Added more features like new arc linkstyle, room/rack location selector and loop indication to Topolgy-Map.
- Monitoring-Health now show all interfaces that where disabled within the last 24 hours (possible with new lastchg field in interfaces table).
- Changed "getfwd dyn" behavior (according to community suggestions), which allows IOS devices to effectively read dot1x controlled nodes as well
- Add direct Google links to device types to find support quickly on the web
- Click to highlight (double-click to clear) lines in the print form, to emphasize items if needed
- Added simple info page (me.php) which can be called from any client on the network
- Rearranging libdb-msq.pm to ease development of alternative backends
- Added USD/EUR/CHF Invoice generator (suggesting an annual contribution), which lets you support NeDi in a more official manner
- Added check to avoid overwriting SNMP devices by NoSNMP neighbours, which can happen due to misconfigured nedi.conf
- Added uppercase init option (-I) to delete rrds and configs. Lowercase now just initializes the DB.
- Removed -w option (import MAC samples from legacy kismet dumps)
- Added POWER-ETHERNET mib support for improved statistics (e.g. Report Device PoE), monitoring (e.g. failed PSUs, budget threshold) and
  toggling PoE on and off on supported devices (non indexed interfaces).
- Added XLS export for subnets in Other-Calculator to generate quick templates for network concepts etc.
- Improved custom/oem theme, contact me to learn more about rebranding NeDi...
- Added new option to skip getting info via webinterface of VoIP phones.
- Fixed some XSS and SQL injection vulnerabilities (required to revert to concat() in Topo-Map and Oth-Noodle)
- aligned skip keys to the warnings (e.g. a skips ARP and j skips IF addresses)
- Replaced SNMP max-message-size workarounds with limiting max-repetitions to 5. This is more reliable, should avoid fragmentation and be even faster!
- To avoid XSS any goto arguments in index.php has to start with grp-mod.php and &lt;script&gt; in inputs is discarded.
- To avoid SQL injection input strings are escaped as well, which might affect (regexp) flexibility.
- System-Export and System-Files are assigned to the admin group in nedi.conf as I rather preserve functionality and restrict access by default, than making them more secure by crippling functionality. 
- Many smaller bug fixes and optimizations!

Database:
- Bigger pw field for users with that a sha256 can be stored, miscopts (renamed from graphs) and groups increased to SMALLINT for future use.
- New fields in interface table for storing status change (removed transient values for ease of use), discards, broadcasts and monitoring thresholds (coming in 1.0.9) 
- Added monitoring thresholds fields for per device tuning (coming in 1.0.9) 
- New fields in networks and nodes table for storing IPv6 addresses. Changed mask field to hold prefixes
- Added testopt and testres to support real DNS queries and other tests where a string is sent and the result compared
- Adjusted ifmetric field in iftrack to smallint
- Adding username to nodes
- Adding physical class and status and modloc (for future use with distributed stacks) in Modules
- Added poe-budget and option (to make frontend aware of device capabilities) fields to devices
- Bigger mac address fields to include vlanid for more efficient IVL support (on iftrack and iptrack as well)
- Added timestamp for links for easy tracking

If you're updating from RC309 onward you can apply the changes with System-Export and don't have to initialize the DB:
nedi-309 ====================================================================
alter table modules add column modloc varchar(255);
alter table monitoring add column eventlvl tinyint unsigned after eventfwd;
nedi-027 ====================================================================
alter table devices add column maxpoe SMALLINT unsigned;
alter table devices add column totpoe SMALLINT unsigned;
