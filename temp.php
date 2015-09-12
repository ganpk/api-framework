<?php
//$initMemory = memory_get_usage();
//$arr = range(0,100000);
//echo memory_get_usage() - $initMemory;
//echo PHP_EOL;

//test($arr);
//$arr2 = $arr;
//$arr2[0] = 2;
//echo memory_get_usage() - $initMemory;
//echo PHP_EOL;

$initMemory = memory_get_usage();
$t = new t();
echo memory_get_usage() - $initMemory;
echo PHP_EOL;

var_dump($t->a[0]);

$t2 = $t;
$t2->a[0] = 1;
var_dump($t->a[0]);
echo memory_get_usage() - $initMemory;
echo PHP_EOL;

function test($t) {
    global $initMemory;
//    $arr[1] = 2;
    echo memory_get_usage() - $initMemory;
    echo PHP_EOL;
}

class t {
    public $a = array();
    public function __construct()
    {
        $this->a = range(0,100000);
    }
}
