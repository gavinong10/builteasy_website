<?php
/**
 *	pluginbuddy_zbdir Class
 *
 *  Provides a directory class for zipbuddy for building a directory tree for backup
 *	
 *	Version: 1.0.0
 *	Author:
 *	Author URI:
 *
 */
if ( !class_exists( "pluginbuddy_zbdir" ) ) {

	/**
	 *	pluginbuddy_zbdir_xclusion Class
	 *
	 *  Abstract class for handling file/directory exclusions or inclusions
	 *	
	 *	All xclusions must be either all relative to the same root path or all
	 *	absolute paths. 
	 *	All xclusions must have normalized directory separators
	 *	All xclusions must start with / if relative to a root path
	 *	All dir xclusions must terminate in / (normalized directory separator)
	 *	All xclusions must be at least one character long
	 *	All pattern exclusions must be value PCRE syntax
	 *
	 *	If xclusions are absolute paths then the specific form will depend on the
	 *	platform but must still be normalized as noted above.
	 *
	 *	FFS: If xclusions are given as relative then a root path must also be set if any
	 *	operations requiring absolute paths are required. Such a root path may be passed
	 *	as an option at object creation or subsequently set or may be passed as an option
	 *	to any method as appropriate where it will be used in conjunction with the
	 *	relative xclusion paths. Not sure if this will be required and in any case it may
	 *	well give a high performance hit in some cases so probably better to build a
	 *	new xclusions object using the absolute paths.
	 *	
	 *	@return		null
	 *
	 */

	abstract class pluginbuddy_zbdir_xclusion {
	
		const NORM_DIRECTORY_SEPARATOR	= '/';
		const LAST_CHARACTER 			= -1;
		const ALL_XCLUSIONS 			= 'all';
		const DIR_XCLUSIONS 			= 'dir';
		const FILE_XCLUSIONS 			= 'file';
		const UNKNOWN_XCLUSIONS 		= 'unknown';
		const PATTERN_XCLUSIONS 		= 'pattern';
		
		const DELIMITER					= '!';
		const ESCAPED_DELIMITER			= '\!';
		
		protected $_files 	 			= array();
		protected $_dirs  	 			= array();
		protected $_all   	 			= array();
		protected $_patterns 			= array();
		
		protected $_options	 			= array();
		protected $_root	 			= '';
		protected $_pattern_auto_delimit = true;
		
		protected static $_default_options = array( 'root' => "",
													'pattern_auto_delimit' => true );

		/**
		 *	__construct()
		 *	
		 *	Construct the object with possible initial array of xclusions
		 *	The options may contain a "root" directory which would be the root of the
		 *	xclusion paths. For every "unknown" xclusion addition that does not have a
		 *	traling slash the root will be prepended to the xclusion and a test of whether
		 *	it is a directory made - if this returns false (either because it is not a
		 *	directory (or not one that currently exists) or because it is a file then the
		 *	xclusion will be treated as a file. The root may be empty if the xclusions are
		 *	actually absolute in which case the test on the xclusion itself would indicate
		 *	whether it was a directory or not.
		 *	
		 *  @param		array	$unknowns	(Optional) Possible set of xclusions that could be file and/or directory xclusions
		 *  @param		array	$options	(Optional) Possible name=>value options
		 *	@return		none
		 *
		 */
		public function __construct( array $unknowns = array(), array $options = array() ) {
		
			// Get our options based on defaults or passed values
			$this->_options = array_merge( self::$_default_options, $options );
			$this->_root = $this->_options[ 'root' ];
			$this->_pattern_auto_delimit = $this->_options[ 'pattern_auto_delimit' ];

			self::add( $unknowns );
		
		}
		
		/**
		 *	__destruct()
		 *	
		 *	Destroy the object - all object storage will be recoverd by default
		 *	
		 *  @param		none
		 *	@return		none
		 *
		 */
		public function __destruct() {
		
		}
	
		/**
		 *	get()
		 *	
		 *	Get required type of xclusions as array
		 *	
		 *  @param		mixed	$type	(optional) Const value that indicates the type of xclusions to get
		 *	@return		array			The requested xclusions (could be empty)
		 *
		 */
		public function get( $type = self::ALL_XCLUSIONS ) {
		
			switch ( $type ) {
				case self::DIR_XCLUSIONS:
					$xclusions = $this->_dirs;
					break;
				case self::FILE_XCLUSIONS:
					$xclusions = $this->_files;
					break;
				case self::PATTERN_XCLUSIONS:
					$xclusions = $this->_patterns;
					break;
				case self::ALL_XCLUSIONS:
				default:
					$xclusions = $this->_all;
			}

			return $xclusions;
			
		}
		
		/**
		 *	get_dir()
		 *	
		 *	Get directory type of xclusions as array
		 *	
		 *  @param		none
		 *	@return		array			The requested xclusions (could be empty)
		 *
		 */
		public function get_dir() {
		
			return self::get( self::DIR_XCLUSIONS );
		
		} 
	
		/**
		 *	get_file()
		 *	
		 *	Get file type of xclusions as array
		 *	
		 *  @param		none
		 *	@return		array			The requested xclusions (could be empty)
		 *
		 */
		public function get_file() {
		
			return self::get( self::FILE_XCLUSIONS );
		
		}
		
		/**
		 *	get_pattern()
		 *	
		 *	Get pattern type of xclusions as array
		 *	
		 *  @param		none
		 *	@return		array			The requested xclusions (could be empty)
		 *
		 */
		public function get_pattern() {
		
			return self::get( self::PATTERN_XCLUSIONS );
		
		}
		
		/**
		 *	add()
		 *	
		 *	Add an array of xlcusions to any existing xclusions
		 *	
		 *  @param		array	$xclusions	The xclusions to add - could be directory/file/pattern type
		 *	@return		object				Reference to this object
		 *
		 */
		public function add( array $xclusions = array(), $type = self::UNKNOWN_XCLUSIONS ) {
		
			switch( $type ) {
				case self::DIR_XCLUSIONS:
					$this->_dirs = array_unique( array_merge( $this->_dirs, $xclusions ) );
					break;
				case self::FILE_XCLUSIONS:
					$this->_files = array_unique( array_merge( $this->_files, $xclusions ) );
					break;
				case self::PATTERN_XCLUSIONS:
					$this->_patterns = array_unique( array_merge( $this->_patterns, $xclusions ) );
					break;
				case self::UNKNOWN_XCLUSIONS:
				default:
					foreach ( $xclusions as $xclusion ) {
			
						if ( self::NORM_DIRECTORY_SEPARATOR === substr( $xclusion, self::LAST_CHARACTER ) ) {
				
							$this->_dirs[] = $xclusion;
				
						} else {
						
							// With no trailing slash this _may_ be a file or directory exclusion so
							// we'll try and test for it being a directory and base the decision on
							// whether we can determine that or not.
							if ( @is_dir( $this->_root . $xclusion ) ) {
							
								// Tests as a directory - must add the trailing separator
								$this->_dirs[] = $xclusion . self::NORM_DIRECTORY_SEPARATOR;
							
							} else {
								
								// Either definitely a file or the test was inclonclusive because
								// perhaps we didn't haev a complete directory path to test
								$this->_files[] = $xclusion;
							
							}
							
						}
			
					}
					
					$this->_dirs = array_unique( $this->_dirs );		
					$this->_files = array_unique( $this->_files );		
			}
			
			// Always refresh the combined array whatever has been added
			// Note: All xclusions does _not_ include pattern xclusions
			$this->_all = array_unique( array_merge( $this->_dirs, $this->_files ) );
			
			return $this;

		}
		
		/**
		 *	add_file()
		 *	
		 *	Add an array of type file xlcusions to any existing file xclusions
		 *	
		 *  @param		array	$xclusions	The xclusions to add
		 *	@return		object				Reference to this object
		 *
		 */
		public function add_file( array $xclusions = array() ) {
		
			return self::add( $xclusions, self::FILE_XCLUSIONS );
		
		}
	
		/**
		 *	add_dir()
		 *	
		 *	Add an array of directory file xlcusions to any existing directory xclusions
		 *	
		 *  @param		array	$xclusions	The xclusions to add
		 *	@return		object				Reference to this object
		 *
		 */
		public function add_dir( array $xclusions = array() ) {
		
			return self::add( $xclusions, self::DIR_XCLUSIONS );
		
		}
		
		/**
		 *	add_pattern()
		 *
		 *	Add an array of type pattern xlcusions to any existing pattern xclusions
		 * 	Add delimiters if we are auto-delimiting. If an auto delimit option is given
		 * 	then will override the default, otherwise the default will be used.
		 *	
		 *  @param		array	$xclusions		The xclusions to add
		 * 	@param		bool	$auto_delimit	True to auto-delimit patterns, false to not, null to use default
		 *	@return		object					Reference to this object
		 *
		 */
		public function add_pattern( array $xclusions = array(), $auto_delimit = null ) {
			
			$add_delimiter = ( is_bool( $auto_delimit ) ) ? $auto_delimit : $this->_pattern_auto_delimit ;
		
			if ( true === $add_delimiter ) {
				
				foreach ( $xclusions as &$xclusion ) {
					
					// If our delimiter appears in the pattern we must escape it
					$xclusion = self::DELIMITER . str_replace( self::DELIMITER, self::ESCAPED_DELIMITER, $xclusion ) . self::DELIMITER;
				
				}
				
				// Not strictly necessary but just so we remember
				unset( $xclusion );
					
			}
					
			return self::add( $xclusions, self::PATTERN_XCLUSIONS );
		
		}
		
		/**
		 *	matches()
		 *	
		 *	Does the path match any member of the set
		 *	
		 *  @param		string	$path		The path to test for match to the set members
		 *  @param		string	$type		The set members to test against
		 *	@return		bool				True if matches any in set, otherwise False
		 *
		 */
		public function matches( $path = '', $type = self::ALL_XCLUSIONS ) {
		
			$result = false;
		
			// Currently only handle single path and not array of paths
			if ( is_string( $path ) ) {
			
				switch( $type ) {
					case self::DIR_XCLUSIONS:
						$candidates = &$this->_dirs;
						break;
					case self::FILE_XCLUSIONS:
						$candidates = &$this->_files;
						break;
					case self::ALL_XCLUSIONS:
					default:
						$candidates = &$this->_all;
				}
			
				$result = in_array( $path, $candidates );
				
			}
			
			return $result;
		
		}
	
		/**
		 *	matches_regex()
		 *	
		 *	Does the path match any member of the pattern set
		 * 	Patterns must already be delimited
		 *	
		 *  @param		string	$path		The path to test for match to the pattern set members
		 *	@return		bool				True if matches any in pattern set, otherwise False
		 *
		 */
		public function matches_regex( $path = '' ) {
		
			$result = false;
		
			// Currently only handle single path and not array of paths
			if ( is_string( $path ) ) {
			
				foreach ( $this->_patterns as $pattern ) {

					if ( 1 === preg_match( $pattern, $path ) ) {
						
						$result = true;
						break;
							
					} 

				}
				
			}
			
			return $result;
		
		}

		/**
		 *	prefix_of()
		 *	
		 *	Is any member of the set a prefix of the path
		 *	
		 *  @param		string	$path		The path to test the set members to be prefix of
		 *  @param		string	$type		The set members to test against
		 *	@param		bool	$exclusive	True if member in set not allowed to be exact match to path
		 *	@return		bool				True if prefixed by any in set, otherwise False
		 *
		 */
		public function prefix_of( $path, $type = self::ALL_XCLUSIONS, $exclusive = true ) {
		
			switch( $type ) {
				case self::DIR_XCLUSIONS:
					$candidates = &$this->_dirs;
					break;
				case self::FILE_XCLUSIONS:
					$candidates = &$this->_files;
					break;
				case self::ALL_XCLUSIONS:
				default:
					$candidates = &$this->_all;
			}
			
			foreach ( $candidates as $candidate ) {
			
				// The candidate _must_ be found at the start of the path (to be a true prefix of the path)
				if ( 0 === strpos( $path, $candidate ) ) {
				
					// If not wanting exclusivity then can simply return here
					if ( false == $exclusive ) {
					
						return true;
						
					}
					
					// Otherwise we must check for exclusivity
					if ( 0 <> strcmp( $path, $candidate ) ) {
				
						return true;
							
					}

				}
				
			}
			
			return false;
			
		}
		
		
		/**
		 *	prefixed_by()
		 *	
		 *	Is any member of the set prefixed by the path
		 *	
		 *  @param		string	$path		The possible prefix path
		 *  @param		string	$type		The set members to test against
		 *	@param		bool	$exclusive	True if prefix not allowed to be exact match to member in set
		 *	@return		bool				True if prefix of any in set, otherwise False
		 *
		 */
		public function prefixed_by( $path, $type = self::ALL_XCLUSIONS, $exclusive = true ) {
		
			switch( $type ) {
				case self::DIR_XCLUSIONS:
					$candidates = &$this->_dirs;
					break;
				case self::FILE_XCLUSIONS:
					$candidates = &$this->_files;
					break;
				case self::ALL_XCLUSIONS:
				default:
					$candidates = &$this->_all;
			}
			
			foreach ( $candidates as $candidate ) {
			
				// The path _must_ be found at the start of the candidate (to be a true prefix of the candidate)
				if ( 0 === strpos( $candidate, $path ) ) {
				
					// If not wanting exclusivity then can simply return here
					if ( false == $exclusive ) {
					
						return true;
						
					}
					
					// Otherwise we must check for exclusivity
					if ( 0 <> strcmp( $candidate, $path ) ) {
				
						return true;
							
					}
					
				}
				
			}
			
			return false;
			
		}
		
	}
	
	/**
	 *	pluginbuddy_zbdir_exclusion Class
	 *
	 *  Class for specifically handling file/directory exclusions
	 *	
	 *	@return		null
	 *
	 */
	class pluginbuddy_zbdir_exclusion extends pluginbuddy_zbdir_xclusion {

	}
	
	/**
	 *	pluginbuddy_zbdir_inclusion Class
	 *
	 *  Class for specifically handling file/directory inclusions
	 *	
	 *	@return		null
	 *
	 */
	class pluginbuddy_zbdir_inclusion extends pluginbuddy_zbdir_xclusion {
	
	}	
	
	/**
	 *	pluginbuddy_zbdir_node Class
	 *
	 *  Class for building file tree node
	 *
	 *	The file tree node is intended to be part of a structure that represents the
	 *	directories (and files) that are included in a tree built out of a root directory
	 *	and taking into account defined file/directory inclusions/exclusions. So dependent
	 *	on these definitions it could represent a complete tree with all directories and
	 *	files (where no exclusions are defined) or an empty tree where no directories or
	 *	files are included (where everything is defined to be excluded). Of course normally
	 *	it would be somewhere between these two extremes.
	 *
	 *	The tree can be built as a persistent structure - where the $keep parameter is defined
	 *	as true - in which case nodes will not be destroyed once visited and the nodes can
	 *	be visited again without having the do all the hard work of building the tree again.
	 *	If, on the other hand, $keep is defined as false then after a node is visited it
	 *	will be destroyed - which means the tree is treversed ina depth-first manner and the
	 *	number of nodes in existence at any one time is purely that number required to
	 *	represent the depth of the current path. The intention of this is of course to
	 *	minimize memory usage. This means though that the tree can only be visited once
	 *	and if any subsequent visit is required then it must be built again (either in keep
	 *	mode or not, dependent on the requirement). So fot multiple visits this is more
	 *	processot intensive - so it's really a balance of requirements vs resources.
	 * 
	 *	Visiing a tree means that we visit each node (in a depth first manner) and methods
	 * 	on a provided output handler are called by the node for each file/directory by
	 *	which the specific output handler can build up some information about the tree.
	 *	For example, a common requirement will be to build up a list of all files/directories
	 *	to be included in a backup, perhaps togetehr with a total count of the number of items
	 *	as well as number of files and directories and a total size of all the files. This would
	 *	then be used as input data for the actual zip file build or it could be used to show
	 *	the suer exactly what was going in to the backup, etc. The list format requirements
	 *	may be different for different zip methods, e.g., for pclzip we can only give it
	 *	directories that are actually empty to be included (as an empty directory) because it
	 *	itself will recurse down into directory content and we don't want it to do that - so
	 *	we cannot give it a directory in the list that is "empty" simply because it's content
	 *	has been excluded. For command line zip this doesn't matter as by default it doesn't
	 *	automatically recurse down and we will not tell it to - so we could give it any "empty"
	 *	directory in the list without harm. An output handler may creaet internal data structures
	 *	such as the file list being an array, but it may also create a list as a file which
	 *	would be less memory intensive for resource constrained servers. A file representation
	 *	is also better for command line zip as we can simply pass it the file name as parameter.
	 *	Future capabilities might allow exclusions based on criteria such as file size - so
	 *	exclude all files >X MB so as to avoid including other large backup files for example.
	 * 
	 *	The root path represents the root of where we are building the tree from. The path
	 *	is the path to this node relative to the root. So for a root of /home/user/site/ and
	 *	a path of /wp-content/plugins the actual node would represent /home/user/site/wp-content/plugins.
	 *	The root and path are combined for the purposes of finding if an item is a file or
	 *	a directory _but_ the exclusion/inclusion handling (including pattern based) is
	 *	based on the path only. This avoids having the do matching against long path strings
	 *	where the prefix is always the root anyway.
	 *
	 *	If symlinks are being ignored (not followed) then even if we come across a directory
	 *	that should be included in the backup we will only include it as such and not descend
	 *	into it. The actual zip method will determine how to handle that item as a symlink so
	 *	we do not completely ignore them.
	 *
	 *	The $in_exclusion_zone parameter is important as it tells this node whether or not it
	 *	is in an exclusion zone, i.e., between a directory exclusion and a more specific
	 *	directory inclusion. In that case we are just traversing through the directory and
	 *	other content that is not on the path to the inclusion should be ignored (unless the
	 *	specific subject of an inclusion - so a specific file _could_ be included from
	 *	within an exclusion zone.
	 *
	 *	The $depth parameter is really just a way of monitoring where we are an dhow deep
	 *	we are going. It has a special use at the root node which is to allow the node to
	 *	handle a specific exclusion zone case where the root node is immediately in an
	 *	exclusion zone. In theory the user of the root node could determine and set this
	 *	but it is safer to have the root node do it. The dpeth monitoring will also enable
	 *	us to consider bailing out if it appears we are disappearing down a black-hole
	 *	because of some bad looping symlink setup or whatever.
	 * 
	 * 	The $mode parameter is used to define how we respond to exclusions/inclusions. In
	 * 	a standard mode all excluded items are ignored totally. In a complete mode all
	 * 	items are recorded. In both cases the item 'status' will indicate whether the item
	 * 	is an excluded or included item and also a 'reason' may be recorded as to why the
	 * 	item is excluded or included. The reason may be present in a debug mode that would
	 * 	record, for example, the rule that was triggered and perhaps the specific matches
	 * 	that led to the exclusion or inclusion (FFS)
	 *	
	 *	@return		null
	 *
	 */
	class pluginbuddy_zbdir_node {

		const NORM_DIRECTORY_SEPARATOR	= '/';
		const STANDARD_MODE = 'standard';
		const COMPLETE_MODE = 'complete';
		const INCLUDE_ACTION = 'include';
		const EXCLUDE_ACTION = 'exclude';
		const NO_ACTION = 'none';
		const STATUS_INCLUDED = 'included';
		const STATUS_EXCLUDED = 'excluded';
		const STATUS_UNKNOWN = 'unknown';
		
		protected $_items = array();
		protected $_root = '';
		protected $_path = '';
		protected $_exclusions_handler = null;
		protected $_inclusions_handler = null;
		protected $_visitor = null;
		protected $_ignore_symlinks = true;
		protected $_in_exclusion_zone = false;
		protected $_keep = false;
		protected $_depth = 0;
		protected $_mode = self::STANDARD_MODE;
		
		// For storing data abount items in the directory represented by the node
		protected $_terminals = array();
		protected $_symdirs = array();
		protected $_children = array();
		
		// This is for storing data about this directory node
		protected $_self = array();
		// Directory content size
		protected $_csize = 0;
		// Directory total size is sum of the content size and total size of each child
		protected $_tsize = 0;
		// Indicates whether or not node has been visited to trigger one-time operations
		protected $_visited = false;
		// Indicates whether or not the directory is truly empty from the outset
		protected $_vacant = false;
		// Indicates the exclusion/inclusion status of _this_ directory
		protected $_status = self::STATUS_UNKNOWN;
		// Indicates whether _this_ directory would be empty (no content included)
		// even if building a complete tree where we record both excluded and included stuff
		protected $_empty = true;
	
		/**
		 *	__construct()
		 *	
		 *	Construct the node object
		 *
		 *	Constructs a tree node wheer $root is tha root of the tree and $path is the
		 *	specific path of this node.
		 *	1) root may be empty if using absolute paths for build and xclusions
		 *	2) root may be / if working in a "caged" filesystem
		 *	3) root may be //share/ if this is a windows share
		 *	4) root may be <drive>:/ if windows
		 *	As the path is generally relative to the root it makes handling exclusions and
		 *	inclusions easier/faster because shorter than the absolute paths would be.
		 *	
		 *	Both the root and the path must be normalized to *nix style deparators.
		 *
		 *	Will throw exception if a directory cannot be scanned.
		 *	
		 *  @param		string	$root				Directory path of the root of the tree
		 *  @param		string	$path				Directory path relative to the root
		 *	@param		object	$exclusion_handler
		 *	@param		object	$inclusion_handler
		 *	@param		object	$visitor
		 *	@param		bool	$ignore_symlinks
		 *	@param		bool	$keep
		 * 	@param		mixed 	$mode
		 *	@param		bool	$in_exclusion_zone
		 *	@param		int		$depth
		 *	@return		none
		 *
		 */
		public function __construct( $root = '', $path = '', $exclusions_handler = null, $inclusions_handler = null, $visitor = null, $ignore_symlinks = true, $keep = false, $mode = self::STANDARD_MODE, $in_exclusion_zone = false, $depth = 0  ) {
			
			// Do not change root even if it is just / because *nix can hanle
			// multiple / as path separators, e.g., /home/jeremy and //home/jeremy
			// and //home//jeremy and //home///jeremy are all equivalent
			// The caller must give us a root path that is / terminated
			$this->_root = trim( $root );
			
			// If path is / will not be changed
			// Path will have / suffix added even if it is empty
			// Note: this is the _internal_ representation of the path -
			// when combined with the root and when passed out it will
			// have the prefix / removed (even if te path is just /)
			// so that in combined case we don;t get // and in external
			// view the path is definitelly a relative path (to a root)
			// as it doesn't start with /
			$this->_path = ( self::NORM_DIRECTORY_SEPARATOR === ( $this->_path = trim( $path ) ) ) ? $this->_path : rtrim( $this->_path, self::NORM_DIRECTORY_SEPARATOR) . self::NORM_DIRECTORY_SEPARATOR ;
			
			// check if exclusions handler not object and throw exception
			// we must have an exclusions handler
			$this->_exclusions_handler = $exclusions_handler;
			
			// check if inclusions handler not object and throw exception
			// we must have an inclusions handler
			$this->_inclusions_handler = $inclusions_handler;
			
			// check if output handler not object and throw exception
			// we must have an output handler even if noop
			$this->_visitor = $visitor;
			
			// Global indication of whether or not we are ignoring/not-following
			// symlinks. If that is the case then although we note a directory
			// we will not descend into it. For a file we always note it anyway.
			$this->_ignore_symlinks = $ignore_symlinks;
			
			// We can choose to not keep child nodes as they are visited, so in
			// other words just traverse the structure and clean it up as we go.
			// This can use less memory but if we want to visit multiple times
			// it is less time efficient because we have to build/destroy the
			// nodes every time. 
			$this->_keep = $keep;
			
			// Record what mode we are operating in
			$this->_mode = $mode;
			
			// The parent is telling us that this node (directory) is in an
			// exclusion zone - the parent or a previous ancestor matched it
			// or a previous directory on this path as a specific exclusion
			// _but_ also as a prefix of a more specific inclusion and so the
			// path is being followed until that inclusion is reached. If this
			// node recognizes that more specific inclusion to be one of it's
			// children then it will signal to _that_ child that it is _not_
			// in an exclusion zone.
			// Whilst in an exclusion zone no directory that is not on the
			// path to a more specific inclusion will be remembered and only
			// files that match a specific inclusion will be noted.
			// We have to check whether our path is a specific exclusion for
			// the special case that this is the initial node _and_ the
			// initial node path is a specific exclusion (otherwise we would
			// require the caller to tell us this which wouldn't be great).
			// Also this depends on our path _not_ also being a specific inclusion
			// in which case this would override it being a specific exclusion.
			// We'll use the node depth to decide whether we make this test or
			// not - if it isn't the initial node then we just relt in whetever
			// our parent node has told us.
			$this->_in_exclusion_zone = $in_exclusion_zone;
			if ( 0 === $depth ) {
				// This is the initial node so we have to handle a special case
				// for the exclusion zone handling that the path is a match
				// to a specific exclusion and _not_ a match to a specifc inclusion.
				// This is becase the user could be excluding the initial directory
				// itself and if we didn't check this we would require the user
				// to tell us this which would be error prone. Note that if the
				// definitions also had the path being a specific inclusion then
				// this overrides the specific exclusion.
				// Note we probably just use the result of the match test since
				// this will be false unless the very specific condition applies
				// but for now we'll rely on the caller setting the initial
				// exclusion zone value to false if they don't know and then we can
				// override it if it should be true. The caller could mess things
				// up by setting it true incorrectly but then this would be a
				// programming error...
				$this->_in_exclusion_zone = ( $this->_in_exclusion_zone || ( ( true === $this->_exclusions_handler->matches( $this->_path ) && ( false === $this->_inclusions_handler->matches( $this->_path ) ) ) ) );				
			}
			
			// Keep track of our descent depth in case we later want to introduce
			// a limit in case of handling some error condition (e.g., loop)
			// Initial node is depth 0, incremented when passed to a child node
			$this->_depth = $depth;
			
			// Record our exclusion/inclusion status dependent on our exclusion zone status
			$this->_status = ( true === $this->_in_exclusion_zone ) ? self::STATUS_EXCLUDED : self::STATUS_INCLUDED ;
			
			// This is just to record when the node has been visited so that
			// on subsequent visits we do not repeat unnecessary work.
			$this->_visited = false;

			// check if we can scan directory and throw exception if failure
			if ( false === ( $this->_items = @scandir( $this->_root . ltrim( $this->_path, self::NORM_DIRECTORY_SEPARATOR ) ) ) ) {
				throw new Exception( 'Unable to scan directory ' . $this->_root . ltrim( $this->_path, self::NORM_DIRECTORY_SEPARATOR ) );
			}
			
			// First remove pesky entries if present
			$this->_items = array_diff( $this->_items, array( '..', '.' ) );
			
			// We must determine whether the directory is truly empty (scandir only returns . and ..)
			// or nothing at all for Windows(?) rather than later just empty because all it's
			// content has been excluded - there is a subtle difference
			$this->_vacant = empty( $this->_items );
			
			// Exclude some further known fluff that would count as content even if excluded
			// and so the directory could not be regarded as truly empty (vacant)
			$this->_items = array_diff( $this->_items, array( '.DS_Store' ) );
			
			// Now handle each item as per exclusions/inclusions
			// TODO: Have a mode whereby the user can decide that the tree should represent
			// the _complete_ tree with exclusions and inclusions. So instead of when an
			// exclusion match is made or implied we just ignore the item we do actually
			// record it as appropriate. For every item we alos incude a 'status' value that
			// indicates (currently) "excluded' or 'included' and then may have a 'reason' or
			// similar (perhaps 'rule') that can indicate why the file was excluded or included.
			// This might be "specific", "pattern', "implied", etc. or we could go to the
			// extreme and include what it matched to if we have some debug or "explain"
			// option when creating a list for a user to look at. This mode would allow us
			// to create the kind of Site Size Map sort of display. So we need an extra
			// option passed in to say if we want to do a complete tree analysis or not
			// and then for each rule we define whether to add to the terminals/symdirs/
			// children or not and in the not case we may still do it if we are doing a
			// complete tree analysis. So we might have each rule define the array of
			// key=>value pairs to add and then we choose to add or not - basically need
			// to work out the most efficient way.
			foreach ( $this->_items as $item ) {
				
				$action = self::NO_ACTION;
				$attributes = array();
				
				( @is_link( $this->_root . ltrim( $this->_path, self::NORM_DIRECTORY_SEPARATOR ) . $item ) ) ? $is_link = true : $is_link = false;
				if ( @is_file( $this->_root . ltrim( $this->_path, self::NORM_DIRECTORY_SEPARATOR ) . $item ) ) {
					
					// Setup the default attributes that apply to all files
					// Note: regardless of whatever rule may match, whether the file is
					// in an exclusion zone or not is always determined by the state of
					// _this_ directory
					$attributes = array( 'directory' => false, 'symlink' => $is_link, 'ignore' => $this->_ignore_symlinks, 'ezone' => $this->_in_exclusion_zone );
					
					// Rules:
					// 1) File matches specific inclusion - include file
					// 1a) File matches a specific pattern inclusion - include file
					// 2) File matches specific exclusion - exclude file
					// 2a) File matches a specific pattern exclusion - exclude file
					// 3) File path matches specific inclusion - include file
					// 3a) File path matches a specific pattern inclusion - include file
					// 4) File path matches specific exclusion - exclude file
					// 4a) File path matches a specific pattern exclusion - exclude file
					// 5) File path in exclusion zone - exclude file
					// 6) Default rule - include file
					if ( true === $this->_inclusions_handler->matches( $this->_path . $item ) ) {
						$action = self::INCLUDE_ACTION;
					} elseif ( true === $this->_inclusions_handler->matches_regex( $this->_path . $item ) ) {
						$action = self::INCLUDE_ACTION;
					} elseif ( true === $this->_exclusions_handler->matches( $this->_path . $item ) ) {
						$action = self::EXCLUDE_ACTION;
					} elseif ( true === $this->_exclusions_handler->matches_regex( $this->_path . $item ) ) {
						$action = self::EXCLUDE_ACTION;
					} elseif ( true === $this->_inclusions_handler->matches( $this->_path ) ) {					
						$action = self::INCLUDE_ACTION;
					} elseif ( true === $this->_inclusions_handler->matches_regex( $this->_path ) ) {					
						$action = self::INCLUDE_ACTION;
					} elseif ( true === $this->_exclusions_handler->matches( $this->_path ) ) {			
						$action = self::EXCLUDE_ACTION;
					} elseif ( true === $this->_exclusions_handler->matches_regex( $this->_path ) ) {			
						$action = self::EXCLUDE_ACTION;
					} elseif ( true === $this->_in_exclusion_zone ) {		
						$action = self::EXCLUDE_ACTION;
					} else {	
						$action = self::INCLUDE_ACTION;
					}
					
					// We will record the file if included or regardless if building a complete tree
					// otherwise just do nothing if the file is being excluded.
					// If we are recording an item we update empty based on whether it's being recorded
					// for a true include or an exclude on a complete tree build. Only if every recording
					// on a complete tree build is for an excluded item will empty remain true
					if ( ( self::INCLUDE_ACTION === $action ) || ( self::COMPLETE_MODE === $this->_mode ) ) {
						$this->_empty = ( $this->_empty && ( self::EXCLUDE_ACTION === $action ) );
						$attributes[ 'status' ] = ( self::INCLUDE_ACTION === $action ) ? self::STATUS_INCLUDED : self::STATUS_EXCLUDED ;
						$this->_terminals[ $this->_path . $item ] = self::stat( $this->_root, $this->_path, $item, $attributes );
					}
					
				} elseif ( @is_dir( $this->_root . ltrim( $this->_path, self::NORM_DIRECTORY_SEPARATOR ) . $item ) ) {

					// Setup the default attributes that apply to all directories
					$attributes = array( 'directory' => true, 'size' => (int)0, 'symlink' => $is_link, 'ignore' => $this->_ignore_symlinks, 'depth' => ( $this->_depth + 1 ) );

					// Rules:
					// 1) Directory matches specific inclusion - follow/record directory and exit
					//    exclusion zone (tell child _it_ isn't in exclusion zone)
					// 1a) Directory matches a specific pattern inclusion - follow/record directory and exit
					//    exclusion zone (tell child _it_ isn't in exclusion zone)
					// 2) Directory matches a specific exclusion _and_ is in the path to a more
					//    specific inclusion - follow/record directory and enter exclusion zone
					//    (tell child it is in an exclusion zone)
					// 2a) Directory matches a specific exclusion pattern _and_ is in the path to a more
					//    specific inclusion - follow/record directory and enter exclusion zone
					//    (tell child it is in an exclusion zone)
					// 3) Directory is in the path to a specific inclusion - follow/record directory
					//    and just pass on to child whether parent told us we were in an
					//    exclusion zone because we may or may not be
					// 4) The directory matches a specific exclusion - do not follow/record
					// 4a) The directory matches a specific exclusion pattern - do not follow/record
					// 5) _This_ directory is in an exclusion zone - do not follow/record directory
					// 6) Default rule - follow/record directory
					if ( true === $this->_inclusions_handler->matches( $this->_path . $item . '/' ) ) {
						// Rule 1
						$action = self::INCLUDE_ACTION;
						$attributes[ 'ezone' ] = false; 
					} elseif ( true === $this->_inclusions_handler->matches_regex( $this->_path . $item . '/' ) ) {
						// Rule 1a
						$action = self::INCLUDE_ACTION;
						$attributes[ 'ezone' ] = false; 
					} elseif ( ( true === $this->_exclusions_handler->matches( $this->_path . $item . '/' ) ) &&
							   ( true === $this->_inclusions_handler->prefixed_by( $this->_path . $item . '/' ) ) ) {
						// Rule 2
						$action = self::INCLUDE_ACTION;
						$attributes[ 'ezone' ] = true; 
					} elseif ( ( true === $this->_exclusions_handler->matches_regex( $this->_path . $item . '/' ) ) &&
							   ( true === $this->_inclusions_handler->prefixed_by( $this->_path . $item . '/' ) ) ) {
						// Rule 2a
						$action = self::INCLUDE_ACTION;
						$attributes[ 'ezone' ] = true; 
					} elseif ( true === $this->_inclusions_handler->prefixed_by( $this->_path . $item . '/' ) ) {
						// Rule 3
						$action = self::INCLUDE_ACTION;
						$attributes[ 'ezone' ] = $this->_in_exclusion_zone; 
					} elseif ( true === $this->_exclusions_handler->matches( $this->_path . $item . '/' ) ) {
						// Rule 4
						$action = self::EXCLUDE_ACTION;
						$attributes[ 'ezone' ] = true; 
					} elseif ( true === $this->_exclusions_handler->matches_regex( $this->_path . $item . '/' ) ) {
						// Rule 4a
						$action = self::EXCLUDE_ACTION;
						$attributes[ 'ezone' ] = true; 
					} elseif ( true === $this->_in_exclusion_zone ) {
						// Rule 5
						$action = self::EXCLUDE_ACTION;
						$attributes[ 'ezone' ] = true; 
					} else {
						// Rule 6
						$action = self::INCLUDE_ACTION;
						$attributes[ 'ezone' ] = $this->_in_exclusion_zone; 
					}
					
					// We will record the directory if included or regardless if building a complete tree
					// otherwise just do nothing if the file is being excluded
					if ( ( self::INCLUDE_ACTION === $action ) || ( self::COMPLETE_MODE === $this->_mode ) ) {
						$this->_empty = ( $this->_empty && ( self::EXCLUDE_ACTION === $action ) );
						$attributes[ 'status' ] = ( self::INCLUDE_ACTION === $action ) ? self::STATUS_INCLUDED : self::STATUS_EXCLUDED ;
						if ( ( true === $is_link ) && ( true === $this->_ignore_symlinks ) ) {
							$this->_symdirs[ $this->_path . $item ] = self::stat( $this->_root, $this->_path, $item, $attributes );
						} else {
							$this->_children[ $this->_path . $item ] = self::stat( $this->_root, $this->_path, $item, $attributes );
						}
					}

				}
			}
			
			// Set things up so now visit - this will recurse down the tree
			// if required and the child nodes will be kept or destroyed dependent
			// on the option. The keep=false option gives us a way to traverse a
			// directory tree with minimum resources as we destroy nodes after
			// they have been visited so the maximum number of nodes active at
			// any time is determined by the deepest path descent we haev to make.
			// In this case we cannot revisit the tree for different purposes
			// without redoing all the work. By contrast with keep=true we keep
			// all nodes and so keep the whole tree active so we can visit it
			// for different purposes without having to do all the work again, the
			// visit just uses what data we have already defined and stored. This
			// is obviously more resource intensive on memory but makes multiple
			// visits less cpu intensive.
			self::visit();		
	
		}
		
		/**
		 *	__destruct()
		 *	
		 *	Desroy the object - all object storage will be recoverd by default
		 *	
		 *	Simply destroy any child nodes (which will recurse down)
		 *	Everything else handled by the unset() of _this_ object
		 *	and the various handler objects are owned by the original
		 *	creator of the top level node and are used by all nodes so
		 *	we don't do anything to them.
		 *
		 *  @param		none
		 *	@return		none
		 *
		 */
		public function __destruct() {

			foreach ( $this->_children as &$child ) {
				if ( isset( $child[ 'child' ]) && ( is_object( $child[ 'child' ] ) ) ) {
					unset( $child[ 'child' ] );
				}
			}
			
		}
		
		/**
		 *	stat()
		 *	
		 *	Return an array of information about the particular item
		 *	
		 *	Return an array of information about the item
		 *	$extra is an array of key=>value pairs to merge in as additional/override
		 *	Note: for "absolute_path" key item we need to "fake" the $item value when
		 *	path is / otherwise we get the parent-of-the-parent from dirname()
		 * 	Note: $path will have / prefix _and_ suffix and $root will have a / suffix
		 * 	so we strip the prefix off the $path when concatenating them
		 *
		 *  @param		string	$root
		 *	@param		string	$path
		 *	@param		string	$item
		 *	@param		array	$extra
		 *	@return		array
		 *
		 */
		public function stat( $root = '', $path = '', $item = '', $extra = array() ) {

			$stat = array();
			
			$stat[ 'filename' ] = basename( $path . $item );
			$stat[ 'name' ] = ( $path . $item );
			// Relative path has no / prefix and has / suffix added _unless_ dirname() is only /
			$stat[ 'relative_path' ] = ltrim( dirname( $path . $item ), self::NORM_DIRECTORY_SEPARATOR ) . ( ( self::NORM_DIRECTORY_SEPARATOR === dirname( $path . $item ) ) ? '' : self::NORM_DIRECTORY_SEPARATOR );
			// Absolute path must be based on /path/item or, if item is empty, and has / suffix added
			$stat[ 'absolute_path' ] = dirname( $root . ltrim( $path, self::NORM_DIRECTORY_SEPARATOR ) .  ( ( ( self::NORM_DIRECTORY_SEPARATOR === $path ) && ( '' === $item ) ) ? '.' : $item ) ) . self::NORM_DIRECTORY_SEPARATOR;
	
			// For symlinks we are _not_ following we need to do lstat and not stat
			if ( ( isset( $extra[ 'symlink' ] ) && $extra[ 'symlink' ] ) && ( isset( $extra[ 'ignore' ] ) && $extra[ 'ignore' ] ) ) {
				$php_stat = @lstat( $root . ltrim( $path, self::NORM_DIRECTORY_SEPARATOR ) . $item );
			} else {
				$php_stat = @stat( $root . ltrim( $path, self::NORM_DIRECTORY_SEPARATOR ) . $item );
			}
			
			// Take what we want from the stat details - not much for now
			if ( is_array( $php_stat )) {
				$stat[ 'size' ] = $php_stat[ 'size' ];
			}
			
			// Record if the file is readable/writeable so we may do some preemptive troubleshooting
			$stat[ 'is_readable' ] = @is_readable( $root . ltrim( $path, self::NORM_DIRECTORY_SEPARATOR ) . $item );
			$stat[ 'is_writable' ] = @is_writable( $root . ltrim( $path, self::NORM_DIRECTORY_SEPARATOR ) . $item );
	
			// Add any additional information or any overrides
			$stat = array_merge( $stat, $extra );
			
			return $stat;
		}
		
		/**
		 *	get_tsize()
		 *	
		 *	Return an total size of all the content of the directory, including
		 *	subdirectories.
		 *
		 *  @param		none
		 *	@return		int		The total size of this directory content including subdirectories
		 *
		 */
		public function get_tsize() {
		
			return $this->_tsize;
		}
		
		/**
		 *	visit()
		 *	
		 *	The visit funciton that builds information about the node and extends down paths
		 *	for any subdirectories thus building the structure further.
		 *
		 *	Will call upon the output handler which may be explicitly passed for a subseqeunt
		 *	visit or will be the handler provided when the node was constructed.
		 *
		 *	Need to record for the directory whether it is truly empty or merely empty
		 *	because all files/directories have been excluded. This may need to be known
		 *	for some zip methods that can only include a directory as empty if it really
		 *	is empty, otherwise the zip method would recurse into it even though we had
		 *	excluded all the content.
		 *
		 *  @param		object	$visitor		The output handler to call upon
		 *	@return		none
		 *
		 */
		public function visit( $visitor = null ) {
			// Visit the node based on the terminals, children and symdirs
			// arrays calling the add method on the handler.
			// Add this node, all terminals and symdirs and traverse into
			// each child or
			if ( null === $visitor ) {
				$visitor = $this->_visitor;
			}
			
			// Assemble the details for this directory node
			if ( false === $this->_visited ) {
				// Directory itself has 0 size
				$vars = array( 'directory' => true, 'vacant' => $this->_vacant, 'size' => (int)0, 'ezone' => $this->_in_exclusion_zone, 'depth' => $this->_depth, 'status' => $this->_status );
				if ( true === $this->_vacant ) {
					// Directory is really empty, _never_ had any content
					// based on scandir() output for this directory being empty.
					// We can set the other vars as well. It's up to the 
					// zip method whether it includes this directory or not
					// (or rather the generator of the file list for the method).
					$vars[ 'empty' ] = true;
					$vars[ 'csize' ] = (double)0;
					$vars[ 'tsize' ] = (double)0;				
				} elseif ( empty( $this->_terminals ) && empty( $this->_symdirs ) && empty( $this->_children ) ) {
					// The directory originally had some content but it has all
					// been excluded. Again set the vars accordingly and it will
					// be up to the generator of the file list for the backup to
					// decide whether to include this directory dependent on how
					// the actual zip method woul dhandle it.
					$vars[ 'empty' ] = $this->_empty;
					$vars[ 'csize' ] = (double)0;
					$vars[ 'tsize' ] = (double)0;
				} else {
					// Directory with content needs content size and (initial) total size
					// Total size may be updated later if there are any children
					$vars[ 'empty' ] = $this->_empty;
					foreach ( $this->_terminals as $terminal ) {
						$this->_csize += (double)$terminal[ 'size' ];
					}
					$vars[ 'csize' ] = (double)$this->_csize;
					$vars[ 'tsize' ] = (double)$this->_tsize = (double)$this->_csize;
				}
				if ( @is_link( $this->_root . ltrim( $this->_path, self::NORM_DIRECTORY_SEPARATOR ) ) ) {
					$vars[ 'symlink' ] = true;
					$vars[ 'ignore' ] = $this->_ignore_symlinks;
				} else {
					$vars[ 'symlink' ] = false;
					$vars[ 'ignore' ] = $this->_ignore_symlinks;
				}
				$this->_self = self::stat( $this->_root, $this->_path, '', $vars );
			}

			// Now pass the item to the handler to add as appropriate to the handler type
			$visitor->add( $this->_self );
			// Get the key of the item just added so we can modify it later
			$update_key = $visitor->get_last_key();
	
			// Give output handler each terminal item
			foreach ( $this->_terminals as $terminal ) {
				$visitor->add( $terminal );
			}
			
			// Give output handler each symdir (these are directories we are _not_
			// following, not by virtue of being excluded as such but because we are
			// not following symlinks at all). So the recorded details for the symdir
			// element must mimic those of a directory as far as possible. One big
			// difference is that for a non followed symdir we cannot know whether
			// it is vacant nor empty and so in fact neither of those attributes
			// are set - this allows the user of the visitor data to determine the
			// condition of a directory being a non-followed symlink by virtue of
			// either or both of these attributes not being set. This can also
			// be determined by checking symlink and ignore attributes that will
			// be set. As an alternative we _could_ change vacant and empty to
			// be enumerated rather than boolean and we could give them a value
			// such as "unknown" in these cases but that starts to get messy as
			// it's nice to be able to do simple boolean tests on these attributes
			// where possible and since the condition can be determined by the available
			// attributes that seems to be the best solution at present.
			foreach ( $this->_symdirs as $symdir ) {
				$visitor->add( $symdir );			
			}
			
			// If we are visiting then what we do depends on whether this is a
			// kept file tree or not. If it is previously created and kept then
			// we should have an array of child nodes that we can visit. If this
			// is the first "visit" on creation then if it is keep then we create
			// and visit and keep the nodes otherwise we simply create and visit
			// and then destroy each node
			foreach ( $this->_children as &$child ) {
				
				if ( false === $this->_visited ) {
					// This is our first visit so we need to create the child object
					// If we are keeping the tree then we'll save the object reference
					// otherwise we'll destroy the child.
					$child[ 'child' ] = new pluginbuddy_zbdir_node( $this->_root, $child[ 'name' ], $this->_exclusions_handler, $this->_inclusions_handler, $this->_visitor, $this->_ignore_symlinks, $this->_keep, $this->_mode, $child[ 'ezone' ], $child[ 'depth' ] );
					// Increment the total size for this directory node by the total size of the child
					$this->_tsize += (double)$child[ 'child' ]->get_tsize(); 
					if ( false === $this->_keep ) {
						unset( $child[ 'child' ] );
					}
				} else {
					// We have already been visited so this must be a kept tree so just
					// do a direct visit to the children
					// Could throw an exception if don't have a child object
					if ( isset( $child[ 'child' ]) && ( is_object( $child[ 'child' ] ) ) ) {
						$child[ 'child' ]->visit( $visitor );
					}
					
				}
	
			}
			
			// Now we have to do some updating on first visit to patch values we
			// didn't know before - use the item key we saved earlier
			if ( false === $this->_visited ) {
				$this->_self[ 'tsize' ] = (double)$this->_tsize;
				$visitor->update( $update_key, array( 'tsize' => (double)$this->_tsize ) );
			}
			
			// Remember that we have been visited so that any subsequent visit
			// will just use what has already been set up
			$this->_visited = true;
			
			// The caller may need to know the actual visitor used if called with null
			return $visitor;
			
		}
	
	}
	
	class pluginbuddy_zbdir_null_object {
		
		public function __construct() {
			
		}
		
		public function __destruct() {
			
		}
		
		public function __call( $method, $arguments ) {
			
		}
		
	}
		
	// Currently just a wrapper for pb_backupbuddy::status()
	// TODO: Would prefer to have a generic logger and this would
	// extend it if required (we may not even need this dependent
	// on how logging evolves)
	class pluginbuddy_zbdir_logger {
		
		protected $_prefix = '';
		protected $_suffix = '';
		
		public function __construct( $prefix = "", $suffix = "" ) {
			
			if ( !empty( $prefix ) ) {

				$this->set_prefix( $prefix );

			}
			
			if ( !empty( $suffix ) ) {

				$this->set_suffix( $suffix );

			}
			
		}
		
		public function __destruct() {
			
		}
		
		public function set_prefix( $prefix = "" ) {
			
			$this->_prefix = $prefix;
			
			return $this;
			
		}
		
		public function get_prefix() {
			
			return $this->_prefix;

		}
		
		public function set_suffix( $suffix = "" ) {
			
			$this->_suffix = $suffix;
			
			return $this;
			
		}
		
		public function get_suffix() {
			
			return $this->_suffix;

		}
		
		public function log( $level, $message, $prefix = null, $suffix = null ) {
			
			$prefix_to_use = ( is_null( $prefix ) ) ? $this->_prefix : ( ( is_string( $prefix ) ) ? $prefix : "" ) ;
			$suffix_to_use = ( is_null( $suffix ) ) ? $this->_suffix : ( ( is_string( $suffix ) ) ? $suffix : "" ) ;
			
			pb_backupbuddy::status( $level, $prefix_to_use . $message . $suffix_to_use );
			
			return $this;
			
		}
		
	}

	// Basic class definition that satisfies node requirements
	class pluginbuddy_zbdir_visitor {
		
		protected $_logger = null;
		
		protected $_process_monitor = null;
	
		public function __construct() {
			
		}
		
		public function __destruct() {
			
		}
		
		public function add( $item = array() ) {
		
		}
		
		public function get_last_key() {
			return 0;
		}
		
		public function update( $key, $updates = array() ) {

		}
		
		public function finalize() {

		}

		public function set_logger( $logger ) {
			
			$this->_logger = $logger;
			
			return $this;
			
		}
		
		public function get_logger() {
			
			if ( is_null( $this->_logger ) ) {

				$logger = new pluginbuddy_zbdir_null_object();
				$this->set_logger( $logger );

			}
			
			return $this->_logger;
			
		}
		
		public function set_process_monitor( $process_monitor ) {
			
			$this->_process_monitor = $process_monitor;
			
			return $this;
			
		}
		
		public function get_process_monitor() {
			
			// If no process monitor has been defined then create a
			// null object to use.
			if ( is_null( $this->_process_monitor ) ) {
				
				$pm = new pluginbuddy_zbdir_null_object();
				$this->set_process_monitor( $pm );
				
			}
			
			return $this->_process_monitor;
			
		}
		
	}

	// This class can be used to visit the the tree and builds a flat array
	// of the tree contents with all the details for every file and directory
	// that are defined to be in the tree. It can be used to get details about
	// the tree such as the number of files and directories, the total size of 
	// all included files, listing of contents in various forms, etc. It is
	// not specific to building a list of backup contents for any particular
	// zip method but can be used to derive such a list. Alternatively a
	// method specific visitor could be defined by the method that would target
	// just that required to produce the list for that method in whatever
	// format was required.
	class pluginbuddy_zbdir_visitor_details extends pluginbuddy_zbdir_visitor {
		
		// This array will hold the details for each item in the tree
		protected $_items = array();
		
		// This array will hold keys of the fields that the vistor wants when
		// an item is added, e.g., just file name or maybe naem and size, etc.
		protected $_wanted_keys = array();
		
		// This bool tells us whether we want all fields or only those as
		// defined by the $_wanted_keys array
		protected $_want_all = true;

		public function __construct( $wanted_keys = array() ) {
		
			// Setup the array of wanted keys - we're assuming that we'll only want
			// a subset in general so always do this rather than bother to test if
			// wanted_keys is empty as there is little overhead in the foreach loop
			// if it is
			foreach ( $wanted_keys as $key ) {
				$this->_wanted_keys[ $key ] = true;
			}
			
			$this->_want_all = ( empty( $this->_wanted_keys ) ) ? true : false ;
		
			parent::__construct();
			
		}
		
		public function __destruct() {
		
			parent::__destruct();
			
		}
		
		public function add( $item = array() ) {
		
			if ( true === $this->_want_all ) {
			
				// Add the item - note that just numeric keys for now, not using
				// any item value for key
				$this->_items[] = $item;
			
			} else {
			
				// We need to only take the item fields that we want
				$this->_items[] = array_intersect_key( $item, $this->_wanted_keys );
			
			}
			
			if ( 0 === ( ( $count = $this->count() ) % 100 ) ) {
				
				// Keep an eye on process progress (if there is a process monitor set)
				$this->get_process_monitor()->checkpoint();
				
				// Log progress (if there is a logger set)
				$this->get_logger()->log( 'details', 'Determining list of candidate files + directories to be added to the zip archive: ' . $count );
				
			}
			
		}
		
		public function get_last_key() {
		
			// Tell the caller the array key of the item just added
			return ( count( $this->_items ) - 1 );
			
		}
		
		public function update( $key, $updates = array() ) {
		
			// The caller wants to update some details of the item identified by the key
			if ( isset( $this->_items[ $key ] ) ) {
			
				// Create an array for the update based on whether we want all fields or not
				
				if ( true === $this->_want_all ) {
		
					// We are using all fileds so want to update all
					$item_update = $updates;
		
				} else {
		
					// We need to only take the item fields that we want
					$item_update = array_intersect_key( $updates, $this->_wanted_keys );
		
				}
			
				$this->_items[ $key ] = array_merge( $this->_items[ $key ], $item_update );
				
			}
			
		}
		
		// Called by user of the visitor after completion of visit to do
		// any final actions
		public function finalize() {

			// By default logging every 100 items but we need to also log the final count
			// which in general will not be an exact multiple of 100
			$count = $this->count();
			$this->get_logger()->log( 'details', 'Determining list of candidate files + directories to be added to the zip archive: ' . $count );
			
		}
		
		// Get selected item values as a string for display or otherwise
		// Normally a bool will be cast as empty string if false which isn't
		// ideal in this context so we handle this specifcally. We could call
		// a get_key_type() function on a class that defines the item attribute
		// keys and use that in a switch to handle the specific key conversion
		// to string but this FFS.
		public function get_as_string( $keys = array(), $delimiter = ':' ) {
		
			$strings = array();
			foreach ( $this->_items as $item ) {
			
				$string = '';
				foreach ( $keys as $key ) {
				
					if ( isset( $item[ $key ] ) ) {
					
						if ( is_bool( $item[ $key ] ) ) {
						
							$string .= ( $item[ $key ] ) ? '1': '0' ;
							
						} else {
						
							$string .= $item[ $key ];
							
						}
						
					}
					
					// Always add delimiter to delimit fields
					$string .= $delimiter;
					
				}
				
				// Always trim off final delimiter
				if ( false !== ( $where = strrpos( $string, $delimiter ) ) ) {
					$string = substr( $string, 0, $where );
				}
				
				$strings[] = $string;
				
			}
			
			return $strings;
			
		}
		
		// Return the list as an array where each item only has the specific details
		// identified by the requested keys 
		public function get_as_array( $keys = array() ) {
		
			$result = array();
			
			foreach ( $this->_items as $item ) {
			
				$current = array();
				foreach ( $keys as $key ) {
				
					( isset( $item[ $key ] ) ) ? ( $current[ $key ] = $item[ $key ] ) : false ;
					
				}
				
				$result[] = $current;
				
			}
			
			return $result;
			
		}
		
		// Simple function to count the number of items that match some
		// key=>value pair criteria. Currently it's just a "match-all"
		// criteria.
		// FFS: do we need to make this more powerful?
		public function count( $criteria = array() ) {

			$count = 0;
			
			if ( empty( $criteria ) ) {
			
				$count = count( $this->_items );
				
			} else {
			
				foreach ( $this->_items as $item ) {
				
					$match = true;
					foreach ( $criteria as $key => $value ) {
					
						( isset( $item[ $key ] ) && ( $value === $item[ $key ] ) ) ? $match : $match = false ;

					}
					
					( $match ) ? $count++ : $count ;
					
				}
				
			}
			
			return $count;
			
		}
		
	}

	/**
	 *	pluginbuddy_zbdir Class
	 *
	 *  Class for building a list of files to be included in a backup
	 *	
	 *	@return		null
	 *
	 */

	class pluginbuddy_zbdir {
	
		const NORM_DIRECTORY_SEPARATOR = '/';
		const DIRECTORY_SEPARATORS = '/\\';
		const TREE_NONE = 0;
		const TREE_SHALLOW = 1;
		const TREE_DEEP = 2;

        /**
         * The path of this directory node
         * Will have a trailing directory separator
         * 
         * @var root string
         */
        protected $_root = "";
        
        protected $_options = array();
        
        protected static $_default_options = array( 'exclusions' => array(),
													'exclusions_handler' => null,
        										    'inclusions' => array(),
 													'inclusions_handler' => null,
													'pattern_exclusions' => array(),
        										    'pattern_inclusions' => array(),
        										    'pattern_auto_delimit' => true,
        										    'visitor' => null,
        										    'ignore_symlinks' => true,
        										    'keep_tree' => false);
        								  
        protected $_exclusions_handler = null;
        protected $_inclusions_handler = null;
        protected $_visitor = null;
        
        protected $_root_node = null;

		/**
		 *	__construct()
		 *	
		 *	Default constructor.
		 *	
		 *	
		 *	@param		string		$root			The root path of the tree
		 *	@param		array		$options		The various options as an associative array
		 *	@return		null
		 *
		 */
		public function __construct( $root = '', $options = array() ) {
		
			// Do not change root even if it is just / because *nix can hanle
			// multiple / as path separators, e.g., /home/jeremy and //home/jeremy
			// and //home//jeremy and //home///jeremy are all equivalent
			// But it _must_ be terminated by / (to be consistent with WordPress
			// representation of directory paths) so we must ensure this. The only
			// case where we do noting is if the root is simply /. If the root is
			// a wonky Windows path like //share/whatever that's ok - it should
			// never be just // as that is an incomplete path specification.
			$this->_root = ( self::NORM_DIRECTORY_SEPARATOR === ( $this->_root = trim( $root ) ) ) ? $this->_root : rtrim( $this->_root, self::NORM_DIRECTORY_SEPARATOR) . self::NORM_DIRECTORY_SEPARATOR ;

			// Get our options based on defaults or passed values
			$this->_options = array_merge( self::$_default_options, $options );
			
			// Use provided exclusions handler, otherwise create our own
			// Have to handle populating the handler slightly differently
			// for each case
			if ( is_object( $this->_options[ 'exclusions_handler' ] ) ) {
				
				$this->_exclusions_handler = $this->_options[ 'exclusions_handler' ];
				
				// Must add any exclusions provided - the provided handler must have
				// had the root option correctly set to allow for properly checking
				// if an exclusion is a directory when it has no trailing slash
				$this->_exclusions_handler->add( $this->_options[ 'exclusions' ] );
				
				// Must add any pattern exclusions provided using the auto-delimit mode
				// chosen by the user which may not be the same as the provided handler
				// was created with as default
				$this->_exclusions_handler->add_pattern( $this->_options[ 'pattern_exclusions' ], $this->_options[ 'pattern_auto_delimit' ] );

			} else {
				
				// Note: exclusions are added at creation of handler and the user chosen
				// auto-delimit mode is set as the default
				$this->_exclusions_handler = new pluginbuddy_zbdir_exclusion( $this->_options[ 'exclusions' ], array( 'root' => $this->_root, 'pattern_auto_delimit' => $this->_options[ 'pattern_auto_delimit' ] ) );

				// Pattern exclusions added using the previously set auto-delimit mode
				$this->_exclusions_handler->add_pattern( $this->_options[ 'pattern_exclusions' ] );
				
			}
			
			// Use provided inclusions handler, otherwise create our own
			// Have to handle populating the handler slightly differently
			// for each case
			if ( is_object( $this->_options[ 'inclusions_handler' ] ) ) {
				
				$this->_inclusions_handler = $this->_options[ 'inclusions_handler' ];
				
				// Must add any inclusions provided - the provided handler must have
				// had the root option correctly set to allow for properly checking
				// if an inclusion is a directory when it has no trailing slash
				$this->_inclusions_handler->add( $this->_options[ 'inclusions' ] );
				
				// Must add any pattern inclusions provided using the auto-delimit mode
				// chosen by the user which may not be the same as the provided handler
				// was created with as default
				$this->_inclusions_handler->add_pattern( $this->_options[ 'pattern_inclusions' ], $this->_options[ 'pattern_auto_delimit' ] );

			} else {
				
				// Note: inclusions are added at creation of handler and the user chosen
				// auto-delimit mode is set as the default
				$this->_inclusions_handler = new pluginbuddy_zbdir_inclusion( $this->_options[ 'inclusions' ], array( 'root' => $this->_root, 'pattern_auto_delimit' => $this->_options[ 'pattern_auto_delimit' ] ) );

				// Pattern inclusions added using the previously set auto-delimit mode
				$this->_inclusions_handler->add_pattern( $this->_options[ 'pattern_inclusions' ] );
				
			}
			
			// Now we need a visitor that we should have been given. If not then we
			// create a null visitor that does nothing and the assumtion is that the tree is
			// being kept and will be visited with a specific visitor subsequently
			if ( null == $this->_options[ 'visitor' ] ) {
				// Not given one - we need at least a basic one so create it
				$this->_visitor = new pluginbuddy_zbdir_visitor();
			} else {
				$this->_visitor = $this->_options[ 'visitor' ];
			}
			
			// Now we are ready to build the tree
			try {
				$this->_root_node = new pluginbuddy_zbdir_node( $this->_root, '', $this->_exclusions_handler, $this->_inclusions_handler, $this->_visitor, $this->_options[ 'ignore_symlinks' ], $this->_options[ 'keep_tree' ] );
			} catch ( Exception $e ) {
				// Log the problem
				//pb_backupbuddy::status( 'details', sprintf( __('Exception - unable to build directory tree: %1$s','it-l10n-backupbuddy' ), $e->getMessage() ) );

				// Maybe we should clean up our handlers, etc., or maybe doesn't
				// really matter at present as we will likely terminate anyway
				
				// And throw it on
				throw $e;
			}
			
			// Do any last actions required by the visitor after we have fully traversed the tree
			$this->_visitor->finalize();
			
			// If we didn't bomb out with an exception then we should have built the tree and visited it
			// with either our null visitor or the visitor we were given. The caller can use their visitor
			// as they require as it is owned by them. We may be asked to visit again in which case we
			// will either be given a visitor to use or the same one as was originally provided will be used
			// if none is given (which actually means we call the root node with no visitor since it
			// remembers the original visitor and will use it again). Obvously need to take care with
			// this - the caller should manage it's own visitor which may mean "clearing" it before
			// using it again dependent on what it actually does.
		
		}
		
		/**
		 *	__destruct()
		 *	
		 *	Default destructor.
		 *	
		 *	@return		null
		 *
		 */
		public function __destruct( ) {
		
			// Destroy exclusions handler if we own it
			if ( null == $this->_options[ 'exclusions_handler' ] ) {
				unset( $this->_exclusions_handler );
			}
			
			// Destroy inclusions handler if we own it
			if ( null == $this->_options[ 'inclusions_handler' ] ) {
				unset( $this->_inclusions_handler );
			}
			
			// Destroy the output handler if we own it
			if ( null == $this->_options[ 'visitor' ] ) {
				unset( $this->_visitor );
			}
			
			// Finally destroy the root node which will destroy the tree as required
			unset( $this->_root_node );
		
		}
		
		public function visit( $visitor = null ) {
		
			// Being asked to visit the tree again - note that if the visitor is null then
			// previous visitor is being used again and the root node will have remembered
			// it so we just call the root node visit with visitor. for this reason we have
			// the visit() function return the actual visitor used so we can then call the
			// finalise() function.
			// FFS: Mayber we should check we have a kept tree otherwise there is nothing to
			// visit
			
			$visitor = $this->_root_node->visit( $visitor );
		
			$visitor->finalize();
			
		}
	
	}

}
?>
