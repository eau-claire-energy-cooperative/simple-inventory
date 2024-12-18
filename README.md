# Simple Inventory

**Authors**: afaber and robweber

## What is it? 

This is a simple to deploy and use system for keeping track of network assets in a small business. Businesses with a small IT department often resort to using spreadsheets or other cumbersome methods to keep track of their hardware assets. Primarily these are workstations but could be phones, copiers, or printers. This project removes some of that hassle by allowing Windows based computers to report information about themselves, through the use of a common database and a quick running login script. The result is that your workstation inventory is always up to date without the hassle of remembering to note entries in a manual system. Other device types can be easily added as needed. 

![alt text](https://github.com/eau-claire-energy-cooperative/simple-inventory/raw/master/screenshots/Detail_Screen.PNG "Detail Screen")

For more pictures see the [screenshots folder](https://github.com/eau-claire-energy-cooperative/simple-inventory/tree/master/screenshots). 

## Why? 

I know, other more comprehensive systems to do this already exist. The problem is they are a little too comprehensive. Small IT groups don't have the time or the resources to dedicate to learning another full-fledged inventory, monitoring, ticket tracking, polish your shoes, make your dinner type product. This is meant to be a 1,2,3 done type of project that will yield results quickly. 

## How does it work? 

The inventory system is two different pieces working together. The first is a PHP based administrative web site that allows you to define various device types that need to be kept track of. Identifying attributes such as notes, asset id tags, and locations can also be defined to allow an easy way to tie the device inventory back to users or accounting systems. Additional features like [tracking software](https://github.com/eau-claire-energy-cooperative/simple-inventory/wiki/Software) or [device checkouts](https://github.com/eau-claire-energy-cooperative/simple-inventory/wiki/Device-Checkout) are also available if needed. 

The second piece is a Powershell program that is configured to be run as part of a user's login process on a Windows based machine. Using Active Directory, or some type of Group Policy, the script is run in the background when a user logs in. This script will collect information about the client PC and send it back to the common database via a REST API.

## How Can I Get It? 


Having a LAMP (Linux, Apache, MySQL, PHP) stack in place is a prerequisite to installing this system. Details for how to do this aren't going to be listed in great detail but a working knowledge of getting the web server, database, and PHP up and running are necessary. Various tutorials on how to do this already exist. 

Once you have that working take a look at the ```INSTALL.md``` file for more specific information. There are some additional PHP and PEAR libraries that need to be installed as well. 


## Key Features

* Easy to setup Powershell script for PC information collection 
* Intuitive web interface that can filter devices quickly to find what you're looking for
* Ability to generate emails on new computer additions
* Decommission feature to remove devices but keep information
* Can sync with Active Directory to find missing computers
* Non-PC devices can be created and managed as well
* Optionally can collect applications via PowerShell or manually assign them to devices
* Software lifecycles can be assigned to specific applications for reminders and update instruction documentation
* Equipment check out feature can be enabled to track devices that are checked out for special purposes
* Track licenses and license keys attached to devices. 
* Optionally can list services and their running state
* Optionally can integrate with [Chocolatey](https://chocolatey.org/) to find outdated packages
* Can export most information to a CSV for offline work


# Attributions

* Framework: [CakePHP](https://cakephp.org/)
* Web Theme: [SB Admin 2](https://startbootstrap.com/themes/sb-admin-2/)
* Icons: [Material Design Icons](https://materialdesignicons.com/)
* Markdown Generator: [Parsedown](https://github.com/erusev/parsedown)
