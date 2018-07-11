#!/bin/bash
echo "start inotify at $(date)"
inotifywait -mrq --event delete,modify,create /home/zhushuai/odp_loc|while read path action file
do
    /usr/bin/php ./syncfile.php $path $file $action
done
