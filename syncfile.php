<?php
$user = 'zhushuai';
$host = '10.188.40.15';
if (empty($argv[1]) || empty($argv[2]) || empty($argv[3])){
    echo "No file param received";
    return;
}
$path = $argv[1];
$file = $argv[2];
$action = $argv[3];

echo PHP_EOL;
echo '--'.getTime().' '.$path.' '.$file.' '.$action.PHP_EOL;
$hasJb = explode('___jb', $file);
if (count($hasJb) > 1) {
    if ($action != 'DELETE'){
        echo 'skipping non delete tmp file operation'.PHP_EOL;
        return;
    }
}
$fullFile = $path.$hasJb[0];
if (filter($fullFile) !== false){
    sync($fullFile, $user,$host);
} else {
    echo "skipping filtered file ".$fullFile.PHP_EOL;
}

function filter($file){
    $keywordExclude =['.git', '.idea', '.swp', '.swo', '.log'];
        foreach($keywordExclude as $keyword){
        if (strpos($file, $keyword) !== false){
            return false;
        }
        }
    return $file;
}

function sync($file, $user, $host){
    if (!file_exists($file)){
        echo 'skipping non exist file '.$file.PHP_EOL;
        return;
    }
    if (!is_file($file)){
        echo 'skipping not file param '.$file.PHP_EOL;
        return;
    }
    echo '+++syncing '.$file.PHP_EOL;
    $dirName = dirname($file);
    $commandMkdir = sprintf("ssh %s@%s 'mkdir %s'", $user, $host, $dirName);
    shell_exec($commandMkdir);
    $commandScp = sprintf('scp %s %s@%s:%s', $file, $user, $host, $file);
    shell_exec($commandScp);
}

function getTime()
{
    $s = explode(' ', microtime());
        return date('Y-m-d H:i:s').$s[0];
}
