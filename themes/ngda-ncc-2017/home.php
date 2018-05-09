<!--include header area-->
<?php

    get_header('ngda');
    get_template_part( 'mega-menu', get_post_format() );
    get_template_part( 'ngda_intro', get_post_format() );
    get_template_part( 'ncc-main-page', get_post_format() );
    //get_template_part( 'featured-posts', get_post_format() );

      ?>

<?php get_footer(); ?>
