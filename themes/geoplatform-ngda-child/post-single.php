<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<br />


<div class="entry-content">
	<br />
	<?php the_content(); ?>
</div>

<!-- the rest of the content -->
<h5><?php the_tags();?></h5>
<h5 class="blog-post-meta">Updated <?php the_date(); ?></h5>
</article><!-- post-the_ID();-->
