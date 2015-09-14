echo 'shutdown...'
pid=$(pidof swoole_manager_item)
kill -15 "$pid"
echo 'done'
