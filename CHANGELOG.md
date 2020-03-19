# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)

## 1.2

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