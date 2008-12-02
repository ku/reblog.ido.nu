
<?php

require_once 'Net/Gearman/Client.php';

function result($resp) {
	$t = func_get_args();
	print "response: ";
    print_r($t);
}

$posts = array ( 24160934, 24060934, 240934);
foreach ($posts as $post) {
	$set = new Net_Gearman_Set();
    $task = new Net_Gearman_Task(
		#'reblog', array( $email, $passwd, $post),
		'sum', array( 1,2,3,4 ),
		'uniqidOfThisJob',  Net_Gearman_Task::JOB_BACKGROUND
	);
	//$task->attachCallback('result');
	$set->addTask($task);
}

$client = new Net_Gearman_Client(array('localhost:37003'));
$client->runSet($set);

?>

