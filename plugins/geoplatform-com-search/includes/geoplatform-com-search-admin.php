<?php global $wp_settings_sections, $wp_settings_fields; 
$fields = $wp_settings_fields['gpsearch']['gpsearch_section'];
$community = $fields['gpsearch_select_community'];
$title = $fields['gpsearch_text_title'];
$objtype = $fields['gpsearch_select_objtype'];
$paging = $fields['gpsearch_checkbox_show_paging'];
$search = $fields['gpsearch_checkbox_show_search'];
$sort = $fields['gpsearch_select_sort'];
$perpage = $fields['gpsearch_select_perpage'];
?>

<div class='wrap gp-search-admin'>
  <h2>GeoPlatform Search Plugin</h2>
  <h4>These settings are the default values of the plugin when added to the page.  Any values are added via shortcode will take precedence.</h4>
  
  <form method='post' action='options.php'>
    <?php settings_fields('gpsearch'); ?>
    <div class='gp-search-form-container'>
      <div class='gp-search-form-container-row'>
        <div class='gp-section'>
           <div class='label'><label for="<?php echo esc_attr($community['args']['label_for']) ?>"><?php echo esc_attr($community['title']); ?></label></div>
           <div class='content'><?php call_user_func($community['callback'], $community['args']); ?></div>
        </div>
        <div class='gp-section'>
           <div class='label'><label for="<?php echo esc_attr($title['args']['label_for']) ?>"><?php echo esc_attr($title['title']); ?></label></div>
           <div class='content'><?php call_user_func($title['callback'], $title['args']); ?></div>
        </div>
        <div class='gp-section'>
           <div class='label label-long'><label for="<?php echo esc_attr($paging['args']['label_for']) ?>"><?php echo esc_attr($paging['title']); ?></label></div>
           <div class='content'><?php call_user_func($paging['callback'], $paging['args']); ?></div>
        </div>
      </div>
      <div class='gp-search-form-container-row'>
        <div class='gp-section'>
           <div class='label'><label for="<?php echo esc_attr($objtype['args']['label_for']) ?>"><?php echo esc_attr($objtype['title']); ?></label></div>
           <div class='content'><?php call_user_func($objtype['callback'], $objtype['args']); ?></div>
        </div>
        <div class='gp-section'>
           <div class='label'><label for="<?php echo esc_attr($sort['args']['label_for']) ?>"><?php echo esc_attr($sort['title']); ?></label></div>
           <div class='content'><?php call_user_func($sort['callback'], $sort['args']); ?></div>
        </div>
        <div class='gp-section'>
           <div class='label label-long'><label for="<?php echo esc_attr($search['args']['label_for']) ?>"><?php echo esc_attr($search['title']); ?></label></div>
           <div class='content'><?php call_user_func($search['callback'], $search['args']); ?></div>
        </div>
      </div>
      <div class='gp-search-form-container-row'>
        <div class='gp-section'>
           <div class='label'><label for="<?php echo esc_attr($perpage['args']['label_for']) ?>"><?php echo esc_attr($perpage['title']); ?></label></div>
           <div class='content'><?php call_user_func($perpage['callback'], $perpage['args']); ?></div>
        </div>
      </div>
      <div class='submit'>
        <input name='submit' type='submit' id='submit' class='button-primary' value='<?php _e("Save Changes") ?>' />
      </div>
    </div>
  </form>

  <h3>How to use this plugin</h3>
  <ul>
    <li>Step 1: Activate plugin</li>
    <li>Step 2: Add Text widget to page (or somewhere where you can enter shortcodes)</li>
    <li>Step 3: Enter shortcode:  <code>[gpsearch]</code></li>
  <ul>

  <h3>Shortcode Options</h3>
  <table cellspacing="0" cellpadding="0" class='gp-search-options-table'>
    <thead>
      <tr>
        <th class='gp-search-options-header'>Name</th>
        <th class='gp-search-options-header'>Description</th>
        <th class='gp-search-options-header'>Example</th>
        <th class='gp-search-options-header'>Default</th>
        <th class='gp-search-options-header'>Note</th>
      </tr>
    </thead>
    <tbody>
      <tr class='gp-search-options-row'>
        <td class='gp-search-options-cell'>community</td>
        <td class='gp-search-options-cell'>A valid Community id</td>
        <td class='gp-search-options-cell'>[gpsearch community=c798ba17574f8caf0da0e409e0e7726a]</td>
        <td class='gp-search-options-cell'>any</td>
        <td class='gp-search-options-cell'></td>
      </tr>
      <tr class='gp-search-options-row'>
        <td class='gp-search-options-cell'>title</td>
        <td class='gp-search-options-cell'>The title text</td>
        <td class='gp-search-options-cell'>[gpsearch title=GeoObjects]</td>
        <td class='gp-search-options-cell'></td>
        <td class='gp-search-options-cell'>Single word only, no spaces allowed</td>
      </tr>
      <tr class='gp-search-options-row'>
        <td class='gp-search-options-cell'>objtype</td>
        <td class='gp-search-options-cell'>Restrict the type of objects</td>
        <td class='gp-search-options-cell'>[gpsearch objtype=map,service]</td>
        <td class='gp-search-options-cell'>any</td>
        <td class='gp-search-options-cell'><span style='font-weight: bold'>options:</span> community, dataset, gallery, layer, map, service</td>
      </tr>
      <tr class='gp-search-options-row'>
        <td class='gp-search-options-cell'>showpaging</td>
        <td class='gp-search-options-cell'>Flag to display paging control</td>
        <td class='gp-search-options-cell'>[gpsearch showpaging=false]</td>
        <td class='gp-search-options-cell'>true</td>
        <td class='gp-search-options-cell'></td>
      </tr>
      <tr class='gp-search-options-row'>
        <td class='gp-search-options-cell'>showsearch</td>
        <td class='gp-search-options-cell'>Flag to display search control</td>
        <td class='gp-search-options-cell'>[gpsearch showsearch=false]</td>
        <td class='gp-search-options-cell'>true</td>
        <td class='gp-search-options-cell'></td>
      </tr>
      <tr class='gp-search-options-row'>
        <td class='gp-search-options-cell'>sort</td>
        <td class='gp-search-options-cell'>Sort order of results</td>
        <td class='gp-search-options-cell'>[gpsearch sort=label]</td>
        <td class='gp-search-options-cell'>modified</td>
        <td class='gp-search-options-cell'><span style='font-weight: bold'>options:</span> label, modified</td>
      </tr>
      <tr class='gp-search-options-row'>
        <td class='gp-search-options-cell'>maxresults</td>
        <td class='gp-search-options-cell'>Number of results per page</td>
        <td class='gp-search-options-cell'>[gpsearch maxresults=13]</td>
        <td class='gp-search-options-cell'>10</td>
        <td class='gp-search-options-cell'></td>
      </tr>
    </tbody>
  </table>
</div>

<?php



