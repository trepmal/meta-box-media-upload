jQuery( function ( $ ) {

	var file_frame = [],
		$button = $( '.meta-box-upload-button' ),
		$removebutton = $( '.meta-box-upload-button-remove' );

	$button.on( 'click', function ( event ) {

		event.preventDefault();

		var $this = $( this ),
			id = $this.attr( 'id' );

		// If the media frame already exists, reopen it.
		if ( file_frame[ id ] ) {
			file_frame[ id ].open();

			return;
		}

		// Create the media frame.
		file_frame[ id ] = wp.media.frames.file_frame = wp.media( {
			title    : $this.data( 'uploader_title' ),
			button   : {
				text : $this.data( 'uploader_button_text' )
			},
			multiple : false  // Set to true to allow multiple files to be selected
		} );

		// When an image is selected, run a callback.
		file_frame[ id ].on( 'select', function() {

			// We set multiple to false so only get one image from the uploader
			var attachment = file_frame[ id ].state().get( 'selection' ).first().toJSON();

			// set input
			$( '#' + id + '-value' ).val( attachment.id );

			// set preview
			var img = '<img src="' + attachment.url + '" style="max-width:100%;" />';

			$this.next( 'input' ).next( '.image-preview' ).html( img );

		} );

		// Finally, open the modal
		file_frame[ id ].open();

	} );

	$removebutton.on( 'click', function( event ) {

		event.preventDefault();

		var $this = $( this ),
			id = $this.prev( 'input' ).attr( 'id' );

		$this.next( '.image-preview' ).html( '' );

		// $this.next( 'br' ).next( 'img' ).remove();

		$( '#' + id + '-value' ).val( 0 );

	} );

} );
