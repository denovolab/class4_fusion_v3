#!/usr/bin/env sh

cat filemode | xargs chmod 777 -R 
rm -rf app/tmp/cache/cake_*
chmod -R 777 db_nfs_path
