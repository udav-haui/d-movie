var LayersliderInit = function () {

    return {
        initLayerSlider: function () {
            $('#layerslider').layerSlider({
                skinsPath : '../../assets/global/plugins/slider-layer-slider/skins/',
                skin : 'fullwidth',
                thumbnailNavigation : 'hover',
                hoverPrevNext : false,
                responsive : true,
                responsiveUnder : 1702,
                layersContainer: 1702
                //width: 1702,
                //height: 621
            });
        }
    };

}();