<article class="p-landing-page__get-started">
		<div class="m-article__heading m-article__heading--front-page">Get Started</div>
		<div>
			<div class="d-flex flex-align-center">
			    <div class="col-4">
			        <div class="m-article__desc">
			            With over <span id="portfolio-total-count"></span> resources,
			            the GeoPlatform has a wealth of data and services relevant to many
			            needs across the blah, blah, blah...  Lorem ipsum dolor sit amet,
			            consectetur adipiscing elit,
			            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
			            Ut enim ad minim veniam, quis nostrud exercitation ullamco
			            laboris nisi ut aliquip ex ea commodo consequat. Duis aute
			            irure dolor in reprehenderit in voluptate velit esse cillum
			            dolore eu fugiat nulla pariatur.
			        </div>
			    </div>
			    <div class="col">
			        <div id="portfolio-breakdown-graph">
			            <div class=" u-text--center u-mg-top--xlg">
			                <div class="fa-3x">
			                    <i class="fas fa-spinner fa-spin"></i>
			                </div>
			                Loading...
			            </div>
			        </div>
			    </div>

			    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

			    <script>

			        function getFriendlyKey(str) {
			            switch(str) {
			                case "Map":
			                case "Layer": return str + "s";
			                case "Community": return "Communities";
			                case "Gallery": return "Galleries";
			                case "regp:Service":
			                    return str.replace('regp:','') + 's';
			                case "dcat:Dataset":
			                    return str.replace('dcat:','') + 's';
			                // case "skos:Concept":
			                // case "skos:ConceptScheme":
			                //     return str.replace('skos:','') + 's';
			                // case "vcard:VCard": return "Contacts";
			                // case "org:Organization":
			                //     return str.replace('org:','') + 's';
			                // case "foaf:Person":
			                //     return str.replace('foaf:','') + 's';
			                default: return "Others";
			            }
			        }

			        function drawChart(counts) {
			            let options = {
			                is3D: true,
			                sliceVisibilityThreshold: .001,
			                chartArea: { top: 0, left: '3%', height: '100%', width: '97%' },
			                legend: { position: 'right', alignment: 'center' }
			            };
			            let data = google.visualization.arrayToDataTable(counts);
			            let el = $('#portfolio-breakdown-graph')[0];
			            let chart = new google.visualization.PieChart(el);
			            chart.draw(data, options);

			            // Listen for the 'select' event, and call my function selectHandler() when
			            // the user selects something on the chart.
			            google.visualization.events.addListener(chart, 'select', () => {
			                var selectedItem = chart.getSelection()[0];
			                if (selectedItem) {
			                    let type = data.getValue(selectedItem.row, 2);
			                    window.open('https://www.geoplatform.gov/geoplatform-search/#/?types=' + type, '');
			                }
			            });
			        }

			        $(document).ready( waitForGoogle );

			        function loadData() {

			            $.getJSON('https://ual.geoplatform.gov/api/items?size=1&facets=types')
			            .done( (data, textStatus, jqXHR) => {
			                let counts = [ ['Type', 'Occurrences', 'id'] ], others = 0, total = 0;
			                data.facets[0].buckets.forEach( bkt => {
			                    total += bkt.count*1;
			                    let key = getFriendlyKey(bkt.label);
			                    if(key) {
			                        if('Others' === key) others += bkt.count*1;
			                        else counts.push([ key + ' ('+bkt.count+')', bkt.count, bkt.label]);
			                    }
			                    //else ignore the bucket
			                });
			                counts.push(['Others (' + others + ')', others, null]);

			                $('#portfolio-total-count').text( -Math.round(-total / 1000) * 1000 );

			                google.charts.load('current', {'packages':['corechart']});
			                google.charts.setOnLoadCallback(() => { drawChart(counts) });
			            })
			            .fail( (jqXHR, textStatus, errorThrown) => {
			                console.log("Error getting portfolio counts: " + errorThrown);
			            });

			        }


			        function waitForGoogle() {
			            setTimeout(() => {
			                if(!google) waitForGoogle();
			                else onGoogleReady();
			            }, 1000);
			        }

			        function onGoogleReady() {
			            if(!google.visualization ||
			                !google.visualization.ScatterChart) {
			                google.charts.load('current', {'packages':['corechart']});
			                google.charts.setOnLoadCallback(loadData);

			            } else {
			                loadData();
			            }
			        }

			    </script>
			</div>

		</div>
		<form id="geoplatformsearchform">
			<div class="m-get-started__search">
				<div class="input-group-slick input-group-slick--lg">
					<span class="icon fas fa-search"></span>
					<input type="text" class="form-control" id="geoplatformsearchfield"	aria-label="Search the GeoPlatform"	placeholder="SEARCH THE GEOPLATFORM">
				</div>
				<button type="button" class="btn btn-lg btn-primary" id="geoplatformsearchbutton">SEARCH</button>
			</div>
		</form>

		<script>
		// Code section. First jQuery triggers off of form submission (enter button) and
		// navigates to the geoplatform-search page with the search field params.
		  jQuery( "#geoplatformsearchform" ).submit(function( event ) {
		    event.preventDefault();
		    window.location.href='geoplatform-search/#/?q='+jQuery('#geoplatformsearchfield').val();
		  });

		// Functionally identical to above, triggered by submit button press.
		  jQuery( "#geoplatformsearchbutton" ).click(function( event ) {
		    window.location.href='geoplatform-search/#/?q='+jQuery('#geoplatformsearchfield').val();
		  });
		</script>
</article>
