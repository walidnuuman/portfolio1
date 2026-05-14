<?php
$content = <<<EOT
# Virtual Hosts
#
# Required modules: mod_log_config

# Use name-based virtual hosting.
##NameVirtualHost *:80

# This is the DEFAULT virtual host for localhost
<VirtualHost *:80>
    ServerName localhost
    DocumentRoot "C:/xampp/htdocs"
</VirtualHost>

# Custom Expense Tracker VirtualHost
<VirtualHost *:80>
    ServerName exptracker.local
    DocumentRoot "C:/projects/expence tracker ii/Expense-Tracker-main/expt_lvl/public"
    
    <Directory "C:/projects/expence tracker ii/Expense-Tracker-main/expt_lvl/public">
        Options Indexes FollowSymLinks MultiViews
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>

EOT;
file_put_contents('C:/xampp/apache/conf/extra/httpd-vhosts.conf', $content);
?>
