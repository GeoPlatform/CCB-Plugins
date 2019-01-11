<?php

get_header('ngda');
get_template_part( 'mega-menu', get_post_format() );
get_template_part( 'single-banner', get_post_format() );

?>

<div class="container">

    <div class="row">
        <div class="col-md-8 col-sm-8">
          <?php if ( have_posts() ) : while ( have_posts() ) : the_post();

        get_template_part( 'post-single', get_post_format() );

        //Un-comment the code below to show comments on the posts
        if ( comments_open() || get_comments_number() ) :
        	  comments_template();
            echo "</br>";
        	endif;
      endwhile; endif;
      ?>

      <?php
      //Paginate posts if the <!--nextpage--> <!--tag is added to the content
      wp_link_pages( array(
      	'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'geoplatform-ngda' ) . '</span>',
      	'after'       => '</div>',
      	'link_before' => '<span>',
      	'link_after'  => '</span>',
      	) );
        ?>
    </div>
    <div class="col-md-4 col-sm-4">
      <?php get_template_part( 'sidebar', get_post_format() ); ?>
    </div>


  </div>
</div>


<?php get_footer(); ?>
