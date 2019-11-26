<?php
/**
 * The template for Single post and page banners
 *
 * @link https://codex.wordpress.org/Sidebars
 *
 * @package GeoPlatform CCB
 *
 * @since 3.0.0
 */
?>
<div class="l-body__side-column">
<?php
  if ( is_active_sidebar( 'geoplatform-widgetized-page-sidebar' ) ) {
    dynamic_sidebar( 'geoplatform-widgetized-page-sidebar' );
  }
  else { ?>
    <article class="m-article">
      <div class="m-article__heading">Have Other Questions?</div>
      <div class="m-article__desc">
        Please check out our <a href="<?php echo home_url('faq') ?>">FAQ page</a> in case your
        question has already been addressed. If you still need help or want
        to report an issue, please send us an email at
        <a href="mailto:servicedesk@geoplatform.gov">servicedesk@geoplatform.gov</a>.
      </div>
      <div class="u-text--center t-text--strong u-mg-top--md u-mg-bottom--md">
        <a class="btn btn-info" href="mailto:servicedesk@geoplatform.gov">
          <span class="far fa-envelope"></span>
          Contact Us
        </a>
      </div>
      <div class="m-article__desc">
        For questions about the federal government not related to GeoPlatform, visit
        <a href="https://www.usa.gov">USA.gov</a> or call
        <a href="tel:18003334636">1-800-FED-INFO</a>
        (<em>1-800-333-4636</em>), 8am - 8pm ET Monday through Friday.
      </div>
    </article>

    <article class="m-article">
      <div class="m-article__heading">Side Content</div>
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
    </article>

    <article class="m-article">
      <div class="m-article__heading">Side Content</div>
      <div class="m-article__desc">
        This is an example of a quick (read: "short") list in the side bar.
      </div>
      <div class="m-article__desc m-list">
        <div class="m-list__item">
          <a class="is-linkless">This is a link</a>
          <div class="m-list__item__text">This is a brief blurb about the link</div>
        </div>
        <div class="m-list__item">
          <a class="is-linkless">This is a link</a>
          <div class="m-list__item__text">This is a brief blurb about the link</div>
        </div>
        <div class="m-list__item">
          <a class="is-linkless">This is a link</a>
          <div class="m-list__item__text">This is a brief blurb about the link</div>
        </div>
      </div>
    </article>

    <article class="m-article">
      <div class="m-article__heading">Side Content</div>
      <div class="m-article__desc">
        <img src="<?php echo get_template_directory_uri() . '/img/default-featured.jpg' ?>" title="An empty image">
          This is an example of side content featuring a thumbnail and a small-to-moderate
          amount of descriptive text along with it.
      </div>
    </article>
<?php  } ?>
</div>
