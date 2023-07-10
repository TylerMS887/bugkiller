<?php
if (PHP_SAPI !== 'cli-server') {
  echo "This script cannot be run unless a PHP development server is available.";
  exit;
}
if (posix_getuid() !== 0) {
   echo "Please run the development server as root.\n";
   exit;
}
header("HTTP/1.1 200 OK");
echo "<h1>Bugkiller Web Upgrade</h1>";
echo "<p>You are upgrading Bugkiller from an external server. Please check your command-line shell for prompts.</p>";
chdir(dirname(__DIR__));
echo "<h3>Update packages</h3><pre><code>";
echo nl2br(shell_exec("composer update --no-dev"));
echo "</code></pre>";
echo "<h3>Update from Git</h3>";
echo "<pre><code>";
echo nl2br(shell_exec("git reset"));
echo "\n";
echo nl2br(shell_exec("git pull"));
echo "</code></pre>";
echo "<h3>Restart Apache</h3>";
shell_exec("apachectl restart");
echo "The update has been completed.";
