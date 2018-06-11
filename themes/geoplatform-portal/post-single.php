
<div class="blog-post">
	<br />

    <!-- Un-Comment this to show how many comments each post has
		https://www.taniarascia.com/wordpress-from-scratch-part-two/
    <a href="<?php comments_link(); ?>">
	<?php
	printf( _nx( 'One Comment', '%1$s Comments', get_comments_number(), 'comments title', 'textdomain' ), number_format_i18n( 						get_comments_number() ) ); ?>
</a>-->

	  <?php if ( has_post_thumbnail() ) {
	    the_post_thumbnail();
	  } ?>

<div class="row">
	<br />
	<?php the_content(); ?>
</div>

<!-- the rest of the content -->
<h5 class="blog-post-meta">Updated on <?php the_modified_date(); ?></h5>
</div><!-- /.blog-post -->
