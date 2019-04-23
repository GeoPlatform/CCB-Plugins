<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 */

get_header(); ?>

<ul class="m-page-breadcrumbs">
  <li><a href="<?php echo home_url() ?>/">Home</a></li>
  <li><a href="<?php echo home_url($wp->request); ?>">404</a></li>
</ul>

<div class="l-body l-body--one-column">

    <div class="l-body__main-column">

        <article class="m-article">
            <br><br>
            <span class="far fa-frown u-text--gargantuan"></span>
            <div class="m-article__heading">Page Not Found</div>
            <div class="m-article__desc">
                The requested page was not found. If you are sure the URL
                is correct, <a href="mailto:servicedesk@geoplatform.gov">Contact Us</a>
                to let us know about the problem.
            </div>
            <br><br>
        </article>

    </div>

</div>

<?php get_footer(); ?>
