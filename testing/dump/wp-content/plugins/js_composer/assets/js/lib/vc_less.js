if ( _.isUndefined( window.vc ) ) {
	var vc = {};
}

vc.less = {};
vc.less.options = {
	relativeUrls: false,
	rootpath: false
};
less.options.env = vcData.debug ? 'development' : 'production';
less.options.logLevel = vcData.debug ? 4 : 0;
vc.less.generateLessFormData = function ( formData, variablesData ) {
	var lessData = {};
	if ( ! _.isEmpty( variablesData ) ) {
		_.each( variablesData, function ( value, key ) {
			var object, objectValue;
			if ( _.isString( value ) ) {
				object = _.first( _.where( formData, { 'name': value } ) );
				if ( _.isObject( object ) ) {
					objectValue = object.value;
					if ( 0 < objectValue.length ) {
						lessData[ key ] = objectValue;
					}
				}
			} else if ( _.isObject( value ) && ! _.isUndefined( value.key ) ) {
				object = _.first( _.where( formData, { 'name': value.key } ) );
				if ( ! _.isObject( object ) && ! _.isUndefined( value.default_key ) ) {
					if ( ! _.isUndefined( lessData[ value.default_key ] ) ) {
						object = { value: lessData[ value.default_key ] }; // take the data from already parsed variable
					} else {
						object = _.first( _.where( formData, { 'name': value.default_key } ) ); // take data from form
					}
				} else if ( ! _.isObject( object ) && ! _.isUndefined( value.default ) ) {
					object = { value: value.default }; // pass default value to data
				}

				if ( _.isObject( object ) ) {
					objectValue = object.value;

					if ( ! _.isUndefined( value.modify_output ) && _.isObject( value.modify_output ) && ! _.isEmpty( value.modify_output ) ) {
						_.each( value.modify_output, function ( modifier ) {
							// In case if value must be wrapped in some mixin
							if ( ! _.isUndefined( modifier.plain ) && _.isObject( modifier.plain ) && ! _.isEmpty( modifier.plain ) ) {
								_.each( modifier.plain, function ( data ) {
									var localValue;

									localValue = data.replace( '{{ value }}', objectValue );
									objectValue = localValue;
								} );
							}

							if ( ! _.isUndefined( modifier.js_function ) && _.isObject( modifier.js_function ) && ! _.isEmpty( modifier.js_function ) ) {
								_.each( modifier.js_function, function ( data ) {
									if ( ! _.isUndefined( data.function ) ) {
										var localValue, func;

										func = new Function( 'value', 'args', data.function );
										if ( ! _.isUndefined( data.arguments ) ) {
											localValue = func( objectValue, data.arguments );
										} else {
											localValue = func( objectValue );
										}
										objectValue = localValue;
									}
								} );
							}

						} );
					}

					if ( objectValue && 0 < objectValue.length ) {
						lessData[ key ] = objectValue;
					}
				}
			}
		} );
	}

	return lessData;
};
vc.less.fileManager = less.FileManager.prototype.extractUrlParts;

less.FileManager.prototype.extractUrlParts = function ( url, baseUrl ) {
	var output;

	url += '?v=' + ( window.vcData && window.vcData.version ? window.vcData.version : '4.5' );
	output = vc.less.fileManager( url, baseUrl );

	return output;
};

vc.less.build = function ( options, callback ) {
	var self;

	this.options = _.extend( {}, {
		modifyVars: {},
		variablesDataLinker: {},
		lessPath: ''
	}, this.options, options );

	this.options.modifyVars = this.generateLessFormData( this.options.modifyVars, this.options.variablesDataLinker );
	self = this;
	_.defer( function () {
		less.render(
			'@import "' + self.options.lessPath + '";',
			self.options
		).then(
			function ( output ) {
				callback && callback.call( self, output );
			},
			function ( error ) {
				callback && callback.call( self, undefined, error );
			}
		);
	} );
};