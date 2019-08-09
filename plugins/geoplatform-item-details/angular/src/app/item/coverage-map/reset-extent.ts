
import * as L from "leaflet";


export var ResetExtentControl = L.Control.extend({

    options: {
        position: 'topleft',
        initialExtent: null
    },

    // initialize: function(options) {
    //     setOptions(this, options);
    // },

    onAdd: function(map) {
        // Create the loading indicator
        var classes = 'leaflet-control-reset-extent';
        var container = L.DomUtil.create('div', 'leaflet-control-extent leaflet-bar');
        var link = L.DomUtil.create('a', classes, container);
        link.innerHTML = '<span class="fas fa-globe-americas"></span>';
		(link as any).href = '#';
		link.title = "Reset map to resource's extent";


        let resetExtent = () => {
            let extent = this.options.initialExtent;
            if(extent) map.fitBounds(extent);
        };

		/*
		 * Will force screen readers like VoiceOver to read this as "Zoom in - button"
		 */
		link.setAttribute('role', 'button');
		link.setAttribute('aria-label', link.title);

		L.DomEvent.disableClickPropagation(link);
		L.DomEvent.on(link, 'click', L.DomEvent.stop);
		L.DomEvent.on(link, 'click', resetExtent, this);
		L.DomEvent.on(link, 'click', this._refocusOnMap, this);

        return container;
    },

    // onRemove: function(map) {
    //
    // },

    // removeFrom: function (map) {
    //     // If this control is separate from the zoomControl, call the
    //     // parent method so we don't leave behind an empty container
    //     return L.Control.prototype.removeFrom.call(this, map);
    // },

    setExtent: function(extent) {
        this.options.initialExtent = extent;
    }

});
