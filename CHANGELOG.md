# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)

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