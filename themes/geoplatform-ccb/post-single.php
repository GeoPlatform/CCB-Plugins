<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<br />


<div class="entry-content">
	<br />
	<?php the_content('Read more...'); ?>
</div>

<!-- the rest of the content -->
<h5><?php the_tags();?></h5>
<h5 class="blog-post-meta">Updated on <?php the_modified_date(); ?> by <a href="#"><?php the_author(); ?></a></h5>
</article><!-- post-the_ID();-->
