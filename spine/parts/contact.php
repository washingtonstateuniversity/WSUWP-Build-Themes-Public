<div id="contact-details" itemscope itemtype="http://schema.org/Organization">
	<meta itemprop="name" class="required" content="<?php echo esc_attr( spine_get_option( 'contact_name' ) ); ?>">
	<meta itemprop="department" class="required" content="<?php echo esc_attr( spine_get_option( 'contact_department' ) ); ?>">
	<meta itemprop="url" class="required" content="<?php echo esc_attr( spine_get_option( 'contact_url' ) ); ?>">
	<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
		<meta itemprop="streetAddress" class="optional" content="<?php echo esc_attr( spine_get_option( 'contact_streetAddress' ) ); ?>">
		<meta itemprop="addressLocality" class="optional" content="<?php echo esc_attr( spine_get_option( 'contact_addressLocality' ) ); ?>">
		<meta itemprop="postalCode" class="required" content="<?php echo esc_attr( spine_get_option( 'contact_postalCode' ) ); ?>">
	</div>
	<meta itemprop="telephone" class="required" content="<?php echo esc_attr( spine_get_option( 'contact_telephone' ) ); ?>">
	<meta itemprop="email" class="required" content="<?php echo esc_attr( spine_get_option( 'contact_email' ) ); ?>">
	<?php
		$contact_point = spine_get_option( 'contact_ContactPoint' );
		if ( ! empty( $contact_point ) ) {
			?><meta itemprop="ContactPoint" title="<?php echo esc_attr( spine_get_option( 'contact_ContactPointTitle' ) ); ?>" class="optional" content="<?php echo esc_attr( $contact_point ); ?>"><?php
		}
	?>
</div>