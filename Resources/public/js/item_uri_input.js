(function($) {
    $(function () {
        function init()
        {
            var currentUri = $('#input-group-addon-uri + input').val();
            var type = 'anchor';

            if (currentUri != null) {
                if (currentUri.indexOf('/') === 0) {
                    type = 'internal';
                } else if (currentUri.indexOf('http') === 0) {
                    type = 'external';
                }
            }

            setIconUriType(type);
            $('select[name="uri_type"]').val(type);
        }

        // Get type of uri internal or external
        function getUriType()
        {
            return $('select[name="uri_type"]').val();
        }

        // Force the / on start of string for internal link
        function forceValidInternalLink() {
            var inputUri = $('#input-group-addon-uri + input');
            var currentUri = inputUri.val();
            if (currentUri != null) {
                if (getUriType() === 'internal' && currentUri.indexOf('/') !== 0) {
                    inputUri.val('/'+currentUri);
                } else if (getUriType() === 'external' && currentUri.indexOf('/') === 0) {
                    currentUri = currentUri.substring(1, currentUri.length);
                    inputUri.val(currentUri);
                } else if (getUriType() === 'anchor' && currentUri.indexOf('#') !== 0) {
                    inputUri.val('#'+currentUri);
                }
            }
        }

        function setIconUriType(type) {
            var iconElement = $('#input-group-addon-uri');

            switch (type) {
                case 'anchor':
                    iconElement.html('<i class="fa fa-anchor"></i>');
                    break;
                case 'external':
                    iconElement.html('<i class="fa fa-link"></i>');
                    break
                case 'internal':
                    iconElement.html(BASE_URL);
                    forceValidInternalLink();
                    break
            }
        }

        init();
        var inputUri = $('#input-group-addon-uri + input');
        var inputUriType = $('select[name="uri_type"]').select2();

        $(inputUriType).on('select2-selected', function() {
            setIconUriType(getUriType());
            forceValidInternalLink();
        });

        $(inputUri).keyup(function(){
            if(getUriType()) {
                forceValidInternalLink();
            }
        });
    });
})(jQuery);
