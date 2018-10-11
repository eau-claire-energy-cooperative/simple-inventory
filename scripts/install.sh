COPYDIR=$1

#install the site
sudo ./update_site.sh $COPYDIR

crontab -l; echo "* * * * * $COPYDIR/app/Console/cake -app $COPYDIR/app Scheduler > /dev/null" | sort - | uniq - | crontab -

