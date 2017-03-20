#!/usr/bin/env sh
git push origin master
ssh root@chat.ocd.community 'cd /var/www/vhosts/chat.ocd.community; git pull origin master'
