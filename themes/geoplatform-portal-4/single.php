<!-- STILL NEEDS UPDATE -->

<?php get_header(); ?>
<?php get_template_part( 'sub-header-post', get_post_format() ); ?>



    <div class="l-body l-body--two-column">


        <div class="l-body__main-column">


            <article class="m-article">
                <div class="m-article__heading">Section Heading</div>
                <div class="m-article__desc">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                    sed do eiusmod tempor incididunt ut labore et dolore magna
                    aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                    ullamco laboris nisi ut aliquip ex ea commodo consequat.
                    Duis aute irure dolor in reprehenderit in voluptate velit
                    esse cillum dolore eu fugiat nulla pariatur. Excepteur sint
                    occaecat cupidatat non proident, sunt in culpa qui officia
                    deserunt mollit anim id est laborum.
                </div>
                <div class="article__actions">
                    <a href="#">Action</a> &nbsp;
                    <a href="/secondary-b.html">Secondary B</a> &nbsp;
                    <a href="/secondary-c.html">Secondary C</a>
                </div>
            </article>





        </div>

<!-- Sidebar -->
        <div>
          <?php get_template_part( 'side-column', get_post_format() ); ?>
        </div>

    </div>
    <?php get_footer(); ?>
