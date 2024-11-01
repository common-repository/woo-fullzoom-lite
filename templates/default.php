<div class="woofz-wrap" id="woofz-wrap">
	<figure class="woofz-product-gallery">
		<img src="<?php echo $thumbnail_medium_url; ?>" alt="<?php echo $product->get_title(); ?>" title="<?php echo $product->get_title(); ?>" data-img="<?php echo $thumbnail_large_url; ?>" class="woofz-product-image" id="woofz-product-image">
		<figcaption id="woofz-thumbnails">
			<img src="<?php echo $thumbnail_small_url; ?>" alt="<?php echo $product->get_title(); ?>" title="<?php echo $product->get_title(); ?>" data-img="<?php echo $thumbnail_medium_url; ?>" data-src="<?php echo $thumbnail_large_url; ?>" class="active">
			<?php
			foreach( $attachment_ids as $attachment_id ) {
				$image_small_url = wp_get_attachment_image_url( $attachment_id, 'shop_thumbnail' );
				$image_medium_url = wp_get_attachment_image_url( $attachment_id, 'shop_catalog' );
				$image_large_url = wp_get_attachment_image_url( $attachment_id, 'full' );
				?>
				<img src="<?php echo $image_small_url; ?>" alt="<?php echo $product->get_title(); ?>" title="<?php echo $product->get_title(); ?>" data-img="<?php echo $image_medium_url; ?>" data-src="<?php echo $image_large_url; ?>">
				<?php
			}
			?>
		</figcaption>
	</figure>
</div>