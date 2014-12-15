#Simple Computer Inventory

* **Authors**: afaber and robweber

##What is it? 

This is a simple to deploy and use system for keeping track of a Windows based PC inventory for a small business. Businesses with a small IT department often resort to using spreadsheets or other cumbersome methods to keep track of their computer inventory. This project removes some of that hassle by allowing each computer in the building to report information about itself, through the use of a common database and quick running login script. The result is that your computer inventory is always up to date without the hassle of remembering to note entries in a manual system. 

##Why? 

I know, other more comprehensive systems to do this already exist. The problem is they are a little too comprehensive. Small IT groups don't have the time or the resources to dedicate to learning another full-fledged inventory, monitoring, ticket tracking, polish your shoes, make your dinner type product. This is meant to be a 1,2,3 done type of project that will yield results quickly. 

##How does it work? 

The inventory system is two different pieces working together. The first is a small Java program that is configured to be run as part of a user's login process on a Windows based machine. Using Active Directory or some type of Group Policy a batch file is run in the background when a user logs in. This batch file kick starts the java process that will collect information about the client PC and send it back to the common database via a REST api.

The second system is a CakePHP based administrative web page that allows you to view and categorize the information sent back. Each computer in the system is available to be viewed. Identifiying attributes such as notes, asset id tags, and locations can also be defined to allow an easy way to tie the PC inventory back into an asset management or departmental type system. 

##How Can I Get It? 

Right now, not quite as easy as I've made it out to be. All of the needed file are committed and available via this repository; however they don't exist in a way that is very easy to use without setting up a lot of infrastructure first. The PHP components require a WAMP or LAMP stack to be fully useable, there is a deps.list document in the repo for any specific dependencies you'll need. Some Ant scripts are also available to deploy the source and java components.  

In time we hope to automate this process through the use of some scripts and perhaps even a ready to deploy Virtual Appliance. Another easy way to get a WAMP stack up and running is to check out the Uniform Server project. 

