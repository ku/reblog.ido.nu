<?php

require_once 'Net/Gearman/Client.php';

$email = $_REQUEST['email'];
$password = $_REQUEST['password'];
$postid = $_REQUEST['postid'];

if ( $email == '' or $password == '' or $postid == '' ) {
	print 0;
	exit;
}


$pid = getmypid();

{
	$set = new Net_Gearman_Set();
    $task = new Net_Gearman_Task(
		'reblog', array(
			'email' => $email,
			'password' => $password,
			'postid' => $postid
		),
		"$pid-$postid-$email",  Net_Gearman_Task::JOB_BACKGROUND
	);
	#$task->attachCallback('result');
	$set->addTask($task);
}

$client = new Net_Gearman_Client(array('localhost:37003'));
$client->runSet($set);


print 1;
?>
