#!/bin/bash
#监控服务是否启动，如果主进程已挂掉，则kill掉所有残留的子进程，然后重新启动Server
count=`ps -fe |grep "api_server.php" | grep -v "grep" | grep "master" | wc -l`

echo $count
if [ $count -lt 1 ]; then
ps -eaf |grep "api_server.php" | grep -v "grep"| awk '{print $2}'|xargs kill -9
sleep 2
ulimit -c unlimited
/usr/local/php/bin/php /www/api-frameworke/api_server.php
echo "restart";
echo $(date +%Y-%m-%d_%H:%M:%S) >/data/log/restart.log
fi