# RTIS_GUI

Installing the prerequisites: 
sudo apt-get install lamp-server^

Now, add the following in /etc/apache2/apache2.conf :
<Directory /path/to/website>
        Options Indexes FollowSymLinks Includes ExecCGI
        AllowOverride All 
        Order deny,allow
        Allow from all
        Require all granted
</Directory>



Change the directory in /etc/apache"2/sites-available/000-default.conf to match where the files are.


Access to the PHP logs with :
tail -f /var/log/apache2/error.log
