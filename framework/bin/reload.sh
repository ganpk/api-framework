echo 'Reloading...'
pid=$(pidof swoole_manager_item)
kill -USR1 "$pid"
echo 'Reloaded'
