( function( $, _, Backbone, builderSettings, sectionData ) {

	var Model = Backbone.Model.extend( {
		defaults: function() {
			return _.extend( {}, sectionData.defaults.text, {
				id: _.uniqueId( 'text_' ),
			} );
		},

		initialize: function( attrs ) {
			this.items = attrs.columns;
			this.set( 'columns', new Backbone.Collection(), { silent: true } );
		},
	} );

	var ItemModel = Backbone.Model.extend( {
		defaults: function() {
			return _.extend( {}, sectionData.defaults['text-item'], {
				id: _.uniqueId( 'text-item_' ),
			} );
		},
	} );

	var View = make.classes.SectionView.extend( {
		template: wp.template( 'ttfmake-section-text' ),

		events: _.extend( {}, make.classes.SectionView.prototype.events, {
			'click .ttfmake-section-configure': 'onConfigureSectionClick',
			'click .ttfmake-text-columns-add-column-link': 'onAddItemClick',
		} ),

		initialize: function() {
			make.classes.SectionView.prototype.initialize.apply( this, arguments );
			this.itemViews = new Backbone.Collection();
		},

		afterRender: function() {
			make.classes.SectionView.prototype.afterRender.apply( this, arguments );

			this.listenTo( this.model, 'change:columns-number', this.onColumnCountChanged );
			this.listenTo( this.model.get( 'columns' ), 'add', this.onItemModelAdded );
			this.listenTo( this.model.get( 'columns' ), 'remove', this.onItemModelRemoved );
			this.listenTo( this.model.get( 'columns' ), 'reset', this.onItemModelsSorted );
			this.listenTo( this.model.get( 'columns' ), 'add remove change reset', this.onItemCollectionChanged );
			this.listenTo( this.itemViews, 'add', this.onItemViewAdded );
			this.listenTo( this.itemViews, 'remove', this.onItemViewRemoved );
			this.listenTo( this.itemViews, 'reset', this.onItemViewsSorted );

			var items = this.model.items || _.times( sectionData.defaults.text['columns-number'], _.constant( sectionData.defaults['text-item'] ) );
			var itemCollection = this.model.get( 'columns' );

			_.each( items, function( itemAttrs ) {
				var itemModel = make.factory.model( itemAttrs );
				itemModel.parentModel = this.model;
				itemCollection.add( itemModel );
			}, this );

			this.model.trigger('change:columns-number');

			this.initSortables();
			this.on( 'sort-start', this.onItemSortStart, this );
			this.on( 'sort-stop', this.onItemSortStop, this );
		},

		onColumnCountChanged: function( itemModel ) {
			var newColumnCount = this.model.get( 'columns-number' );
			var currentColumnCount = this.model.get( 'columns' ).size();
			var neededColumns = newColumnCount - currentColumnCount;
			var $stage = $( '.ttfmake-text-columns-stage', this.$el );

			if ( neededColumns > 0 ) {
				var items = _.times( neededColumns, _.constant( sectionData.defaults['text-item'] ) );
				var itemCollection = this.model.get( 'columns' );

				_.each( items, function( itemAttrs ) {
					var itemModel = make.factory.model( itemAttrs );
					itemModel.parentModel = this.model;
					itemCollection.add( itemModel );
				}, this );
			}

			$stage.removeClass( function( i, className ) {
				return className.match( /ttfmake-text-columns-[0-9]/g || [] ).join( ' ' );
			});

			$stage.addClass( 'ttfmake-text-columns-' + newColumnCount );
		},

		onItemModelAdded: function( itemModel, itemCollection, options ) {
			var itemView = make.factory.view( { model: itemModel } );

			if ( itemView ) {
				var itemIndex = itemCollection.indexOf( itemModel );
				var itemViewModel = new Backbone.Model( { id: itemModel.id, view: itemView } );
				this.itemViews.add( itemViewModel, _.extend( options, { at: itemIndex } ) );
			}
		},

		onItemModelRemoved: function( itemModel ) {
			var itemViewModel = this.itemViews.get( itemModel.id );
			this.itemViews.remove( itemViewModel );
		},

		onItemModelsSorted: function( itemCollection ) {
			this.itemViews.reset( _.map( itemCollection.pluck( 'id' ), function( id ) {
				return this.itemViews.get( id );
			}, this ) );
		},

		onItemCollectionChanged: function() {
			this.model.trigger( 'change' );
		},

		onItemViewAdded: function( itemViewModel, itemViewCollection, options ) {
			var itemViewIndex = this.itemViews.indexOf( itemViewModel );
			var $itemViewEl = itemViewModel.get( 'view' ).render().$el;

			if ( 0 === itemViewIndex ) {
				$( '.ttfmake-text-columns-stage', this.$el ).prepend( $itemViewEl );
			} else {
				var previousItemViewModel = this.itemViews.at( itemViewIndex - 1 );
				previousItemViewModel.get( 'view' ).$el.after( $itemViewEl );
			}

			itemViewModel.get( 'view' ).trigger( 'rendered' );

			if ( options.scroll ) {
				window.make.builder.scrollToView( itemViewModel.get( 'view' ) );
			}
		},

		onItemViewRemoved: function( itemViewModel ) {
			itemViewModel.get( 'view' ).$el.animate( {
				opacity: 'toggle',
				height: 'toggle'
			}, builderSettings.closeSpeed, function() {
				itemViewModel.get( 'view' ).remove();
			} );
		},

		onItemViewsSorted: function( itemViewCollection ) {
			var $stage = $( '.ttfmake-text-columns-stage', this.$el );

			itemViewCollection
				.toArray()
				.reverse()
				.forEach( function( itemViewModel ) {
				var $itemViewEl = itemViewModel.get( 'view' ).$el;
				$itemViewEl.detach();
				$stage.prepend( $itemViewEl );
			}, this );
		},

		initSortables: function() {
			var $sortable = $( '.ttfmake-text-columns-stage', this.$el );

			$sortable.sortable( {
				handle: '.ttfmake-sortable-handle',
				placeholder: 'sortable-placeholder',
				items: '.ttfmake-text-column',
				forcePlaceholderSizeType: true,
				distance: 2,
				zIndex: 99999,
				tolerance: 'pointer',

				start: function ( e, ui ) {
					this.trigger( 'sort-start', e, ui );
				}.bind( this ),

				stop: function ( e, ui ) {
					this.trigger( 'sort-stop', e, ui );
				}.bind( this ),
			} );
		},

		onItemSortStart: function( e, ui ) {
			ui.placeholder.height( ui.item.height() );
			ui.placeholder.css( 'padding', ui.item.css( 'padding' ) );
			ui.placeholder.css( 'margin-bottom', ui.item.css( 'margin-bottom' ) );
		},

		onItemSortStop: function( e, ui ) {
			var $sortable = $( '.ttfmake-text-columns-stage', this.$el );
			var ids = $sortable.sortable( 'toArray', { attribute: 'data-id' } );

			this.model.get( 'columns' ).reset( _.map( ids, function( id ) {
				return this.model.get( 'columns' ).get( id );
			}, this ) );
		},

		onAddItemClick: function( e ) {
			e.preventDefault();

			var itemModel = make.factory.model( sectionData.defaults['text-item'] );
			itemModel.parentModel = this.model;
			this.model.get( 'columns' ).add( itemModel, { scroll: true } );
		},

		onConfigureSectionClick: function( e ) {
			e.preventDefault();

			var sectionType = this.model.get( 'section-type' );
			var sectionSettings = sectionData.settings[ sectionType ];

			if ( sectionSettings ) {
				window.make.overlay = new window.make.overlays.configuration( {
					model: this.model,
					buttonLabel: 'Update content settings'
				}, sectionSettings );
				window.make.overlay.open();
			}
		},
	} );

	var ItemView = make.classes.SectionItemView.extend( {
		template: wp.template( 'ttfmake-section-text-item' ),

		events: _.extend( {}, make.classes.SectionItemView.prototype.events, {
			'click .ttfmake-text-column-remove': 'onRemoveItemClick',
			'click .edit-content-link': 'onEditItemContentClick',
		} ),

		initialize: function() {
			make.classes.SectionItemView.prototype.initialize.apply( this, arguments );
		},

		afterRender: function() {
			make.classes.SectionItemView.prototype.afterRender.apply( this, arguments );

			this.listenTo( this.model, 'change:content', this.onItemContentChanged );

			var iframe = $( 'iframe', this.$el ).get( 0 );
			make.utils.initFrame( iframe );
			make.utils.setFrameContent( iframe, this.model.get( 'content' ) );
			this.refreshContentHeight();
		},

		onEditItemContentClick: function( e ) {
			e.preventDefault();

			window.make.overlay = new window.make.overlays.content( {
				model: this.model,
				field: 'content',
				buttonLabel: 'Update column'
			} );
			window.make.overlay.open();

			var backgroundColor = this.model.parentModel.get( 'background-color' );

			if (backgroundColor) {
				window.make.overlay.setStyle( { backgroundColor: backgroundColor } );
			}
		},

		onRemoveItemClick: function( e ) {
			e.preventDefault();

			if ( ! confirm( 'Are you sure you want to trash this column permanently?' ) ) {
				return;
			}

			this.model.collection.remove( this.model );
		},

		onItemContentChanged: function() {
			var $iframe = $( 'iframe', this.$el );
			make.utils.setFrameContent( $iframe.get( 0 ), this.model.get( 'content' ) );
			this.refreshContentHeight();
		},

		refreshContentHeight: function() {
			var $iframe = $( 'iframe', this.$el );

			if ( '' !== this.model.get( 'content' ) ) {
				$( '.ttfmake-iframe-content-placeholder', this.$el ).removeClass( 'show' );
				var iframeHeight = Math.min( Math.max( $iframe.contents().innerHeight(), 300 ), 500 );
				$iframe.innerHeight( iframeHeight );
			} else {
				$( '.ttfmake-iframe-content-placeholder', this.$el ).addClass( 'show' );
				$iframe.innerHeight( 300 );
			}
		}
	} );

	make.factory.model = _.wrap( make.factory.model, function( func, attrs, BaseClass ) {
		switch ( attrs[ 'section-type' ] ) {
			case 'text': BaseClass = Model; break;
			case 'text-item': BaseClass = ItemModel; break;
		}

		return func( attrs, BaseClass );
	} );

	make.factory.view = _.wrap( make.factory.view, function( func, options, BaseClass ) {
		switch ( options.model.get( 'section-type' ) ) {
			case 'text': BaseClass = View; break;
			case 'text-item': BaseClass = ItemView; break;
		}

		return func( options, BaseClass );
	} );

} ) ( jQuery, _, Backbone, ttfmakeBuilderSettings, ttfMakeSections );
