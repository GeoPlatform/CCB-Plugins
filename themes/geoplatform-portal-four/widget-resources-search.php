<?php
class Geopportal_Resource_Search_Widget extends WP_Widget {

  // Constructor. Simple.
	function __construct() {
		parent::__construct(
			'geopportal_resource_search_widget', // Base ID
			esc_html__( 'GeoPlatform Resource Search', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform Search widget for resource pages. Redirects input to the Search interface, while also providing optional asset type filtering.', 'geoplatform-ccb' ), 'customize_selective_refresh' => true) // Args
		);
	}

  // Handles the widget output.
	public function widget( $args, $instance ) {

    // Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
		if (array_key_exists('geopportal_resource_search_title', $instance) && isset($instance['geopportal_resource_search_title']) && !empty($instance['geopportal_resource_search_title']))
      $geopportal_resource_search_title = apply_filters('widget_title', $instance['geopportal_resource_search_title']);
		else
      $geopportal_resource_search_title = "asset";
		if (array_key_exists('geopportal_resource_search_criteria', $instance) && isset($instance['geopportal_resource_search_criteria']) && !empty($instance['geopportal_resource_search_criteria']))
      $geopportal_resource_search_criteria = apply_filters('widget_title', $instance['geopportal_resource_search_criteria']);
		else
      $geopportal_resource_search_criteria = "";

    //Vets input to determine search prefix.
		$geopportal_resource_search_prefix = "/#/?q=";
		if ( empty($geopportal_resource_search_criteria) ){
			$geopportal_test_value = strtolower($geopportal_resource_search_title);
			if ($geopportal_test_value == "dataset" || $geopportal_test_value == "datasets")
				$geopportal_resource_search_prefix = "/#/?types=dcat:Dataset&q=";
			elseif ($geopportal_test_value == "service" || $geopportal_test_value == "services")
				$geopportal_resource_search_prefix = "/#/?types=regp:Service&q=";
			elseif ($geopportal_test_value == "layer" || $geopportal_test_value == "layers")
				$geopportal_resource_search_prefix = "/#/?types=Layer&q=";
			elseif ($geopportal_test_value == "map" || $geopportal_test_value == "maps")
				$geopportal_resource_search_prefix = "/#/?types=Map&q=";
			elseif ($geopportal_test_value == "gallery" || $geopportal_test_value == "galleries")
				$geopportal_resource_search_prefix = "/#/?types=Gallery&q=";
			elseif ($geopportal_test_value == "community" || $geopportal_test_value == "communities")
				$geopportal_resource_search_prefix = "/#/?types=Community&q=";
			elseif ($geopportal_test_value == "organization" || $geopportal_test_value == "organizations")
				$geopportal_resource_search_prefix = "/#/?types=org:Organization&q=";
			elseif ($geopportal_test_value == "contact" || $geopportal_test_value == "contacts")
				$geopportal_resource_search_prefix = "/#/?types=vcard:VCard&q=";
			elseif ($geopportal_test_value == "person" || $geopportal_test_value == "people")
				$geopportal_resource_search_prefix = "/#/?types=foaf:Person&q=";
			elseif ($geopportal_test_value == "application" || $geopportal_test_value == "applications")
				$geopportal_resource_search_prefix = "/#/?types=Application&q=";
			elseif ($geopportal_test_value == "topic" || $geopportal_test_value == "topics")
				$geopportal_resource_search_prefix = "/#/?types=Topic&q=";
			elseif ($geopportal_test_value == "website" || $geopportal_test_value == "websites")
				$geopportal_resource_search_prefix = "/#/?types=WebSite&q=";
			elseif ($geopportal_test_value == "concept" || $geopportal_test_value == "concepts")
				$geopportal_resource_search_prefix = "/#/?types=skos:Concept&q=";
			elseif ($geopportal_test_value == "conceptscheme" || $geopportal_test_value == "conceptschemes" || $geopportal_test_value == "concept scheme" || $geopportal_test_value == "concept schemes")
				$geopportal_resource_search_prefix = "/#/?types=skos:ConceptScheme&q=";
			elseif ($geopportal_test_value == "rightsstatement" || $geopportal_test_value == "rightsstatements" || $geopportal_test_value == "rights statement" || $geopportal_test_value == "rights statements")
				$geopportal_resource_search_prefix = "/#/?types=dct:RightsStatement&q=";
			else
				$geopportal_resource_search_prefix = "/#/?q=";
		}
		else {
			$geopportal_resource_search_prefix = "/#/?types=" . $geopportal_resource_search_criteria . "&q=";
		}
		?>

		<!--
		SEARCH
		-->
		<div class="m-section-group">

				<article class="m-article">
						<div class="m-article__heading">Find <?php _e(sanitize_text_field($geopportal_resource_search_title), 'geoplatform-ccb') ?></div>
						<div class="m-article__desc">
								<p>Enter a search phrase or just hit the search button and search for GeoPlatform Portfolio Assets and Site Content.  To learn more about searching, please visit the <a href="<?php echo home_url('help/apps/search/'); ?>">Search Help page</a>.</p>
						</div>
						<div class="article__actions">
								<div class="flex-1 d-flex flex-justify-between flex-align-center">
										<form class="input-group-slick flex-1" id="geopportal_resource_search_form">
												<span class="icon fas fa-search"></span>
												<input type="text" class="form-control"  id="geopportal_resource_search_input"
														aria-label="Search GeoPlatform <?php _e(sanitize_text_field(ucwords($geopportal_resource_search_title)), 'geoplatform-ccb') ?>"
														placeholder="Search GeoPlatform <?php _e(sanitize_text_field(ucwords($geopportal_resource_search_title)), 'geoplatform-ccb') ?>">
										</form>
										<button type="button" class="btn btn-secondary" id="geopportal_resource_search_button">SEARCH</button>
								</div>&nbsp;&nbsp;
						</div>
				</article>
		</div>

		<script type="text/javascript">
			jQuery(document).ready(function() {

				jQuery("#geopportal_resource_search_button").click(function(event){
					var geopportal_query_string = "<?php echo $geopportal_resource_search_prefix ?>" + jQuery("#geopportal_resource_search_input").val();
					window.open(
						"<?php echo home_url(get_theme_mod('headlink_search'))?>" + geopportal_query_string,
						'_blank'
					);
				});

				jQuery("#geopportal_resource_search_form").submit(function(event){
					event.preventDefault();
					var geopportal_query_string = "<?php echo $geopportal_resource_search_prefix ?>" + jQuery("#geopportal_resource_search_input").val();
					window.open(
						"<?php echo home_url(get_theme_mod('headlink_search'))?>" + geopportal_query_string,
						'_blank'
					);
				});
			});
		</script>

    <?php
	}

  // The admin side of the widget.
	public function form( $instance ) {

    // Checks for entries in the widget admin boxes and provides defaults if empty.
    // $geopportal_resource_search_title = ! empty( $instance['geopportal_resource_search_title'] ) ? $instance['geopportal_resource_search_title'] : 'Features &amp; Announcements';
		$geopportal_resource_search_title = ! empty( $instance['geopportal_resource_search_title'] ) ? $instance['geopportal_resource_search_title'] : 'asset';
		$geopportal_resource_search_criteria = ! empty( $instance['geopportal_resource_search_criteria'] ) ? $instance['geopportal_resource_search_criteria'] : '';


    // HTML for the widget control box.
		echo "<p>";
			_e("Input an asset filter type. If it is not a valid type, it will not be properly filtered by the search function. Singular and plural entries are permitted, and all lower-case text use is suggested for proper output formatting.", 'geoplatform-ccb');
		echo "</p>";
		echo "<p>";
			_e("You may also optionally enter a type query key manually, dcat:Dataset or org:Organization for example. This will override the matching operation with the type name. You should only do this if you know the exact query key text.", 'geoplatform-ccb');
		echo "</p>";
		echo "<p>";
      echo "<label for='" . $this->get_field_id( 'geopportal_resource_search_title' ) . "'>Asset Type:</label>";
      echo "<input type='text' id='" . $this->get_field_id( 'geopportal_resource_search_title' ) . "' name='" . $this->get_field_name( 'geopportal_resource_search_title' ) . "' value='" . esc_attr( $geopportal_resource_search_title ) . "' />";
    echo "</p>";
		echo "<p>";
      echo "<label for='" . $this->get_field_id( 'geopportal_resource_search_criteria' ) . "'>Type Query Key:</label>";
      echo "<input type='text' id='" . $this->get_field_id( 'geopportal_resource_search_criteria' ) . "' name='" . $this->get_field_name( 'geopportal_resource_search_criteria' ) . "' value='" . esc_attr( $geopportal_resource_search_criteria ) . "' />";
    echo "</p>";
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance[ 'geopportal_resource_search_title' ] = strip_tags( $new_instance[ 'geopportal_resource_search_title' ] );
		$instance[ 'geopportal_resource_search_criteria' ] = strip_tags( $new_instance[ 'geopportal_resource_search_criteria' ] );

		return $instance;
	}
}

// Registers and enqueues the widget.
function geopportal_register_portal_resource_search_widget() {
		register_widget( 'Geopportal_Resource_Search_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_portal_resource_search_widget' );
