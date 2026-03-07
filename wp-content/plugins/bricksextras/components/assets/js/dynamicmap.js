/* edge buffer */
(function (factory, window) {
    // define an AMD module that relies on 'leaflet'
    if (typeof define === 'function' && define.amd) {
      define(['leaflet'], factory);
  
    // define a Common JS module that relies on 'leaflet'
    } else if (typeof exports === 'object') {
      module.exports = factory(require('leaflet'));
    }
  
    // attach your plugin to the global 'L' variable
    if (typeof window !== 'undefined' && window.L && !window.L.EdgeBuffer) {
      factory(window.L);
    }
  }(function (L) {
    L.EdgeBuffer = {
      previousMethods: {
        getTiledPixelBounds: L.GridLayer.prototype._getTiledPixelBounds
      }
    };
  
    L.GridLayer.include({
  
      _getTiledPixelBounds : function(center, zoom, tileZoom) {
        var pixelBounds = L.EdgeBuffer.previousMethods.getTiledPixelBounds.call(this, center, zoom, tileZoom);
  
        // Default is to buffer one tiles beyond the pixel bounds (edgeBufferTiles = 1).
        var edgeBufferTiles = 2;
        if ((this.options.edgeBufferTiles !== undefined) && (this.options.edgeBufferTiles !== null)) {
          edgeBufferTiles = this.options.edgeBufferTiles;
        }
  
        if (edgeBufferTiles > 0) {
          var pixelEdgeBuffer = L.GridLayer.prototype.getTileSize.call(this).multiplyBy(edgeBufferTiles);
          pixelBounds = new L.Bounds(pixelBounds.min.subtract(pixelEdgeBuffer), pixelBounds.max.add(pixelEdgeBuffer));
        }
        return pixelBounds;
      }
    });
  
  }, window));


