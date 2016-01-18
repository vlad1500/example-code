<?php
echo "running deployment script...\n";
//echo svn_update('/storage/www/codebase/apps/devhardcover/');


//echo "\nrunning\n";

/*
svn_auth_set_parameter(SVN_AUTH_PARAM_DEFAULT_USERNAME, 'dennis');
svn_auth_set_parameter(SVN_AUTH_PARAM_DEFAULT_PASSWORD, 'tTC+9=3Hkqy4');
$b4 = trim(`svnversion`);
if ($revision = svn_update(realpath(getcwd()))) { //use @svn_update(...) if you want to hide warnings
    if ($b4 === trim(`svnversion`)) {
        echo 'Already up-to-date (r'.$b4.').';
    } else {
        echo 'Updated to r'.$revision;
    }
} else {
    echo 'Update failed';
}
*/

echo shell_exec('2>&1 svn cleanup /storage/www/codebase/apps/devhardcover');
echo shell_exec('2>&1 svn up /storage/www/codebase/apps/devhardcover --config-dir /home/dennis/.subversion');

?>
