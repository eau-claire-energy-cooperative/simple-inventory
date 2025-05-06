# Simple Inventory

![alt text](https://github.com/eau-claire-energy-cooperative/simple-inventory/raw/master/screenshots/Detail_Screen.PNG "Detail Screen")

[![License](https://img.shields.io/github/license/eau-claire-energy-cooperative/simple-inventory)](https://github.com/eau-claire-energy-cooperative/simple-inventory/blob/master/LICENSE)
[![standard-readme compliant](https://img.shields.io/badge/readme%20style-standard-brightgreen.svg)](https://github.com/RichardLitt/standard-readme)

This is a simple to deploy and use system for keeping track of assets in a small business. Businesses with a small IT department often resort to using spreadsheets, or other cumbersome method, to keep track of their hardware assets. Primarily these are workstations but could be phones, copiers, or printers. This project allows you to define device types and track their attributes easily. Additionally licenses and installed software can be attached to the device as well. Through a REST API Windows based computers can report information about themselves with the included Powershell script. The result is that your workstation inventory is always up to date without the hassle of remembering to note entries in a manual system.

For more pictures see the [screenshots folder](https://github.com/eau-claire-energy-cooperative/simple-inventory/tree/master/screenshots). 

## Background

So why does this exist? Obviously other, more comprehensive, systems are available to do this already exist. The problem is they are a little too comprehensive. Small IT deptarments don't have the time or the resources to dedicate to learning another full-fledged inventory, monitoring, ticket tracking, polish your shoes, make your dinner type product. This is meant to be a 1,2,3 done type of project that will yield results quickly. It doesn't need an agent and doesn't try and stack a bunch of other features into the system that make it hard to use. 

The inventory system is two different pieces working together. The main component is PHP based web site that allows you to define various device types that need to be kept track of. Identifying attributes such as notes, asset id tags, and locations can also be set up to allow an easy way to tie the device inventory back to users or accounting systems. Additional features like [tracking software](https://github.com/eau-claire-energy-cooperative/simple-inventory/wiki/Software) or [device checkouts](https://github.com/eau-claire-energy-cooperative/simple-inventory/wiki/Device-Checkout) are available if needed. 

The other component is a [REST API](https://github.com/eau-claire-energy-cooperative/simple-inventory/wiki/API). The API allows automatic enrollment and setup of devices. Included is a Powershell script that can be configured to run as part of a user's login process on Windows machines. Using Active Directory, or some type of Group Policy, the script runs in the background and updates information about the device. 

## Install


Having a LAMP (Linux, Apache, MySQL, PHP) stack in place is a prerequisite to installing this system. Details for how to do this aren't going to be listed in great detail but a working knowledge of getting the web server, database, and PHP up and running are necessary. Various tutorials on how to do this already exist. 

Once you have that working take a look at the [INSTALL.md](https://github.com/eau-claire-energy-cooperative/simple-inventory/blob/master/INSTALL.md) file for more specific information.


## Usage

Below are a list of key features. Detailed instructions for setting up the various components can be found on the [Wiki](https://github.com/eau-claire-energy-cooperative/simple-inventory/wiki). 

### Key Features

* Easy to setup Powershell script for Windows PC information collection 
* Intuitive web interface that can filter devices quickly to find what you're looking for
* Ability to generate emails on new computer additions
* Decommission feature to remove devices from production but keep information
* Can sync with Active Directory to find missing computers
* Non-PC devices types can be created and managed as well
* Optionally can collect applications via PowerShell or manually assign them to devices
* Software lifecycles can be assigned to specific applications for reminders and update instruction documentation
* Equipment check out feature can be enabled to track devices that are checked out for special purposes
* Track licenses and license keys attached to devices. 
* Setup reminders for license expiration
* Optionally can list services and their running state
* Can export most information to a CSV for offline work

## Maintainers

* [@robweber](https://github.com/robweber)

## Thanks

Thanks to Andrew Faber for developing the first iteration of the web interface. 

The following projects and frameworks are utilized within this project (not an exhaustive list):

* Framework: [CakePHP](https://cakephp.org/)
* Web Theme: [SB Admin 2](https://startbootstrap.com/themes/sb-admin-2/)
* Icons: [Material Design Icons](https://materialdesignicons.com/)
* Markdown Generator: [Commonmark](https://github.com/thephpleague/commonmark)
* Cron Expression Parser: [cron-expression](https://github.com/dragonmantank/cron-expression)

## License

[GPLv3](/LICENSE)


