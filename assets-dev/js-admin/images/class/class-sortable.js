Wizzaro.namespace('Plugins.Gallery.v1');
Wizzaro.Plugins.Gallery.v1.ImagesSortable = function( config ) {
    
    var _config = config;
    
    //----------------------------------------------------------------------------------------------------
    // Init sortable
    
    //----------------------------------------------------------------------------------------------------
    // API functions
    
    this.create = _create;
    this.recreate = _recreate;
    this.destroy = _destroy;
    this.refresh = _refresh;
    
    
    //----------------------------------------------------------------------------------------------------
    // Functions
    function _create() {
        jQuery( '#' + _config.container_id ).sortable( {
            items: '> .' + _config.items_class,
            placeholder: _config.placeholder_class,
            cursor: "move",
            opacity: _config.sort_opacity,
            update: function(event, ui) {
                //TODO open sortable tools
            }
        } ).disableSelection();
    }
    
    function _destroy() {
        jQuery( '#' + _config.container_id ).sortable( 'destroy' );
    }
    
    function _recreate() {
        _destroy();
        _create();
    }
    
    function _refresh() {
        jQuery( '#' + _config.container_id ).sortable( 'refresh' );
    }
};
