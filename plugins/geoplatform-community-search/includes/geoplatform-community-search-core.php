
<?php
  if (!function_exists('geopcomsearch_cname')) {
  function geopcomsearch_cname($objName, $geopcomsearch_uuid, $isContainer = false)
  {
    $fieldId = 'gp-search-' . $geopcomsearch_uuid . '-' . $objName;
    if ($isContainer == true)
     return $fieldId . '-c';
    else
      return $fieldId;
  }
}

?>

<div class='wrap'>
  <div class='gp-search'>
    <div class='header'><h2 class='gp-search-title'><?php echo $geopmap_shortcode_array['title'] ?></h2></div>

    <?php if ($geopcomsearch_searchVal == 1): ?>
    <div class='search-bar'>
      <div class='search-bar-icon'><span class="glyphicon glyphicon-search"></span></div>
      <div class='search-bar-input'><input class='<?php echo geopcomsearch_cname('input', $geopmap_shortcode_array['geopcomsearch_uuid']); ?> search-bar-input-text' placeholder='Search for items' type='text' /></div>
    </div>
    <?php endif ?>

    <?php if ($geopcomsearch_pagingVal == 1): ?>
    <div class="<?php echo geopcomsearch_cname('paging', $geopmap_shortcode_array['geopcomsearch_uuid']); ?> gp-search-paging">
      <div class='gp-search-paging-arrow gp-search-paging-arrow-left gp-search-paging-arrow-disabled' title='Go to first page'><span class="<?php echo geopcomsearch_cname('paging-arrow-first', $geopmap_shortcode_array['geopcomsearch_uuid']); ?> glyphicon glyphicon-fast-backward"></span></div>
      <div class='gp-search-paging-arrow gp-search-paging-arrow-disabled' title='Go to previous page'><span class="<?php echo geopcomsearch_cname('paging-arrow-previous', $geopmap_shortcode_array['geopcomsearch_uuid']); ?> glyphicon glyphicon-backward"></span></div>
      <div class='<?php echo geopcomsearch_cname('paging-text', $geopmap_shortcode_array['geopcomsearch_uuid']); ?> gp-search-paging-text'></div>
      <div class='gp-search-paging-arrow gp-search-paging-arrow-disabled' title='Go to next page'><span class="<?php echo geopcomsearch_cname('paging-arrow-next', $geopmap_shortcode_array['geopcomsearch_uuid']); ?> glyphicon glyphicon-forward"></span></div>
      <div class='gp-search-paging-arrow gp-search-paging-arrow-right gp-search-paging-arrow-disabled' title='Go to last page'><span class="<?php echo geopcomsearch_cname('paging-arrow-last', $geopmap_shortcode_array['geopcomsearch_uuid']); ?> glyphicon glyphicon-fast-forward"></span></div>
    </div>
    <?php endif ?>

    <div class="<?php echo geopcomsearch_cname('objects', $geopmap_shortcode_array['geopcomsearch_uuid']); ?> gp-search-objects">
      <ul class="<?php echo geopcomsearch_cname('object-list', $geopmap_shortcode_array['geopcomsearch_uuid']); ?> gp-search-object-list"></ul>
    </div>

    <div class="<?php echo geopcomsearch_cname('objects-none', $geopmap_shortcode_array['geopcomsearch_uuid']); ?> gp-search-objects-none">No results found.</div>

    <div class="<?php echo geopcomsearch_cname('working', $geopmap_shortcode_array['geopcomsearch_uuid']); ?> gp-search-working">
      <div class='gp-search-working-content'>
        <div class='gp-search-working-spinner'></div>
        <div class='gp-search-working-text'>Retrieving objects...</div>
      </div>
    </div>
    <div class="<?php echo geopcomsearch_cname('error', $geopmap_shortcode_array['geopcomsearch_uuid']); ?> gp-search-error">
      <div class='gp-search-error-title'>Not Found</div>
      <div>Error retrieving data.  Try again or contact us about this error.</div>
    </div>
  </div>
</div>

