<?php
/**
 * Provides a bank of URLs associated with all three levels of plugin development.
 * Accepts a parameter for each of its variable grabs and returns the proper URL
 * for each acceptable parameter.
 *
 * @link       www.geoplatform.gov
 * @since      1.0.0
 *
 */

class Geop_url_bank{

  // The enviromnetal URL variables are declared.
  private $geopmap_sit_ual_url;
	private $geopmap_sit_maps_url;
	private $geopmap_sit_viewer_url;
	private $geopmap_sit_oe_url;
  private $geopmap_stg_ual_url;
	private $geopmap_stg_maps_url;
	private $geopmap_stg_viewer_url;
	private $geopmap_stg_oe_url;
  private $geopmap_prd_ual_url;
	private $geopmap_prd_maps_url;
	private $geopmap_prd_viewer_url;
	private $geopmap_prd_oe_url;

  // Constructor, where the URL variables are assigned values.
  function __construct(){
    $this->geopmap_sit_ual_url = "https://sit-ual.geoplatform.us";
    $this->geopmap_sit_maps_url = "https://sit-maps.geoplatform.us";
    $this->geopmap_sit_viewer_url = "https://sit-viewer.geoplatform.us";
    $this->geopmap_sit_oe_url = "https://sit-oe.geoplatform.us";
    $this->geopmap_stg_ual_url = "https://stg-ual.geoplatform.gov";
    $this->geopmap_stg_maps_url = "https://stg-maps.geoplatform.gov";
    $this->geopmap_stg_viewer_url = "https://stg-viewer.geoplatform.gov";
    $this->geopmap_stg_oe_url = "https://stg-oe.geoplatform.gov";
    $this->geopmap_prd_ual_url = "https://ual.geoplatform.gov";
    $this->geopmap_prd_maps_url = "https://maps.geoplatform.gov";
    $this->geopmap_prd_viewer_url = "https://viewer.geoplatform.gov";
    $this->geopmap_prd_oe_url = "https://oe.geoplatform.gov";
  }

  // ual_url accessor. Screens $param for sit or stg equivilent values and
  // returns the proper variable for that request. All other parameter values
  // return the prd value.
  function geop_maps_get_ual_url($param){
    switch ($param){
      case "dev":
        return $this->geopmap_sit_ual_url;
				break;
      case "stg":
        return $this->geopmap_stg_ual_url;
				break;
      default:
        return $this->geopmap_prd_ual_url;
				break;
    }
  }

  // viewer_url accessor. Screens $param for sit or stg equivilent values and
  // returns the proper variable for that request. All other parameter values
  // return the prd value.
  function geop_maps_get_viewer_url($param){
    switch ($param){
      case "dev":
        return $this->geopmap_sit_viewer_url;
				break;
      case "stg":
        return $this->geopmap_stg_viewer_url;
				break;
      default:
        return $this->geopmap_prd_viewer_url;
				break;
    }
  }

  // maps_url accessor. Screens $param for sit or stg equivilent values and
  // returns the proper variable for that request. All other parameter values
  // return the prd value.
  function geop_maps_get_maps_url($param){
    switch ($param){
      case "dev":
        return $this->geopmap_sit_maps_url;
				break;
      case "stg":
        return $this->geopmap_stg_maps_url;
				break;
      default:
        return $this->geopmap_prd_maps_url;
				break;
    }
  }

  // oe_url accessor. Screens $param for sit or stg equivilent values and returns
  // the proper variable for that request. All other parameter values return the
  // prd value.
  function geop_maps_get_oe_url($param){
    switch ($param){
      case "dev":
        return $this->geopmap_sit_oe_url;
				break;
      case "stg":
        return $this->geopmap_stg_oe_url;
				break;
      default:
        return $this->geopmap_prd_oe_url;
				break;
    }
  }
}
?>
