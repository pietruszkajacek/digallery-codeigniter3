$(function() {
	$.widget( "qsr.select_category", {
		// default options
		options: {
			//remote: "/profile/get_categories_ajax/",
			categories: [],
			category_id: null,
			select_width: 280,
			select_height: 160,
			max_width: 560,
			height: 366,
			
			// callbacks
			change: null
		},
	
		categories: [ ],
		selects: [ ],
		
		// the constructor
		_create: function () {
//			$.post( this.options.remote, $.proxy( function ( data ) {
//				this.categories = data;
				
				this.categories = this.options.categories;
				
				this.wrapper = $( "<div>", {
					"class": "widget_nested_cats"
				})
				.css( {
					"max-width": this.options.max_width,
					"overflow-x": 'scroll',
					"background-color": "#ddd"
				} )
				.appendTo( this.element );

				this.container = $( "<div>", {
					"class": "selects_list_container"
				} )
				.css( {
					"width": this.options.select_width, 
					"overflow-x": 'auto'
				} )
				.appendTo( this.wrapper );

				//this._create_select( null );
				this._build( this.options.category_id );

				this._on( this.container, {
					"click a": function ( event ) {

						var target = event.target,
							$current_select = $( target ).parent(),
							level = parseInt( $current_select.attr( "data-level" ) ),
							cat_id = $( target ).attr( "data-id-cat" ),
							categories = [];

						if ( target !== this.selects[level].current_item ) {
							if ( this.selects[level].current_item !== null )
								this._highlight_off_item( this.selects[level].current_item );

							this.selects[level].current_item = target;
							this._highlight_on_item( target );

							categories = this._get_sub_categories( cat_id );

							if ( categories.length > 0 ) {
								//dana kategoria posiada podkategorie...
								//bieżacym selectem jest ostatni select - tylko tworzenie nowego bez usuwania
								if ( (this.selects.length - 1) - level === 0 ) {
									this.container.width( this.container.width() + this.options.select_width );
									this._create_select( cat_id );
								} else {
									//usuwanie selectow
									if ( (this.selects.length - 1) - level === 1 ) {
										//tylko jeden select przed biezacym - usuwanie opcji i dodawanie nowych bez usuwania selecta
										$( 'a', this.selects[level + 1].select ).remove();
										this._fill_select( this.selects[level + 1].select, categories );
										this.selects[level + 1].current_item = null;

									} else {
										//wiecej selectow przed biezacym
										//Usunięcie zbędnych list wyboru
										this._remove_selects( level + 2 );
										$( 'a', this.selects[level + 1].select ).remove();
										this._fill_select( this.selects[level + 1].select, categories );
										this.selects[level + 1].current_item = null;
										//dostosowanie szerokosci kontenera
										this.container.width( (level + 2) * this.options.select_width );
									}
								}
							} else {
								//dana kategoria nie posiada podkategorii
								if ( (this.selects.length - 1) - level > 0 ) {
									//Usunięcie zbędnych list wyboru
									this._remove_selects( level + 1 );
									//Dostosowanie szerokości kontenera z listami wyboru
									this.container.width( (level + 1) * this.options.select_width );
								}
							}

							this.options.category_id = cat_id;
							this._trigger('change', null, { "cat_id": cat_id, leaf: ( level === this.selects.length - 1 ? true : false ) } );
						}
						event.preventDefault();
					}
				} );
//			}, this ) );
		},
	
		_build: function ( cat_id ) {
			var path = this._build_path( cat_id );
			
			if (this.selects.length > 0)
				this._remove_selects(0);
			
			if ( path.length > 0 ) {
				for ( var i = 0; i < path.length; i++ ) {
					this._create_select( path[i].parent_cat_id );
					this.selects[i].current_item = $( 'a[data-id-cat="' + path[i].id + '"]' )[0];
					this._highlight_on_item( this.selects[i].current_item );
				}
				/*
				if ( this._get_sub_categories( cat_id ).length > 0 ) 
					this._create_select( cat_id );
				*/
				var leaf = parseInt( path[i - 1].lft ) === ( parseInt(path[i - 1].rgt) - 1 );
				
				if ( !leaf )
					this._create_select( cat_id );
				
				this.container.width(this.selects.length * this.options.select_width);
				
				//this._trigger('change', null, { "cat_id": cat_id, leaf: ( path.length === this.selects.length ? true : false ) } );
				this._trigger('change', null, { "cat_id": cat_id, "leaf": leaf } );
			} else {
				this._create_select( null );
			}
		},
		
		path_levels_str: function ( separator ) {
			var path = this.path_levels();
			
			if ( path.length > 0 )
				return path.join( ' ' + separator + ' ');
			else
				return "";
		},
		
		path_levels: function () {
			var path = this._build_path( this.options.category_id ),
				short_path = [ ];
			
			if ( path.length > 0 ) {
				for ( var i = 0; i < path.length; i++ )
					short_path.push( path[i].name_cat );
			}
			
			return short_path;
		},
		
		_build_path: function ( cat_id ) {
			var path = [ ];
			while ( cat_id !== null) {
				for ( var i = 0; i < this.categories.length; i++ ) {
					if ( this.categories[i].id === cat_id ) {
						path.unshift( this.categories[i] );
						cat_id = this.categories[i].parent_cat_id;
						break;
					}
				}
			}
		
			
			return path;
		},

		_highlight_on_item: function ( item ) {
			$( item ).css( {'background-color': 'whitesmoke'} );
		},
			
		_highlight_off_item: function ( item ) {
			$( item ).css( {'background-color': ''} );
		},

		_create_select: function ( parent_cat_id ) {
			var $select = $( "<div>", {
				"class": "select",
				"data-level": this.selects.length
			} )
			.css( {
				"float": "left",
				"width": this.options.select_width,
				"height": this.options.select_height,
				"overflow-y": "auto"
			} )
			.appendTo( this.container );

			this._fill_select( $select, this._get_sub_categories( parent_cat_id ) );
			this.selects.push( { select: $select[0], current_item: null } );
		},
			
		_remove_selects: function ( level ) {
			for ( var i = level; i < this.selects.length; i++ )
				$( this.selects[i].select ).remove();

			this.selects = this.selects.slice( 0, level );
		},
	
		_fill_select: function ( sel, items ) {
			for ( var i = 0; i < items.length; i ++ ) {
				$( "<a>", {
					text: items[i].name_cat,
					href: '#',
					"data-id-cat": items[i].id
				} )
				.css( {'display': 'block'} )
				.appendTo( sel );
			}
		},
			
		//Zwraca tablice z podkategoriami
		_get_sub_categories: function ( parent_cat_id ) {
			var sub_categories = [ ];
			for ( var i = 0; i < this.categories.length; i ++ ) {
				if ( this.categories[i].parent_cat_id === parent_cat_id )
					sub_categories.push( this.categories[i] );
			}
			return sub_categories;
		},
			
		// events bound via _on are removed automatically
		// revert other modifications here
		_destroy: function () {
			// remove generated elements
			this.wrapper.remove();
		},
			
		// _setOptions is called with a hash of all options that are changing
		// always refresh when changing options
		_setOptions: function () {
			alert('Ops');
			// _super and _superApply handle keeping the right this-context
			this._superApply( arguments );
			this._refresh();
		},
			
		// _setOption is called for each individual option that is changing
		_setOption: function ( key, value ) {
			alert('Op');
			this._super( key, value );
		}
	});
});