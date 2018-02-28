<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<br />

<?php $name = get_post_meta($post->ID, 'featured_img_ExternalUrl', true);
if( $name ) { ?>
<a href="<?php echo $name; ?>" target="blank"><?php the_post_thumbnail('post-thumbnail', ['class' => 'img-responsive responsive--full', 'title'=>'Go to Map']); ?></a>
<?php } else {
the_post_thumbnail('post-thumbnail', ['class' => 'img-responsive responsive--full']);
} ?>


<div class="entry-content">
	<br />
	<?php the_content(); ?>
</div>

<!-- the rest of the content -->
<h5><?php the_tags();?></h5>
<h5 class="blog-post-meta">Updated on <?php the_date(); ?> by <a href="#"><?php the_author(); ?></a></h5>
</article><!-- post-the_ID();-->
