<?php
if ( ! is_admin() ) { die( 'Access denied' ); }

pb_backupbuddy::$ui->title( __( 'BackupBuddy Live', 'it-l10n-backupbuddy' ) );
?>

<style>
	.metabox-holder .postbox {
		float: left;
		width: 30%;
		min-width: 200px;
		margin-right: 20px;
		//box-sizing: border-box:
	}
	.metabox-holder .postbox h3 {
		display: block;
		font-weight: 800;
	}
</style>


<p>
	BackupBuddy live continuously backs up database and media updates as they occur.  Additional full database snapshots and file modification scans are performed daily.
</p>

<div class="backupbuddy-live-stats metabox-holder">
	<div class="postbox">
		<h3 class="hndle"><span>At a Glance</span></h3>
		<div class="inside">
			<ul>
				<li><b>Status:</b> OK</li>
				<li><b>Backed up:</b> 75% (2,154 files & 15 database tables)</li>
				<li><b>Next action:</b> Check file signatures &nbsp; <span class="description">Runs: Sep 12th, 2:30pm</span></li>
			</ul>
		</div>
	</div>


	<div class="postbox">
		<h3 class="hndle"><span>Database</span></h3>
		<div class="inside">
			<ul>
				<li><b>Status:</b> OK</li>
				<li><b>Live backup:</b> Enabled</li>
				<li><b>Last snapshot:</b> Sep 9th, 1:31am &nbsp; <span class="description">4 hours ago</span></li>
			</ul>
		</div>
	</div>


	<div class="postbox">
		<h3 class="hndle"><span>Files</span></h3>
		<div class="inside">
			<ul>
				<li><b>Status:</b> OK</li>
				<li><b>Last scan:</b> Sep 9th, 1:31am &nbsp; <span class="description">4 hours ago</span></li>
				<li><b>Pending uploads:</b> 34 files &nbsp; [<a href="javascript:void(0);">view</a>]</li>
			</ul>
		</div>
	</div>
</div>

<br style="clear: both;"><br>
