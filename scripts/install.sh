COPYDIR=$1

#install the site
sudo ./update_site.sh $COPYDIR

cp $COPYDIR/app/Config/custom.ini.default $COPYDIR/app/Config/custom.ini

crontab -l; echo "* * * * * $COPYDIR/app/Console/cake -app $COPYDIR/app Scheduler > /dev/null" | sort - | uniq - | crontab -

