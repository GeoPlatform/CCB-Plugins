<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<br />


  <?php if ( has_post_thumbnail() ) {
    the_post_thumbnail();
  } ?>

	<?php the_content(); ?>

<!-- the rest of the content -->
<h5><?php the_tags();?></h5>
<h5 class="blog-post-meta">Updated <?php the_modified_date(); ?> by <a href="#"><?php the_author(); ?></a></h5>

</article><!-- post-the_ID();-->
