<?php
// Just some testing
$dbPath = __DIR__ . '/database/database.sqlite';

if (! file_exists($dbPath)) {
    $conn = new SQLite3($dbPath);
    $initSql = file_get_contents(__DIR__ . '/database/init.sql');
    if (! $conn->query($initSql) ) {
        throw new \Exception("Error initializing databse.");
    }
} else {
    $conn = new SQLite3($dbPath);
}

$data = json_encode(['one' => 123, 'two' => 342]);
$conn->query("INSERT into samples values (null, '{$data}', '2017-06-13 10:31');");

$rs = $conn->query("select * from samples where created_at > '2017-06-13 10:30';");

while($row = $rs->fetchArray()) {
    var_dump($row);
}
