<?php
if (PHP_SAPI == 'apache2handler') {
   header("Content-Type: text/plain");
   header("HTTP/1.1 403 Forbidden");
   echo "BUGKILLER MAINTENANCE\n\nPlease run maintenance scripts from your server's command-line shell, or by launching a PHP debug server on updateFromCliServer.php.\nContact your administrator if you do not have access.";
   exit;
}
if (PHP_SAPI != 'cli' and PHP_SAPI != 'cli-server') {
   $runtype = PHP_SAPI;
   echo "Run this script via the command-line shell. If you do not have access to your server's shell contact your server administrator. Detected PHP_SAPI: $runtype\n";
   exit;
}
if (posix_getuid() !== 0 and PHP_SAPI == 'cli-server') {
   echo "Please run the PHP server as root.\n";
   exit;
}
if (posix_getuid() !== 0) {
   echo "Please run this script as root.\n";
   exit;
}
if (PHP_SAPI == 'cli-server') {
   require "updateFromCliServer.php";
   exit;
}
echo "Bugkiller Server Updater\n";
echo "Attempting to update packages...\n";
chdir(dirname(__DIR__));
shell_exec("COMPOSER_ALLOW_SUPERUSER=1 composer update --no-dev");
echo "Pulling git changes...\n";
shell_exec("git reset");
shell_exec("git pull --no-rebase");
chdir("maintenance");
echo "Update finished!\n";
