( function( $, _, Backbone, builderSettings, sectionData ) {

	builderSettings = _.extend( builderSettings, {
		openSpeed : 400,
		closeSpeed: 250,
	} );

	var Factory = {
		model: function( attrs, BaseClass ) {
			return window.make.factory.extend( attrs, BaseClass );
		},

		view: function( options, BaseClass ) {
			return window.make.factory.extend( options, BaseClass );
		},

		extend: function( options, BaseClass ) {
			if ( BaseClass && options ) {
				return new BaseClass( options );
			}
		}
	};

	var sectionCollection = new Backbone.Collection();

	var Make = Backbone.View.extend( {
		initialize: function() {
			// Hide any open dropdowns
			$( 'body' ).on( 'click', function() {
				$( '.ttfmake-configure-item-button', this.$el ).removeClass( 'active' );
				$( '.configure-item-dropdown', this.$el ).hide();
			} );
		},

		load: function( sectionData ) {
			_.each( sectionData, function( attrs ) {
				var sectionModel = this.factory.model( attrs );

				if ( sectionModel ) {
					sectionCollection.add( sectionModel );
				}
			}, this );
		}
	} );

	var Menu = Backbone.View.extend( {
		events: {
			'click .ttfmake-menu-list-item-link': 'onSectionAdd',
		},

		onSectionAdd: function( e ) {
			e.preventDefault();

			var sectionType = $( e.currentTarget ).data( 'section' );
			var sectionModel = make.factory.model( { 'section-type': sectionType } );

			if ( sectionModel ) {
				sectionCollection.add( sectionModel, { scroll: true } );
			}
		}
	} );

	var Builder = Backbone.View.extend( {
		initialize: function() {
			this.sectionViews = new Backbone.Collection();

			this.listenTo( this.collection, 'add', this.onSectionModelAdded );
			this.listenTo( this.collection, 'remove', this.onSectionModelRemoved );
			this.listenTo( this.collection, 'reset', this.onSectionModelsSorted );
			this.listenTo( this.collection, 'add remove reset', this.onSectionCollectionChanged );
			this.listenTo( this.sectionViews, 'add', this.onSectionViewAdded );
			this.listenTo( this.sectionViews, 'remove', this.onSectionViewRemoved );
			this.listenTo( this.sectionViews, 'add remove', this.toggleClosedClass );

			this.initSortables();
			this.on( 'sort-start', this.onSectionSortStart, this );
			this.on( 'sort-stop', this.onSectionSortStop, this );
		},

		onSectionModelAdded: function( sectionModel, sectionCollection, options ) {
			var sectionView = make.factory.view( { model: sectionModel } );

			if ( sectionView ) {
				var sectionIndex = sectionCollection.indexOf( sectionModel );
				var sectionViewModel = new Backbone.Model( { id: sectionModel.id, view: sectionView } );
				this.sectionViews.add( sectionViewModel, _.extend( options, { at: sectionIndex } ) );
			}
		},

		onSectionModelRemoved: function( sectionModel, sectionCollection, options ) {
			var sectionViewModel = this.sectionViews.get( sectionModel.id );
			this.sectionViews.remove( sectionViewModel );
		},

		onSectionCollectionChanged: function( sectionModel ) {
			var $layoutInput = $( '#ttfmake-section-layout' );
			$layoutInput.val( JSON.stringify( this.collection.pluck( 'id' ) ) );
		},

		onSectionModelsSorted: function( sectionCollection ) {
			this.sectionViews.reset( _.map( sectionCollection.pluck( 'id' ), function( id ) {
				return this.sectionViews.get( id );
			}, this ) );
		},

		onSectionViewAdded: function( sectionViewModel, sectionViewCollection, options ) {
			var sectionViewIndex = this.sectionViews.indexOf( sectionViewModel );
			var $sectionViewEl = sectionViewModel.get( 'view' ).render().$el;

			if ( 0 === sectionViewIndex ) {
				this.$el.prepend( $sectionViewEl );
			} else {
				var previousSectionViewModel = this.sectionViews.at( sectionViewIndex - 1 );
				previousSectionViewModel.get( 'view' ).$el.after( $sectionViewEl );
			}

			sectionViewModel.get( 'view' ).trigger( 'rendered' );

			if ( options.scroll ) {
				this.scrollToView( sectionViewModel.get( 'view' ) )
			}
		},

		onSectionViewRemoved: function( sectionViewModel ) {
			sectionViewModel.get( 'view' ).remove();
		},

		initSortables: function () {
			this.$el.sortable( {
				handle: '.ttfmake-section-header',
				placeholder: 'sortable-placeholder',
				forcePlaceholderSizeType: true,
				distance: 2,
				tolerance: 'pointer',

				start: function ( e, ui ) {
					this.trigger( 'sort-start', e, ui );
				}.bind( this ),

				stop: function ( e, ui ) {
					this.trigger( 'sort-stop', e, ui );
				}.bind( this ),
			} );
		},

		onSectionSortStart: function( e, ui ) {
			ui.item.css( '-webkit-transform', 'translateZ(0)' );
			$( '.sortable-placeholder', this.$el ).height( parseInt( ui.item.height(), 10 ) - 2 );
		},

		onSectionSortStop: function( e, ui ) {
			ui.item.css( '-webkit-transform', '' );

			var ids = this.$el.sortable( 'toArray', { attribute: 'data-id' } );

			this.collection.reset( _.map( ids, function( id ) {
				return this.collection.get( id );
			}, this ) );
		},

		scrollToView: function ( view ) {
			$( 'html, body' ).animate( {
				// Offset + admin bar height + margin
				scrollTop: view.$el.offset().top - 32 - 9
			}, 800, 'easeOutQuad' );
		},

		toggleClosedClass: function() {
			if ( 0 === this.sectionViews.size() ) {
				this.$el.addClass('ttfmake-stage-closed');
			} else {
				this.$el.removeClass('ttfmake-stage-closed');
			}
		}
	} );

	var SectionView = Backbone.View.extend( {
		events: {
			'click .ttfmake-section-toggle': 'onToggleSectionClick',
			'click .ttfmake-section-remove': 'onRemoveSectionClick',
			'click .ttfmake-section-configure': 'onConfigureSectionClick'
		},

		initialize: function() {
			this.on( 'rendered', this.afterRender, this );
			this.listenTo( this.model, 'change', this.onSectionModelChanged );
		},

		render: function() {
			if ( this.template ) {
				this.setElement( this.template( this.model ) );
			}

			return this;
		},

		afterRender: function() {
			this.listenTo( this.model, 'change:title', this.updateTitle );
		},

		onSectionModelChanged: function() {
			var $sectionModelTextarea = $( '.ttfmake-section-json', this.$el );
			$sectionModelTextarea.val( JSON.stringify( this.model.toJSON() ) );
		},

		onToggleSectionClick: function ( e ) {
			e.preventDefault();

			var $sectionBody = $( '.ttfmake-section-body', this.$el );

			if ( 'closed' !== this.model.get( 'state' ) ) {
				$sectionBody.slideUp( builderSettings.closeSpeed, function() {
					this.$el.removeClass( 'ttfmake-section-open' );
					this.model.set( 'state', 'closed' );
				}.bind( this ) );
			} else {
				$sectionBody.slideDown( builderSettings.openSpeed, function() {
					this.$el.addClass( 'ttfmake-section-open' );
					this.model.set( 'state', 'open' );
				}.bind( this ) );
			}
		},

		onRemoveSectionClick: function ( e ) {
			e.preventDefault();

			if ( ! window.confirm( builderSettings.confirmString ) ) {
				return;
			}

			this.$el.animate( {
				opacity: 'toggle',
				height: 'toggle'
			}, builderSettings.closeSpeed, function() {
				this.model.collection.remove( this.model );
			}.bind( this ) );
		},

		onConfigureSectionClick: function( e ) {
			e.preventDefault();

			var sectionType = this.model.get( 'section-type' );
			var sectionSettings = sectionData.settings[ sectionType ];

			if ( sectionSettings ) {
				window.make.overlay = new window.make.overlays.configuration( { model: this.model }, sectionSettings );
				window.make.overlay.open();
			}
		},

		updateTitle: function() {
			if ( this.model.get( 'title' ) ) {
				$( '.ttfmake-section-header h3', this.$el ).addClass( 'has-title' );
			} else {
				$( '.ttfmake-section-header h3', this.$el ).removeClass( 'has-title' );
			}

			var $headerTitle = $( '.ttfmake-section-header-title', this.$el );
			$headerTitle.html( _.escape( this.model.get( 'title' ) ) );
		},

		unbindEvents: function() {
			// Unbind any listenTo handlers
			this.stopListening();
			// Unbind any delegated DOM handlers
			this.undelegateEvents()
			// Unbind any direct view handlers
			this.off();
		},

		remove: function() {
			this.unbindEvents();
			Backbone.View.prototype.remove.apply( this, arguments );
		},
	} );

	var SectionItemView = Backbone.View.extend( {
		events: {
			'click .ttfmake-configure-item-button': 'onConfigureDropdownClick',
		},

		initialize: function() {
			this.on( 'rendered', this.afterRender, this );
		},

		render: function() {
			if ( this.template ) {
				this.setElement( this.template( this.model ) );
			}

			return this;
		},

		afterRender: function() {
			// Noop
		},

		onConfigureDropdownClick: function( e ) {
			e.preventDefault();
			e.stopPropagation();

			this.toggleConfigureDropdown();
		},

		toggleConfigureDropdown: function() {
			$( '.configure-item-dropdown' ).hide();
			$( '.ttfmake-configure-item-button' ).removeClass( 'active' );
			var $cogLink = $( '.ttfmake-configure-item-button', this.$el ).first();

			if ( ! $cogLink.hasClass( 'ttfmake-configure-item-button' ) ) {
				return;
			}

			var $configureItemDropdown = $( '.configure-item-dropdown', this.$el ).first();
			$cogLink.toggleClass( 'active' );
			$configureItemDropdown.toggle();
		},

		unbindEvents: function() {
			// Unbind any listenTo handlers
			this.stopListening();
			// Unbind any delegated DOM handlers
			this.undelegateEvents()
			// Unbind any direct view handlers
			this.off();
		},

		remove: function() {
			this.unbindEvents();
			Backbone.View.prototype.remove.apply( this, arguments );
		},
	} );

	Utils = {
		frameHeadLinks: _.memoize( function() {
			var scripts = tinyMCEPreInit.mceInit.make_content_editor.content_css.split( ',' );
			var link = '';

			_.each( scripts, function( url ) {
				if ( url.indexOf( 'make-css' ) < 0 ) {
					link += '<link type="text/css" rel="stylesheet" href="' + url + '" />';
				}
			} );

			return link;
		} ),

		wrapShortcodes: function( content ) {
			// Render captions
			content = content.replace(
				/\[caption.*?\](\<img.*?\/\>)[ ]*(.*?)\[\/caption\]/g,
				'<div><dl class="wp-caption alignnone">'
				+ '<dt class="wp-caption-dt">$1</dt>'
				+ '<dd class="wp-caption-dd">$2</dd></dl></div>'
			);

			return content.replace( /^(<p>)?(\[.*\])(<\/p>)?$/gm, '<div class="shortcode-wrapper">$2</div>' );
		},

		initFrame: function( iframe ) {
			var iframeContent = iframe.contentDocument ? iframe.contentDocument : iframe.contentWindow.document;
			var $iframeHead = $( 'head', iframeContent );
			var headLinks = make.utils.frameHeadLinks();

			$iframeHead.html( headLinks );

			// Firefox hack
			// @link http://stackoverflow.com/a/24686535
			$( iframe ).on( 'load', function() {
				$( this ).contents().find( 'head' ).html( headLinks );
			} );
		},

		setFrameContent: function( iframe, content ) {
			var iframeContent = iframe.contentDocument ? iframe.contentDocument : iframe.contentWindow.document;
			var $iframeBody = $( 'body', iframeContent );

			content = switchEditors.wpautop( make.utils.wrapShortcodes( content ) );

			$iframeBody.html( content );

			$( iframe ).on( 'load', function() {
				$( this ).contents().find( 'body' ).html( content );
			} );
		}
	}

	window.make = new Make( { el: '#ttfmake-builder' } );
	window.make.factory = Factory;
	window.make.menu = new Menu( { el: '#ttfmake-menu' } );
	window.make.builder = new Builder( { el: '#ttfmake-stage', collection: sectionCollection } );
	window.make.classes = { SectionView: SectionView, SectionItemView: SectionItemView };
	window.make.utils = Utils;

	$( document ).ready( function() {
		window.make.load( sectionData.data );
	} );

} ) ( jQuery, _, Backbone, ttfmakeBuilderSettings, ttfMakeSections );