function xDynamicMap() {

    const extrasDynamicMap = function ( container, ajax = false ) {

        const dynamicMaps = container.querySelectorAll('.brxe-xdynamicmap');

        dynamicMaps.forEach(dynamicMap => {

            const configAttr = dynamicMap.getAttribute('data-x-map')
            const config = configAttr ? JSON.parse(configAttr) : {}

            let defaultMarker = xMap.leafletDir + 'map-marker.svg'

            const autoZoom = null != config.autoZoom ? config.autoZoom : true;
            const dragging = null != config.dragging ? config.dragging : true;
            const zoomControl = null != config.zoomControl ? config.zoomControl : true;


            let mapLayers = [];

            let tileImg = 'https://tiles.stadiamaps.com/tiles/stamen_watercolor/{z}/{x}/{y}.jpg';
            tileImg = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'

            const defaultLayer = L.tileLayer(tileImg, {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                edgeBufferTiles: 1 /* todo make editable */
            });

            //mapLayers.push(defaultLayer)

            if ( typeof L.tileLayer.provider === 'function' ) {
                let tileLayer = L.tileLayer.provider(config.theme);
                mapLayers.push(tileLayer)
            } 


            /* Add locations / clusters */
            let coords = [];
            let clusteredLocations
            let customIcon

            const locations = L.layerGroup();
            if (config.maybeCluster) {
                clusteredLocations = L.markerClusterGroup();
            }

            if (config.locations) {
                config.locations.forEach(location => {

                    customIcon = L.icon({
                        iconUrl: !!location.markerImage ? location.markerImage : defaultMarker,
                        //shadowSize:   [50, 64], // size of the shadow
                        //iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
                        //shadowAnchor: [4, 62],  // the same for the shadow
                        //popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
                    });

                    var marker = L.marker(new L.LatLng(location.lattitude, location.longitude), { 
                        title: location.label,
                        icon: customIcon
                    });
                    
                    coords.push([location.lattitude, location.longitude]);
                    if (!config.maybeCluster) {
                        marker.bindPopup(location.details).addTo(locations)
                    } else {
                            

                        
                        marker.bindPopup(location.details);
                        clusteredLocations.addLayer(marker);
                    }
                })
            } else if ( config.externalLoop ) {

                if ( dynamicMap.closest('.brxe-section') && dynamicMap.closest('.brxe-section').querySelector('[data-lat]') ) {

                    dynamicMap.closest('.brxe-section').querySelectorAll('[data-lat]').forEach(location => {

                        coords.push([location.getAttribute('data-lat'), location.getAttribute('data-lng')]);
                            if (!config.maybeCluster) {
                                L.marker([location.getAttribute('data-lat'), location.getAttribute('data-lng')]).bindPopup(location.getAttribute('data-details')).addTo(locations)
                            } else {
                                var marker = L.marker(new L.LatLng(location.getAttribute('data-lat'), location.getAttribute('data-lng')), { title: location.getAttribute('data-details') });
                                marker.bindPopup(location.getAttribute('data-details'));
                                clusteredLocations.addLayer(marker);
                            }

                            location.addEventListener('click', (e) => {
                                    e.preventDefault();
                                    let lat = location.getAttribute('data-lat');
                                    let long = location.getAttribute('data-lng');
                                    map.flyTo([lat,long], 10);
                                });

                    })

                }

            }

            if (!config.maybeCluster) {
                mapLayers.push(locations)
            } else {
                mapLayers.push(clusteredLocations)
            }



            L.Marker.prototype.options.icon = L.icon({
                iconUrl: defaultMarker,
            });

            
            const map = L.map(dynamicMap, {
                center: [39.73, -104.99],
                zoom: 9,
                maxZoom: 19,
                layers: mapLayers,
                dragging: dragging,
                zoomControl: zoomControl
            });

            if (config.scale) {
                L.control.scale().addTo(map);
            }

            

            const baseLayers = {
                'OpenStreetMap': defaultLayer,
            };

           
            /* set bounds based on coords */
            //map.fitBounds(coords).pad(0.5)

            /* add layers to map */
            const controls = L.control.layers(baseLayers).addTo(map);

            /* remove controls from top right */
            map.removeControl(controls);

            /* set boundary so all markers are includes */


            
            if (coords.length !== 0) {
                map.fitBounds(coords, {padding: [ parseFloat(config.mapPadding), parseFloat(config.mapPadding) ]})
            }

            //map.setZoom(parseFloat(config.defaultZoom), { animate: false });
            

            /* boundary padding
            var bounds = map.getBounds()
            var newBounds = bounds.pad(.1)
            //map.fitBounds(newBounds)
            */

            

            /* mapbox

           const mapBoxLayer = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1Ijoid3BsaXQiLCJhIjoiY2xwdW9xdjc4MG44NjJubjRoNm9lcnozeCJ9.pxxNDLyn5xZFbHVP-cKl_w', {
                attribution: '© <a href="https://www.mapbox.com/about/maps/">Mapbox</a> © <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> <strong><a href="https://www.mapbox.com/map-feedback/" target="_blank">Improve this map</a></strong>',
                tileSize: 512,
                maxZoom: 18,
                zoomOffset: -1,
                id: 'mapbox/navigation-night-v1',
                accessToken: 'pk.eyJ1Ijoid3BsaXQiLCJhIjoiY2xwdW9xdjc4MG44NjJubjRoNm9lcnozeCJ9.pxxNDLyn5xZFbHVP-cKl_w'
                }).addTo(map);


             */


            window.xMap.Instances[dynamicMap.dataset.xId] = map;
            

        })

    }


    extrasDynamicMap(document);

    function xDynamicMapAjax(e) {

        if (typeof e.detail.queryId === 'undefined') {
            if ( typeof e.detail.popupElement === 'undefined' ) {
                return;
            } else {
                extrasDynamicMap( e.detail.popupElement, true )
            }
        }

        setTimeout(() => {
            if ( document.querySelector('.brxe-' + e.detail.queryId) ) {
                extrasDynamicMap(document.querySelector('.brxe-' + e.detail.queryId).parentElement);
            }
        }, 0);
      }
      
      document.addEventListener("bricks/ajax/load_page/completed", xDynamicMapAjax)
      document.addEventListener("bricks/ajax/pagination/completed", xDynamicMapAjax)
      document.addEventListener("bricks/ajax/popup/loaded", xDynamicMapAjax)
      document.addEventListener("bricks/ajax/end", xDynamicMapAjax)

    // Expose function
    window.doExtrasDynamicMap = extrasDynamicMap;

}


    

document.addEventListener("DOMContentLoaded",() => {
    bricksIsFrontend&&xDynamicMap()
 });