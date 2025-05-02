Instructions for how to install the various components (assumes Debian type system). This guide assumes you have an LAMP (Linux, Apache, MariaDB, PHP) system setup and available.

## Install Prerequisites

Clone the repository and setup dependencies.

```
sudo apt-get install -y php-intl php-ldap php-soap php-mysql php-xml

git clone https://github.com/eau-claire-energy-cooperative/simple-inventory.git

cd simple-inventory/www

# install Composer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'.PHP_EOL; } else { echo 'Installer corrupt'.PHP_EOL; unlink('composer-setup.php'); exit(1); }"
php composer-setup.php
php -r "unlink('composer-setup.php');"

# build the website
php composer.phar update

# set permissions
cd ../
sudo chown -R your_user:www-data www

```

## Database setup

Install the Inventory default database schema. This assumes you have a database setup called `inventory` along with a database user `inventory_user`. Adjust based on your setup.

```

mysql -u inventory_user -p inventory < config/inventory_schema.sql

```

## Deploy Website

### Apache 2

Point Apache to the directory containing the Inventory site. There are multiple ways to set this up but a sample file is located in the `config` directory. Copy this to the `/etc/apache2/sites-enabled` folder. Be sure to edit the paths. Restart Apache.

### Configure Website

Two files are needed to setup the configuration and database information.

```
cd www/
cp config/.env.example config/.env
cp config/app_local.example.php config/app_local.php
```

Edit the `.env` file. Set the security salt and timezone information. Timezone codes can be found on the PHP site: https://www.php.net/manual/en/timezones.php

Edit the `app_local.php` file. Set the database host, name, username, and password. Save the file.

### Scheduled Tasks

To use the Scheduled Tasks feature of the admin panel a local process must be configured to run the SchedulerShell via the CakePHP CLI. This can be done using your local cron daemon. An example of the entry could be:

```
# run the scheduler every minute to check if a task needs to be run
* * * * * /path/to/inventory/bin/cake scheduler > /dev/null

```

If you encounter issues with this make sure the `cake` file has execute permissions and that the user you're running the command with is able to read/write files in the directory.

### Logging In

Log in to the site by going to the URL created (http://localhost/inventory if on same machine) and use the default username of __admin__ and the password of __pass__.

### Additional Settings

Check the [settings page of the wiki](https://github.com/eau-claire-energy-cooperative/simple-inventory/wiki/Settings) for more information on customizing your setup and instructions for encrypting the settings values

## Powershell

The update_inventory.ps1 script actually pulls the information from the computer and sends it to the inventory site via a REST API. The updater should run at login for each computer. This can be most easily accomplished via a [Group Policy login script](https://github.com/eau-claire-energy-cooperative/simple-inventory/wiki/Updater-Script-via-Group-Policy) or just adding a call to the existing login script for users. The updater script needs to be somewhere on your network it can be called by all computers.

At default the script takes 2 parameters; the URL of the inventory site and the API authentication key. The authentication key is set on the settings pages and is "pass" by default (please change it!). There are other parameters to turn off various functions if you don't need them. See the script itself for more details.

```

inventory_updater.ps1 -Url http://localhost/inventory -ApiAuthKey key

```
