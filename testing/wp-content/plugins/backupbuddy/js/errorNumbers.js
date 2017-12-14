/*
@since 5.0

1xxx	BackupBuddy core.
2xxx	Backup startup, pre-backup, etc.
3xxx	Database related.
4xxx	Zip related.
5xxx	
6xxx	
7xxx	
8xxx	Destination related (remote destinations).
9xxx	Specifically defined errors prior to BackupBuddy 5.0.
10000+	Unspecified error number. Randomly generated for uniqueness for tracking down unexpected error situations.

*/

function getErrorInfo( errorNumber ) {
	
	if ( '9010' == errorNumber ) {
		return {
			title: '',
			description: '',
			tips: ''
		};
	}
	
	return false; // No help found for this number.
} // end function