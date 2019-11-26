<?php
$geopportal_community_disp_thumb = get_template_directory_uri() . '/img/default-featured.jpg';
?>

<div class="m-article">
    <div class="m-article__heading">
        Explore <?php echo the_title() ?> Resources
    </div>

    <br>

    <div class="carousel slide" data-ride="carousel" data-interval="false" id="geopportal_community_anchor_carousel">

        <ol class="carousel-indicators">
            <li data-target="#geopportal_community_anchor_carousel" data-slide-to="0" class="active" title="Data">Data</li>
            <li data-target="#geopportal_community_anchor_carousel" data-slide-to="1" title="Data Services">Services</li>
            <li data-target="#geopportal_community_anchor_carousel" data-slide-to="2" title="Open Maps">Maps</li>
            <li data-target="#geopportal_community_anchor_carousel" data-slide-to="3" title="Layers">Layers</li>
            <li data-target="#geopportal_community_anchor_carousel" data-slide-to="4" title="Galleries">Galleries</li>
        </ol>

        <div class="carousel-inner">

            <div class="carousel-item active">
                <div class="m-article">
                    <div class="m-article__heading u-text--sm">Recent Datasets</div>
                    <div class="m-article__desc">
                        <div class="d-grid d-grid--3-col--lg">
                          <?php
                          for ($i = 0; $i < 6; $i++){?>
                            <div class="m-tile m-tile--16x9">
                                <div class="m-tile__thumbnail">
                                    <img alt="This is alternative text for the thumbnail" src="<?php echo $geopportal_community_disp_thumb ?>">
                                </div>
                                <div class="m-tile__body">
                                    <a href="/secondary.html" class="m-tile__heading">Dataset Label</a>
                                    <div class="m-tile__timestamp">Jan 1, 2018 by Joe User</div>
                                </div>
                            </div>
                          <?php } ?>
                        </div>
                        <div class="u-mg-top--xlg d-flex flex-justify-between flex-align-center">
                            <form class="input-group-slick flex-1 geopportal_port_community_search_form" grabs-from="geopportal_community_dataset_search">
                                <span class="icon fas fa-search"></span>
                                <input type="text" class="form-control" id="geopportal_community_dataset_search"
                                    query-prefix="/#/?communities=<?php echo $post->geopportal_compost_community_id ?>&types=dcat:Dataset&q="
                                    aria-label="Search for associated datasets"
                                    placeholder="Search for associated datasets">
                            </form>
                            <button class="u-mg-left--lg btn btn-secondary geopportal_port_community_search_button" grabs-from="geopportal_community_dataset_search">SEARCH DATASETS</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="carousel-item">
                <div class="m-article">
                    <div class="m-article__heading">Recent Services</div>
                    <div class="m-article__desc">
                        <div class="d-grid d-grid--3-col--lg">
                          <?php
                          for ($i = 0; $i < 6; $i++){?>
                            <div class="m-tile m-tile--16x9">
                                <div class="m-tile__thumbnail">
                                    <img alt="This is alternative text for the thumbnail" src="<?php echo $geopportal_community_disp_thumb ?>">
                                </div>
                                <div class="m-tile__body">
                                    <a href="/secondary.html" class="m-tile__heading">Service Label</a>
                                    <div class="m-tile__timestamp">Jan 1, 2018 by Joe User</div>
                                </div>
                            </div>
                          <?php } ?>
                        </div>
                        <div class="u-mg-top--xlg d-flex flex-justify-between flex-align-center">
                            <form class="input-group-slick flex-1 geopportal_port_community_search_form" grabs-from="geopportal_community_services_search">
                                <span class="icon fas fa-search"></span>
                                <input type="text" class="form-control" id="geopportal_community_services_search"
                                    query-prefix="/#/?communities=<?php echo $post->geopportal_compost_community_id ?>&types=regp:Service&q="
                                    aria-label="Search for associated services"
                                    placeholder="Search for associated services">
                            </form>
                            <button class="u-mg-left--lg btn btn-secondary geopportal_port_community_search_button" grabs-from="geopportal_community_services_search">SEARCH SERVICES</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="carousel-item">
                <div class="m-article">
                    <div class="m-article__heading">Recent Maps</div>
                    <div class="m-article__desc">
                        <div class="d-grid d-grid--3-col--lg">
                          <?php
                          for ($i = 0; $i < 6; $i++){?>
                            <div class="m-tile m-tile--16x9">
                                <div class="m-tile__thumbnail">
                                    <img alt="This is alternative text for the thumbnail" src="<?php echo $geopportal_community_disp_thumb ?>">
                                </div>
                                <div class="m-tile__body">
                                    <a href="/secondary.html" class="m-tile__heading">Map Label</a>
                                    <div class="m-tile__timestamp">Jan 1, 2018 by Joe User</div>
                                </div>
                            </div>
                          <?php } ?>
                        </div>
                        <div class="u-mg-top--xlg d-flex flex-justify-between flex-align-center">
                            <form class="input-group-slick flex-1 geopportal_port_community_search_form" grabs-from="geopportal_community_maps_search">
                                <span class="icon fas fa-search"></span>
                                <input type="text" class="form-control" id="geopportal_community_maps_search"
                                    query-prefix="/#/?communities=<?php echo $post->geopportal_compost_community_id ?>&types=Map&q="
                                    aria-label="Search for associated maps"
                                    placeholder="Search for associated maps">
                            </form>
                            <button class="u-mg-left--lg btn btn-secondary geopportal_port_community_search_button" grabs-from="geopportal_community_maps_search">SEARCH MAPS</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="carousel-item">
                <div class="m-article">
                    <div class="m-article__heading">Recent Layers</div>
                    <div class="m-article__desc">
                        <div class="d-grid d-grid--3-col--lg">
                          <?php
                          for ($i = 0; $i < 6; $i++){?>
                            <div class="m-tile m-tile--16x9">
                                <div class="m-tile__thumbnail">
                                    <img alt="This is alternative text for the thumbnail" src="<?php echo $geopportal_community_disp_thumb ?>">
                                </div>
                                <div class="m-tile__body">
                                    <a href="/secondary.html" class="m-tile__heading">Layer Label</a>
                                    <div class="m-tile__timestamp">Jan 1, 2018 by Joe User</div>
                                </div>
                            </div>
                          <?php } ?>
                        </div>
                        <div class="u-mg-top--xlg d-flex flex-justify-between flex-align-center">
                            <form class="input-group-slick flex-1 geopportal_port_community_search_form" grabs-from="geopportal_community_layers_search">
                                <span class="icon fas fa-search"></span>
                                <input type="text" class="form-control" id="geopportal_community_layers_search"
                                    query-prefix="/#/?communities=<?php echo $post->geopportal_compost_community_id ?>&types=Layer&q="
                                    aria-label="Search for associated layers"
                                    placeholder="Search for associated layers">
                            </form>
                            <button class="u-mg-left--lg btn btn-secondary geopportal_port_community_search_button" grabs-from="geopportal_community_layers_search">SEARCH LAYERS</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="carousel-item">
                <div class="m-article">
                    <div class="m-article__heading">Galleries</div>
                    <div class="m-article__desc">
                        <div class="d-grid d-grid--3-col--lg">
                          <?php
                          for ($i = 0; $i < 6; $i++){?>
                            <div class="m-tile m-tile--16x9">
                                <div class="m-tile__thumbnail">
                                    <img alt="This is alternative text for the thumbnail" src="<?php echo $geopportal_community_disp_thumb ?>">
                                </div>
                                <div class="m-tile__body">
                                    <a href="/secondary.html" class="m-tile__heading">Gallery Label</a>
                                    <div class="m-tile__timestamp">Jan 1, 2018 by Joe User</div>
                                </div>
                            </div>
                          <?php } ?>
                        </div>
                        <div class="u-mg-top--xlg d-flex flex-justify-between flex-align-center">
                            <form class="input-group-slick flex-1 geopportal_port_community_search_form" grabs-from="geopportal_community_galleries_search">
                                <span class="icon fas fa-search"></span>
                                <input type="text" class="form-control" id="geopportal_community_galleries_search"
                                    query-prefix="/#/?communities=<?php echo $post->geopportal_compost_community_id ?>&types=Gallery&q="
                                    aria-label="Search for associated galleries"
                                    placeholder="Search for associated galleries">
                            </form>
                            <button class="u-mg-left--lg btn btn-secondary geopportal_port_community_search_button" grabs-from="geopportal_community_galleries_search">SEARCH GALLERIES</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
  jQuery(document).ready(function() {

    jQuery(".geopportal_port_community_search_button").click(function(event){
      var geopportal_grabs_from = jQuery(this).attr("grabs-from");
      var geopportal_query_string = jQuery("#" + geopportal_grabs_from).attr("query-prefix") + jQuery("#" + geopportal_grabs_from).val();
      window.location.href="<?php echo home_url(get_theme_mod('headlink_search'))?>" + geopportal_query_string;
    });

    jQuery( ".geopportal_port_community_search_form" ).submit(function(event){
      event.preventDefault();
      var geopportal_grabs_from = jQuery(this).attr("grabs-from");
      var geopportal_query_string = jQuery("#" + geopportal_grabs_from).attr("query-prefix") + jQuery("#" + geopportal_grabs_from).val();
      window.location.href="<?php echo home_url(get_theme_mod('headlink_search'))?>" + geopportal_query_string;
    });
  });
</script>
