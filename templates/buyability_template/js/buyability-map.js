jQuery(document).ready(function($){

var image =$('#buyabilitymap');

    image.mapster(
    {
        fillOpacity: 0.3,
        fillColor: "d42e16",
        stroke: true,
        strokeColor: "3320FF",
        strokeOpacity: 0.8,
        strokeWidth: 0,
        singleSelect: true,
        mapKey: 'name',
        listKey: 'name',
        onClick: function (e) {
            var newToolTip = defaultDipTooltip;
          
            image.mapster('set_options', { 
                areas: [{
                    key: "dip",
                    toolTip: newToolTip
                    }]
                });
        },
        showToolTip: true,
        toolTipClose: ["tooltip-click", "area-click"],
        areas: [
            {
                key: "wa",
                fillColor: "242041"
            },
            {
                key: "nt",
                fillColor: "ac6027"
            },
            {
                key: "sa",
                fillColor: "86152f"
            },
            {
                key: "qld",
                fillColor: "aa8c30"
            },
            {
                key: "tas",
                fillColor: "ac6027"
            },
            {
                key: "vic",
                fillColor: "242041"
            },
            {
                key: "nsw",
                fillColor: "076273"
            },
            {
                key: "act",
                strokeColor: "86152f"
            }
            ]
    });
 

});

