<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Optimize MYSQL</title>
</head>

<body>
<?php
echo '<pre>' . "\n\n";
set_time_limit( 100 );

$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;

//Connection variables :
$h = 'localhost';
$u = 'root';
$p = 'v1olet';

$dummy_db = 'rcpl_cms';//The php->mysql API needs to connect to a database even when executing scripts like this. If you got an error from this(permissions), just replace this with the name of your database

$db_link = mysql_connect($h,$u,$p);

$res = mysql_db_query($dummy_db, 'SHOW DATABASES', $db_link) or die('Could not connect: ' . mysql_error());
echo 'Found '. mysql_num_rows( $res ) . ' databases' . "\n";
$dbs = array();
while ( $rec = mysql_fetch_array($res) )
{
$dbs [] = $rec [0];
}

foreach ( $dbs as $db_name )
{
echo "Database : $db_name \n\n";
$res = mysql_db_query($dummy_db, "SHOW TABLE STATUS FROM `" . $db_name . "`", $db_link) or die('Query : ' . mysql_error());
$to_optimize = array();
while ( $rec = mysql_fetch_array($res) )
{
if ( $rec['Data_free'] > 0 )
{
$to_optimize [] = $rec['Name'];
echo $rec['Name'] . ' needs optimization' . "\n";
}
}
if ( count ( $to_optimize ) > 0 )
{
foreach ( $to_optimize as $tbl )
{
mysql_db_query($db_name, "OPTIMIZE TABLE `" . $tbl ."`", $db_link );
}
}
}

$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 6);
echo 'Parsed in ' . $total_time . ' secs' . "\n\n";
?>



</body>
</html>
