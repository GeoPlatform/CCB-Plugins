<div class="whatsNew section--linked">
  <div class="container-fluid">
    <div class="line"></div>
    <div class="line-arrow"></div>

    <div class="col-lg-10 col-lg-offset-1">
      <h4 class="heading text-centered">
        <div class="title darkened">
          Features & Announcements
        </div>
      </h4>
      <div class="row">
        <?php
         //set counter
          $postNum = 0;
          //only show the first two posts
          while ( have_posts() && ($postNum < 3)) : the_post();
              $postNum++;
              get_template_part( 'post-card', get_post_format() );
          endwhile;
          ?>
        <div class="clearfix"></div>
      </div><!--#row-->
      <div class="row">
        <nav>
          <ul class="pager">
            <li><?php next_posts_link( 'Previous' ); ?></li>
            <li><?php previous_posts_link( 'Next' ); ?></li>
          </ul>
        </nav>
      </div><!--#row-->
    </div><!--#col-lg-10 col-lg-offset-1-->
  </div><!--#container-fluid-->
  <br>
  <div class="footing">
      <div class="line-cap"></div>
      <div class="line"></div>
  </div><!--#footing-->
</div><!--#whatsNew section-linked-->
