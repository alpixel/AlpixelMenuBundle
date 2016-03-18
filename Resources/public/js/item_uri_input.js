(function($) {
    $(function () {
        function init()
        {
            var currentUri = $('#input-group-addon-uri + input').val();
            var type = 'external';
            if (currentUri != null && currentUri.indexOf('/') === 0) {
                type = 'internal';
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
            var currentUri = $('#input-group-addon-uri + input').val();
            if (getUriType() === 'internal' && currentUri != null && currentUri.indexOf('/') !== 0) {
                $('#input-group-addon-uri + input').val('/'+currentUri);
            }
            else if (getUriType() === 'external' && currentUri.indexOf('/') === 0) {
                currentUri = currentUri.substring(1, currentUri.length);
                $('#input-group-addon-uri + input').val(currentUri);
            }
        }

        function setIconUriType(type) {
            switch (type) {
                case 'external':
                    $('#input-group-addon-uri').html('<i class="fa fa-link"></i>');
                    break
                case 'internal':
                    $('#input-group-addon-uri').html(BASE_URL);
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
