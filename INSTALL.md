Instructions for how to install the various components (assumes Debian type system). 

## Web Site

### PHP7

Install default from from Linux package manager, also install the following modules. 

```

sudo apt-get install -y php7.0-ldap php7.0-mcrypt php7.0-mysql php7.0-xml

```

### Apache 2

Install default from Linux package manager. Move the files from the www/ directory to the folder where Apache will load the site from. This can be done easily with the provided install script. 

```

sudo ./install.sh /path/to/www/directory

```

### Pear 

Install with:

```

wget http://pear.php.net/go-pear.phar
sudo php go-pear.phar

```

Add the following to the apache2/php.ini file

```

include_path=".:/usr/share/pear"

```

Install the Net Module   

```

sudo pear install Pear/Net_Ping

```

### Security 

In the ```app/Config/bootstrap.php``` file set the Settings.encryption_key value to a different value. This is what encrypts settings values in the database. 

### Mysql

Install default from Linux Package Manager. Create a DB user and edit the app/Config.database.php file with the correct login information. 

Run the CakePHP Schema manager with

```

app/Console/cake schema create

```

Updating an existing install with DB changes can be done with

```

app/Console/cake schema update

```

### Scheduled Tasks

To use the Scheduled Tasks feature of the admin panel a local process must be configured to run the SchedulerShell via the CakePHP CLI. This can be done using your local cron daemon. An example of the entry could be: 

```
# run the scheduler every minute to check if a task needs to be run
* * * * * /path/to/inventory/app/Console/cake -app /path/to/inventory/app Scheduler > /dev/null

```

If you encounter issues with this make sure the ```cake``` file has execute permissions and that the user you're running the command with is able to read/write files in the directory. 

### Logging In

Log in to the site by going to the URL created (http://localhost/inventory if on same machine) and use the default username of __test__ and the password of __pass__.

## Powershell 

The update_inventory.ps1 script actually pulls the information from the computer and sends it to the inventory site via a REST API. The updater should run at login for each computer. This can be most easily accomplished via a Group Policy login script or just adding a call to the existing login script for users. The updater script needs to be somewhere on your network it can be called by all computers. 

At default the script takes 2 parameters; the URL of the inventory site and the API authentication key. The authentication key is set on the settings pages and is "pass" by default (please change it!). There are other parameters to turn off various functions if you don't need them. See the script itself for more details. 

```

inventory_updater.ps1 -Url http://localhost/inventory -ApiAuthKey key

```