<script type='text/javascript'>
(function (gp, $) {
  var QueryFactory = GeoPlatform.QueryFactory;
  var ItemTypes = GeoPlatform.ItemTypes;
  var QueryParameters = GeoPlatform.QueryParameters;
  var ItemService = GeoPlatform.ItemService;
  var JQHttpClient = GeoPlatform.JQueryHttpClient;
  var debounce = debouncer(getObjects, 500);

  var page = 1, totalPages = 0, totalResults = 0, start = 0;
  var perPage = 0;
  try { perPage = parseInt(<?php echo $geopmap_shortcode_array['maxresults'] ?>); }
  catch (e) { perPage = 25; }
  if (perPage < 0) perPage = 25;
  if (perPage > 500) perPage = 500;

  let geopcomsearch_uuid = '<?php echo $geopmap_shortcode_array['geopcomsearch_uuid'] ?>';

  var firstArrowSelector = $('.gp-search-' + geopcomsearch_uuid + '-paging-arrow-first');
  var previousArrowSelector = $('.gp-search-' + geopcomsearch_uuid + '-paging-arrow-previous');
  var nextArrowSelector = $('.gp-search-' + geopcomsearch_uuid + '-paging-arrow-next');
  var lastArrowSelector = $('.gp-search-' + geopcomsearch_uuid + '-paging-arrow-last');
  var textSelector = $('.gp-search-' + geopcomsearch_uuid + '-paging-text');
  var objSelector = $('.gp-search-' + geopcomsearch_uuid + '-objects');
  var listSelector = $('.gp-search-' + geopcomsearch_uuid + '-object-list');
  var noneSelector = $('.gp-search-' + geopcomsearch_uuid + '-objects-none');
  var workingSelector = $('.gp-search-' + geopcomsearch_uuid + '-working');
  var errorSelector = $('.gp-search-' + geopcomsearch_uuid + '-error');
  var pagingSelector = $('.gp-search-' + geopcomsearch_uuid + '-paging');
  var searchSelector = $('.gp-search-' + geopcomsearch_uuid + '-input');

  $(document).ready(function () {
    firstArrowSelector.on('click', function (e) {
      var disabled = $(this).parent().hasClass('gp-search-paging-arrow-disabled');
      if (disabled === false) { page = 1; getObjects(); }
    });
    previousArrowSelector.on('click', function (e) {
      var disabled = $(this).parent().hasClass('gp-search-paging-arrow-disabled');
      if (disabled === false) { page = page - 1; getObjects(); }
    });
    nextArrowSelector.on('click', function () {
      var disabled = $(this).parent().hasClass('gp-search-paging-arrow-disabled');
      if (disabled === false) { page = page + 1; getObjects(); }
    });
    lastArrowSelector.on('click', function () {
      var disabled = $(this).parent().hasClass('gp-search-paging-arrow-disabled');
      if (disabled === false) { page = totalPages; getObjects(); }
    });

    searchSelector.on("keydown", debounce);
    searchSelector.on("change", debounce);

    getObjects();
  })

  function displayObjects(objs, _totalResults) {
    if (_totalResults === 0) {
      pagingSelector.hide();
      noneSelector.show();
      objSelector.hide();
    }
    else {
      totalResults = _totalResults;
      start = (page - 1) * perPage;
      end = start + perPage;
      if (end > totalResults)
        end = totalResults;
      totalPages = Math.ceil(totalResults / perPage);
      textSelector.text((start + 1) + " - " + end + " of " + totalResults);

      if (page === 1) {
        firstArrowSelector.parent().addClass('gp-search-paging-arrow-disabled');
        previousArrowSelector.parent().addClass('gp-search-paging-arrow-disabled');
      }
      else {
        firstArrowSelector.parent().removeClass('gp-search-paging-arrow-disabled');
        previousArrowSelector.parent().removeClass('gp-search-paging-arrow-disabled');
      }

      if (page === totalPages) {
        nextArrowSelector.parent().addClass('gp-search-paging-arrow-disabled');
        lastArrowSelector.parent().addClass('gp-search-paging-arrow-disabled');
      }
      else {
        nextArrowSelector.parent().removeClass('gp-search-paging-arrow-disabled');
        lastArrowSelector.parent().removeClass('gp-search-paging-arrow-disabled');
      }

      objSelector.show(function () {
        for (var i = 0; i < objs.length; i++) {
          var oeLink = GeoPlatform.oeUrl + "/view/" + objs[i].id;
          var width = listSelector.width() - 40;

          // If its a map, use an anchor to the proper url.  Otherwise, just display text.
          var label = objs[i].label;
          if (objs[i].resourceTypes !== undefined && objs[i].resourceTypes.length > 0) {
            if (objs[i].resourceTypes[0] === 'http://www.geoplatform.gov/ont/openmap/AGOLMap' && objs[i].landingPage !== undefined)
              label = "<a href='" + objs[i].landingPage + "' target='_blank'>" + objs[i].label + "</a>";
            else {
              var url = "https://viewer.geoplatform.gov?id=" + objs[i].id;
              label = "<a href='" + url + "' target='_blank'>" + objs[i].label + "</a>";
            }
          }

          var html = [];
          html.push("<li class='gp-search-object-list-item'>");
          html.push("<div class='gp-search-object-list-item-content' title='" + objs[i].label + "'>");
          html.push("<div class='left'>");
          html.push("<div style='max-width:" + width + "px;'class='link'>" + label + "</div>");
          html.push("<div style='max-width:" + width + "px;'class='objtype'>" + objs[i].type + "</div>");
          html.push("<div style='max-width:" + width + "px;'class='modified'>Modified: " + new Date(objs[i].modified).toLocaleString() + "</div>");
          html.push("</div>");
          html.push("<div class='right'>");
          html.push("<a href='" + oeLink + "' target='_blank'><span class='glyphicon glyphicon-info-sign' style='font-size:20px'></span></a>");
          html.push("</div>");
          html.push("</div>");
          html.push("</li>");
          listSelector.append(html.join(''));
        }
      });

      errorSelector.hide();
      pagingSelector.show();
      objSelector.show();
    }

    errorSelector.hide();
    workingSelector.hide();
  }

  function getObjects() {
    errorSelector.hide();
    workingSelector.show();
    objSelector.hide();
    noneSelector.hide();
    listSelector.empty();

    var query = new GeoPlatform.Query();

    // Sets default query based on keyword, overwritten if search bar input.
    var initSearch = searchSelector.val()
    if (!initSearch){
      initSearch = '<?php echo $geopmap_shortcode_array['keyword'] ?>';
    }
    query.setQ(initSearch);

    query.setFacets(null);
    query.setFields(['resourceTypes', 'landingPage', 'modified']);

    let objType = '<?php echo $geopmap_shortcode_array['objtype'] ?>';

    var typesArray = [];
    if (objType !== undefined && objType.length > 0)
    {
      var objTypeSplit = objType.split(',');
      for (var i = 0; i < objTypeSplit.length; i++)
      {
        switch (objTypeSplit[i])
        {
          case 'map': typesArray.push(GeoPlatform.ItemTypes.MAP); break;
          case 'dataset': typesArray.push(GeoPlatform.ItemTypes.DATASET); break;
          case 'service': typesArray.push(GeoPlatform.ItemTypes.SERVICE); break;
          case 'layer': typesArray.push(GeoPlatform.ItemTypes.LAYER); break;
          case 'gallery': typesArray.push(GeoPlatform.ItemTypes.GALLERY); break;
          case 'community': typesArray.push(GeoPlatform.ItemTypes.COMMUNITY); break;
        }
      }
    }
    if (typesArray.length === 0)
    {
      typesArray.push(GeoPlatform.ItemTypes.MAP);
      typesArray.push(GeoPlatform.ItemTypes.DATASET);
      typesArray.push(GeoPlatform.ItemTypes.SERVICE);
      typesArray.push(GeoPlatform.ItemTypes.LAYER);
      typesArray.push(GeoPlatform.ItemTypes.GALLERY);
      typesArray.push(GeoPlatform.ItemTypes.COMMUNITY);
    }
    query.setTypes(typesArray);

    let sort = '<?php echo $geopmap_shortcode_array['sort'] ?>';
    if (sort === undefined || sort.length === 0)
      query.setSort('modified,desc');
    else
    {
      switch (sort) {
        case 'modified': query.setSort('modified,desc'); break;
        case 'label': query.setSort('label,asc'); break;
      }
    }

    query.setPage(page - 1);
    query.setPageSize(perPage);

    // Grabs the 'community' value and filters query results by ID value if set.
    let community = '<?php echo $geopmap_shortcode_array['community'] ?>';
    if (community !== undefined && community.length > 0 && community !== 'any')
      query.usedBy(community);

    // Grabs the 'theme' value and filters querry results by theme ID values if set. Multiple values are accecpted.
    let theme = '<?php echo $geopmap_shortcode_array['theme'] ?>';
    if (theme !== undefined && theme.length > 0 && theme !== 'any')
      query.themes(theme);

    retrieveObjects(query)
      .then(function (response) { displayObjects(response.results, response.totalResults); })
      .catch(function (error) {
        errorSelector.show();
        workingSelector.hide();
        pagingSelector.hide();
      });
  }

  function retrieveObjects(query) {
    var deferred = Q.defer();
    var service = new ItemService(GeoPlatform.ualUrl, new JQHttpClient());
    service.search(query)
      .then(function (response) { deferred.resolve(response); })
      .catch(function (e) { deferred.reject(e); });
    return deferred.promise;
  }

  function debouncer(func, wait) {
    var timeout;
    return function (e) {
      clearTimeout(timeout);
      timeout = setTimeout(function () { timeout = null; func(e); }, wait);
    };
  };
}(window.GeoPlatform.geopcomsearch = window.GeoPlatform.geopcomsearch || {}, jQuery));

</script>
