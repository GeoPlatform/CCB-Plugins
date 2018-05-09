<?php

get_header();
get_template_part( 'mega-menu', get_post_format() );
get_template_part( 'single-banner', get_post_format() );

?>

<div class="container">

    <div class="row">
        <div class="col-md-8 col-sm-8">
          <h2>NGDA Meetings</h2>
<p>Details and information about upcoming and past NGDA meetings can be accessed using the drop down menu below. Information can include dates, locations, agendas, and notes for completed meetings.</p>
<p>Click here to see the 2017 Theme Lead Meeting List.</p>
        <br />
          <form>
          <select name="archive-dropdown" onchange="document.location.href=this.options[this.selectedIndex].value;">
            <option value=""><?php echo esc_attr( __( 'Select Meeting' ) ); ?></option>
            <?php wp_get_archives( array('type' => 'postbypost', 'post_type'=> 'ngda_meetings', 'format'=> 'option' ) ); ?>
          </select>
          <!-- <input type="submit" /> -->
            </form>

<!-- https://wp-types.com/documentation/customizing-sites-using-php/creating-templates-custom-post-type-archives/ -->
 <h3><?php the_title();?></h3>
<strong>
  Meeting Date: <?php echo types_render_field( "meetingdate" );  // Call to Types function for rendering a custom field "Meeting Date" ?>
<br />
  Meeting Time: <?php echo types_render_field( "meetingtime" ); ?>
<br />
  Phone: <?php echo types_render_field( "phone" ); ?>
<br />
  Passcode: <?php echo types_render_field( "passcode" ); ?>
  <br />
  Webinar: <a href="<?php echo types_render_field( "webinar" );  ?>"><?php echo types_render_field( "webinar" );  ?></a>
  <br />
  Presentaion Link:  <a href="<?php echo types_render_field( "presentation-link" );  ?>"><?php echo types_render_field( "presentation-link" ); ?></a>
  <br />
  Meeting Notes Link:  <a href="<?php echo types_render_field( "meeting-notes-link" );  ?>"><?php echo types_render_field( "meeting-notes-link" ); ?></a>
<br />
  In Person: <?php echo types_render_field( "inperson" );   ?>
<br />
  Other Information: <?php echo types_render_field( "otherinformation" );  ?>
<br />
  Agenda: <?php echo types_render_field( "agenda" );  ?>


</strong>
</div>
<div class="col-md-4 col-sm-4">
  <?php get_template_part( 'sidebar', get_post_format() ); ?>
</div>


</div>
</div>




<?php get_footer(); ?>
