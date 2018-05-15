<?php
/**
 * Template Name: Style Guide
 *
 *https://developer.wordpress.org/themes/template-files-section/page-templates/
 */
 ?>
 <?php get_header(); ?>
 <div class="content--sidebar-drawer">
        <nav class="style-menu">

    <div class="heading">
      <br/>

        <h4>
            <span class="icon-gp"></span>
            <a href="/">GeoPlatform</a>
        </h4>
    </div>
    <br class="hidden-xs hidden-sm">

    <div class="nav-section">
        <a href="/style">
            <span class="glyphicon glyphicon-home"></span>
            Style Guide Home
        </a>
    </div>

    <div class="nav-section">
        <div class="nav-section-heading">
            <span class="glyphicon glyphicon-th-large"></span> Basics
        </div>
        <a href="/style/best-practices">Best Practices</a>
        <a href="/style/bootstrap">Bootstrap</a>
        <a href="/style/typography">Typography</a>
        <a href="/style/sizing">Sizing</a>
        <a href="/style/colors">Colors</a>
        <a href="/style/icons">Icons</a>
        <a href="/style/shadows">Shadows</a>
    </div>

    <!--
    <div class="nav-section">
        <div class="nav-section-heading">
            <span class="glyphicon glyphicon-th-large"></span> Structure
        </div>
        <a href="/style/headings">Headings</a>
        <a href="/style/navigation">Navigation</a>
    </div>
    -->

    <div class="nav-section">
        <div class="nav-section-heading">
            <span class="glyphicon glyphicon-th-large"></span> Components
        </div>
        <a href="/style/navigation">Navigation</a>
        <a href="/style/buttons">Buttons</a>
        <a href="/style/headers">Headers</a>
        <a href="/style/cards">Cards</a>
        <a href="/style/featured">Featured Items</a>
        <a href="/style/results-grid">Search Results (Grid)</a>
        <a href="/style/lists">Lists</a>
        <a href="/style/search">Search</a>
        <a href="/style/forms-slick">Forms (Slick)</a>
        <a href="/style/forms-material">Forms (Material)</a>
        <a href="/style/pagination">Pagination</a>
        <a href="/style/sorting">Sorting</a>
    </div>

</nav>
    </div>
    <div class="content--main">

            <div class="heading">
                <div class="pull-left heading__nav">
                    <div class="btn-group">
                        <button type="button" class="btn btn-link btn-link-lite dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="icon-options u-vertical t-light"></span>
                        </button>
                        <div class="dropdown-menu">
                            <nav class="style-menu">

        <div class="heading">
            <h4>
                <span class="icon-gp"></span>
                <a href="/">GeoPlatform</a>
            </h4>
        </div>
        <br class="hidden-xs hidden-sm">

        <div class="nav-section">
            <a href="/style">
                <span class="glyphicon glyphicon-home"></span>
                Style Guide Home
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-heading">
                <span class="glyphicon glyphicon-th-large"></span> Basics
            </div>
            <a href="/style/best-practices">Best Practices</a>
            <a href="/style/bootstrap">Bootstrap</a>
            <a href="/style/typography">Typography</a>
            <a href="/style/sizing">Sizing</a>
            <a href="/style/colors">Colors</a>
            <a href="/style/icons">Icons</a>
            <a href="/style/shadows">Shadows</a>
        </div>

        <!--
        <div class="nav-section">
            <div class="nav-section-heading">
                <span class="glyphicon glyphicon-th-large"></span> Structure
            </div>
            <a href="/style/headings">Headings</a>
            <a href="/style/navigation">Navigation</a>
        </div>
        -->

        <div class="nav-section">
            <div class="nav-section-heading">
                <span class="glyphicon glyphicon-th-large"></span> Components
            </div>
            <a href="/style/navigation">Navigation</a>
            <a href="/style/buttons">Buttons</a>
            <a href="/style/headers">Headers</a>
            <a href="/style/cards">Cards</a>
            <a href="/style/featured">Featured Items</a>
            <a href="/style/results-grid">Search Results (Grid)</a>
            <a href="/style/lists">Lists</a>
            <a href="/style/search">Search</a>
            <a href="/style/forms-slick">Forms (Slick)</a>
            <a href="/style/forms-material">Forms (Material)</a>
            <a href="/style/pagination">Pagination</a>
            <a href="/style/sorting">Sorting</a>
        </div>

    </nav>
                        </div>
                    </div>
                </div>



            </div>

            <div class="body">

              <?php if ( have_posts() ) : while ( have_posts() ) : the_post();

               the_content(); ?>

            <!-- the rest of the content -->
            <h5 class="blog-post-meta">Updated <?php the_date(); ?> by <a href="#"><?php the_author(); ?></a></h5>
            <?php
          endwhile; endif;
          ?>

        </div>

           <?php get_footer(); ?>







        </div>
