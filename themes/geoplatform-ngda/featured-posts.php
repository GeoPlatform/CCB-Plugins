<div class="apps-and-services section--linked">

    <!-- top directional lines and section heading -->
    <h4 class="heading">
        <div class="line"></div>
        <div class="line-arrow"></div>
        <div class="title darkened">Featured Posts</div>
    </h4>

    <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">

            <br>
              <div class="row">

                <?php if ( have_posts() ) : while ( have_posts() ) : the_post();

              get_template_part( 'post-card', get_post_format() );

            endwhile; endif;?>

            </div><!--/row -->
          </div><!--col-lg-12-->
        </div><!--#row-->
      <br>
    </div><!--#container-fluid-->

    <!-- bottom directional lines -->
    <div class="footing">
        <div class="line-cap"></div>
        <div class="line"></div>
    </div><!--#footing-->
</div><!--#apps-and-services section-linked-->
