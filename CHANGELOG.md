# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)

## 7.2

## Added

- test LDAP connection on save
- can utilize LDAP as part of Auto Location if the LDAP location field is set
- include link within Checkout Request notification email
- link to [https://endoflife.date](https://endoflife.date) on OS page to help find EOL for operating systems

## Changed

- changed the default user icon to a Material Design Icon - "account"

## Fixed

- ensure local user password field is masked
- redirect to initial URL after login (long time bug)
- blank notes field failing out new devices additions

## 7.1

### Added

- added upcoming license expirations to the dashboard
- locations screen hyperlinks the device count to view assigned devices
- can add license keys to a device directly from the device info page
- default schema file for new installs
- Added the attribute Last Boot as a device type option. Powershell script will collect this automatically. #35

## Changed

- minor cosemetic items
- utilize dynamic autocomplete when searching for device or application names instead of giant drop down list
- utilize CakePHP json output instead of special json render file
- switch to `FormHelper->control()` from `FormHelper->input()` to auto detect field data type
- renamed "Send Emails" to "Receive Emails" in user area - this makes more sense for what this setting does

## Fixed

- fixed changing device checkout status when a request is already assigned (#34)
- fixed settings encryption, missed this during initial framework update
- minor PHP 8 issues
- sorting on search pages
- Install instructions now updated for CakePHP 4 framework
- REGEX issue if Location didn't have auto location string

## 7.0

### Added

- exported CSV files now only show filtered search results instead of entire table
- some basic error checking (licenses, checkout requests) is now completed on decommissioning or deleting of devices (#22)
- viewer to see driver files in the /drivers/ directory and delete them
- new Dashboard page - shows default metrics such as total devices, total applications, checkout requests, and recent activity
- Additional logging for various website operations, including the user that completed the action. Actions impacted are: Logins, Devices added/removed/changed, lifecycles added/removed/changed, checkouts approved/denied/extended/check-in/check-out, devices types added/removed, schedules created/removed, locations added/removed/changed, decomissioned devices hard drive or recycle status. 

### Changed

- Updated to CakePHP 4 framework from CakePHP 2
- layout of Services search page matches other search pages
- Jquery updated to 3.7.1
- DataTables updated to 2.2.2
- CSV exports now done in Javascript instead of PHP
- layout of Operating Systems matches License page (highlighting dates and general organization)
- CronExpression library updated to 3.4.0. This is a [breaking change](http://ctankersley.com/2017/10/12/cron-expression-update/) to how the next dates are calculated. New lib is in line with how cron should actually function. 
- CheckoutRequests times are now calculated based on [FrozenTime](https://book.cakephp.org/4/en/core-libraries/time.html) functions instead of based on php date() unix time comparisons. 
- Checkout requests can now be deleted if the check in time has past without denying them first (#30)
- Decommissioned devices now save the display attributes of the parent device type. Only valid attributes are shown on the Decommissioned pages now. 
- Driver upload information now references Powershell commands instead of old Double Drivers tools. This is outdated and no longer necessary. 
- Dashboard page replaces full inventory as default landing page. Full inventory is now a link in the Devices menu 
- root `/` now redirects to Dashboard. 
- Logs table now includes a `USER` column. Populated for website log messages to capture the user that performed the action.
- REST API now utilizes HTTP Methods instead of url actions. This is inline with how REST APIs are typically used. Examples: instead of `/api/inventory/exists` you call `/api/inventory` using a the HTTP GET method. The same endpoint `/api/inventory/` using POST would add a new device. 
- Updater Powershell script updated to utilize GET, POST, PUT, or DELETE method calls as appropriate with the REST API. 
- REST API endpoints for `/applications` and `/services` now take a list of each to update instead of a single entry. This greatly speeds up bulk additions to the database. The updater script is also updated to reflect this change. 

### Fixed

- new device duplicate check now checks name and device type
- extending checkout times now fails if the check in date is in the past or is set prior to the check out date (#29)

### Removed

- removed Application monitoring feature (alerting if certain app found installed). This was seldom used and didn't really have a good use case. The way software is managed in a modern environment users should not be able to install rogue software. 

## 6.0

### Added

- Added __parent license__ feature to replace existing license functionality. Licenses now include the name, vendor, and optional expiration dates
- Old licenses renamed __license keys__. License keys are attached to a parent license and now include a quantity value. Individual key codes are attached to devices up to the quantity listed. 
- New command - __License Renewal Reminders__ can be setup to email renewal reminders based on license expirate date and the renewal threshold. 

### Changed

- previous license key functionality is moved to the new parent license and license key model. There is __no automated migration path__ to move existing licenses to the new model. 

- modified layout of the Checkout Requests page. Requests are now split into separate areas (New, Active, and Upcoming). Each has their own functions and are moved when the status changes. Areas only appear when there are requests in them to cut down on clutter. 

## 5.1

### Added

- log external email addresses when sending
- allow manually deleting denied checkout requests
- "New Available" badge in Lifecycle views if newer version exists in DB
- modified Lifecycle information to display number of installs on previous, current, and newer versions than the one being tracked
- Notes fields now render Markdown
- Ability to change check-in date for requests - checks downstream requests for conflicts #24
- added admin name to Checkout Request approval email #23

### Fixed

- need to pass in external recipient in SendEmailsTask
- showing deny button for approved requests when device is checked out elsewhere

## 5.0

### Added

- (Equipment Device Checkout)[https://github.com/eau-claire-energy-cooperative/simple-inventory/issues/20] brand new feature that allows users to request equipment for checkout. Admins can flag available equipment and manage checkout requests via an approve/deny process. Equipment shows it's current checkout status and can be moved to a temporary location when currently checked out. 
- Scheduled task to purge expired checkout requests. Works for requests where the time window has expired and the equipment is not checked out.
- reconfigured search result pages to use the selected attributes like the main inventory page does

### Changed

- device info page now has an action menu drop down to collapse functions related to editing, decommissioning and deletion
- consolidated "isAuthenticated" logic to controller parent class instead of having in each child controller
- modified the `login.ctp` layout so it can be used for other non-authenticated pages
- email functions will accept and optional `recipient` variable. This overrides default behavior, which is to email all admin users. 

### Fixed

- fixed icons on advanced settings page (/settings2)
- drop down menus needed double click to work properly, fixed by correcting javascript imports
- limit login history to 50 for faster SQL queries
- fixed action buttons on license management page to match all other pages

## 4.2

### Fixed

- minor UI formatting

### Added

- licenses can be removed from a device on the moreInfo screen instead of having to go back into Licenses

### Changed

- minor readability improvements to PingComponent

## 4.1

### Fixed

- fixed issue with updater script not copying powershell files
- made sure third party vendor files were being included

## 4.0

### Added

- added purge of old computer logins to the log purge Task
- action buttons now have titles when hovering with the mouse
- new Applications area with a search ability and filter for devices that are attached to the application
- Software Lifecycle management - add a lifecycle to an Application along with an update frequency and notes for updates
- CSV downloads for both Application and Lifecycle tables
- anchor to cron syntax explanation since used throughout views
- ability to manually add/remove disks from devices through the edit screen
- operating systems page under Software to view all assigned operating systems, device counts, and end of life information
- clicking license key codes will copy them to the clipboard

### Changed

- reconfigured menus to split screens between Devices (Hardware) and Software
- changed Programs to Applications - major DB shift. Applications now have their own table and are linked to devices with a linking table.
- Applications can now be assigned and unassigned directly from a device
- settings encryption now down with OpenSSL instead of Mcrypt. _Set Encryption to False prior to upgrading_

### Fixed

- added default cron syntax for Task setup
- minor formatting between different views for consistency
- disk ordering on device info page

### Removed

- Removed RestrictedPrograms model - this is now done within the Application model with a monitoring field.

## 3.1

### Fixed 

- device types variable error on add device page
- decommission was failing due to missing Model import
- fixed issue the ldap_bind where blank passwords treated as anonymous logins

## 3.0

### Added 

- added purge of old computer logins to the log purge Task
- action buttons now have titles when hovering with the mouse
- new Applications area with a search ability and filter for devices that are attached to the application
- Software Lifecycle management - add a lifecycle to an Application along with an update frequency and notes for updates
- CSV downloads for both Application and Lifecycle tables
- anchor to cron syntax explanation since used throughout views
- ability to manually add/remove disks from devices through the edit screen
- operating systems page under Software to view all assigned operating systems, device counts, and end of life information

### Changed

- reconfigured menus to split screens between Devices (Hardware) and Software
- changed Programs to Applications - major DB shift. Applications now have their own table and are linked to devices with a linking table. 
- Applications can now be assigned and unassigned directly from a device

### Fixed

- added default cron syntax for Task setup
- minor formatting between different views for consistency
- disk ordering on device info page
- fixed visible icon on 802.1x supplicant password field

### Removed

- Removed RestrictedPrograms model - this is now done within the Application model with a monitoring field. 

## 2.3

### Added

- added ability to manually assign or unassign programs from a device. This will get cleared during auto update so only use for devices that aren't getting pulled in via a script

### Changed

- changed icon provider from Google Material Icons to Material Design Icons. Many more icons to choose from.

## 2.2

### Added

- emails can be added to the queue from any controller, these are sent to anyone with the "send email" flag set to true
- emails sent when devices are decommissioned, useful if this information needs to be passed on to other systems
- ability to delete from the decommissioned table to completely clear out old devices
- new Scheduled Task to automatically purge decommmissioned devices after so many years
- new Schedule Task to automatically purge logs after so many years

### Fixed

- only show categories on the edit screen that exist in that device type

## 2.1

### Added

- added Material Icon CSS and font files
- device type editing pages now include a realtime preview of the icon before saving
- show real auth key on downloads page when listing example command
- added links to Wiki page on how to configure the GPO for the updater script

### Changed

- make sure custom.ini is renamed as part of default install
- updated install instructions

### Changed

- Updated all hardcoded icons on the site to use Material Icons instead.
- Device type icons use Material Icon syntax as well

### Removed

- removed Font Awesome icon library 

## 2.0 

### Added

- added Manufacturer as a computer attribute
- added Device Types with computer being one of several possible
- Device Types can set attributes that are allowed for that type
- set attributes as viewable and editable based on device type
- allow DeviceTypes to exclude themselves from AD comparisons
- added 802.1x supplicant information to allowed device attributes
- main page now includes pagination (50 devices at a time)
- Allow setting the device type as part of the powershell updater script, default is "computer". 
- Main inventory listing page now remembers filtering between page refreshes
- can upload a CSV file to bulk import devices instead of manually adding one at a time
- manually edited devices can also keep a user history
- description on license page when empty
- added visual to settings page to see if settings are in fact encrypted or not

### Changed

- the ability to decommission or check the running status of a device is controlled by the Device Type
- device type must be set when adding device via API
- only allowed attribute types for a device are allowed when setting them via the API
- Updated UI elements to read "device" instead of "computer" in most cases. Alternatively the actual device type name is used as well. 
- updated the Powershell updater script to send a device type of "computer" - this is hardcoded for now
- "search" is now "filter" in inventory listings, avoid confusion in terminology
- Asset ID is no longer required for any device - the only required fields are defined in the `AppController`, they are the Name, Location, and Device Type.
- Ping command uses IP address rather than hostname, more reliable as long as IP is current

### Fixed

- merged attribute listings on various pages to pull from one variable
- don't return associations for `/api/locations` api calls, these are unnessary
- fixed confirmation dialog prompt Title attribute
- minor PHP warning messages
- made sure to set header to `application/json` to API responses
- don't allow duplicate device (computer) names by adding constraints to the database and application checks. 
- modified Logs page so multi string Device Names link correctly

### Removed 

- removed the Fields To Display setting from the settings area. This was no longer needed when each device type can specify it's own attributes. 
- removed Shutdown/Restart commands. These required computer admin privledges and were just generally unuseful. Wake On Lan remains

## 1.6

### Added

- added ```Settings.encrypt``` value to the ```custom.ini``` file layout. This controls if settings encryption is used or not
- added an error message when incorrect authentication type is set at login
- added [AuthenticationReset](https://github.com/eau-claire-energy-cooperative/simple-inventory/wiki/Recovering-Login) command line option to regain access on lost logins
- added module and log level arguments to AppShell->dblog() function
- added documentation and code links to the login page

### Fixed

- fixed issue with settings encryption. this can now be toggled via a true/false value. Also included better instructions on this
- fixed warning where the AppShell->log() function conflicted with the CakePHP default. Renamed to dblog()
- fixed issue with deleting a computer, method was tied to a POST request even though button was issuing a GET request. 

## 1.5

### Added

- added the ability to set a license as "unassigned" to a specific computer. This way the key can be saved if deploying later. 
- added ```custom.ini.default``` file and moved the encryption_key setting to this file, loaded at runtime. Allows for custom setting of encryption key
- added put current version number in footer

### Changed

- made the decommission date of a computer more prominent on the listing screen and the detail screen
- updated the look of the decommission process screen, including the error message listing

## 1.4

### Fixed

- fixed error with /api/add_log function where the Security class wasn't found. Added import to main AppController class

## 1.3

### Added

- encrypt all settings at rest in the database. Use the ```app/Config/bootstrap.php``` file to set the Settings.encryption_key value 
- added link to documentation GitHub in the footer

### Changed

- refer to Computer Detail pages consistently, some places called it Computer Info
- minor costmetic changes 

## 1.2

### Added

- added support for the update script sending the ipv6 address found on the main connected interface. Can be togged on/off in the settings as with the other fields. 
- added an API authentication key setting and support for it in the updater script and API controller flows. This stops random operations from being done on the API without some kind of authentication first. 

### Changed

- updated the UI to use a new theme based on Bootstrap and JQuery. The menu system and overall look have been cleaned, updated, and made consistant overall.

## 1.1

### Added

- added a "DNS Search Domain" field to the settings. Computer names will have this added to create a fully qualified domain name for "is alive" ping requests

## 1.0

### Added

- started Changelog for this project. For historical changes see the [commit history](https://github.com/eau-claire-energy-cooperative/simple-inventory/commits/master). Using 1.0 as version since this is a mature project with no tagged versions prior. 
- added auth check to AjaxController, these should check authentication the same as any other page

### Changed

- moved Licenses, Programs, and Scheduled Tasks into their own controller, away from the Admin controller

### Removed

- removed now-defunct Dashboard and all files needed with it
