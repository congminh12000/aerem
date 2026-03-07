
 function xImageHotspots(){

    const extrasImageHotspots = function ( container ) {

        bricksQuerySelectorAll(container, '.brxe-ximagehotspots').forEach( imageHotspot => {

            const configAttr = imageHotspot.getAttribute('data-x-hotspots')
            const config = configAttr ? JSON.parse(configAttr) : {}
            
            imageHotspot.querySelectorAll('.x-marker').forEach( marker => {

                let instance = tippy(marker.querySelector('button.x-marker_marker'), {
                    content: marker.querySelector('.x-marker_popover-content'), 
                    allowHTML: true,     
                    interactive: true, 
                    arrow: true,
                    trigger: config.interaction,
                    appendTo: marker.querySelector('.x-marker_popover'),
                    placement: config.placement,
                    maxWidth: 'none',    
                    animation: 'extras',
                    theme: 'extras',     
                    touch: true, 
                    moveTransition: 'transform ' + config.moveTransition + 'ms ease-out', 
                    offset: [ config.offsetSkidding , config.offsetDistance], 
                    
                });

            })

        })

    }


    extrasImageHotspots(document)

    function xImageHotspotsAJAX(e) {

        if ( typeof e.detail.queryId === 'undefined' ) {
            if ( typeof e.detail.popupElement === 'undefined' ) {
                return;
            } else {
                extrasImageHotspots( e.detail.popupElement )
            }
        }
    
        setTimeout(() => {
            if ( document.querySelector('.brxe-' + e.detail.queryId) ) {
                extrasImageHotspots(document.querySelector('.brxe-' + e.detail.queryId).parentElement);
            }
        }, 0);
    
      }
    
      document.addEventListener("bricks/ajax/load_page/completed", xImageHotspotsAJAX)
      document.addEventListener("bricks/ajax/pagination/completed", xImageHotspotsAJAX)
      document.addEventListener("bricks/ajax/popup/loaded", xImageHotspotsAJAX)
      document.addEventListener("bricks/ajax/end", xImageHotspotsAJAX)

    // Expose function
    window.doExtrasImageHotspots = extrasImageHotspots;

}

document.addEventListener("DOMContentLoaded",function(e){
    
    if ( !bricksIsFrontend ) {
        return;
    }

    xImageHotspots()
})
