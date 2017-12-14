<?php
backupbuddy_core::verifyAjaxAccess();


// Tests ACTUAL PHP maximum runtime.
/*	php_max_runtime_test()
*	
*	Tests the ACTUAL PHP maximum runtime of the server by echoing and logging to the status log the seconds elapsed.
*	
*	@param		int		$stop_time_limit		Time after which the test will stop if it is still running.
*	@return		null
*/


$stop_time_limit = 240;
pb_backupbuddy::set_greedy_script_limits(); // Crank it up for the test!

$m = "# Starting BackupBuddy PHP Max Execution Time Tester. Determines what your ACTUAL limit is (usually shorter than the server reports so now you can find out the truth!). Stopping test if it gets to `{$stop_time_limit}` seconds. When your browser stops loading this page then the script has most likely timed out at your actual PHP limit.";
pb_backupbuddy::status( 'details', $m );
echo $m . "<br>\n";

$t = 0; // Time = 0;
while( $t < $stop_time_limit ) {
	
	pb_backupbuddy::status( 'details', 'Max PHP Execution Time Test status: ' . $t );
	echo $t . "<br>\n";
	//sleep( 1 );
	$now = time(); while ( time() < ( $now + 1 ) ) { true; }
	flush();
	$t++;
	
}

$m = '# Ending BackupBuddy PHP Max Execution Time The test was stopped as the test time limit of ' . $stop_time_limit . ' seconds.';
pb_backupbuddy::status( 'details', $m );
echo $m . "<br>\n";
die();
