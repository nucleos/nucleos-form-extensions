/*! (c) Christian Gripp, core23 - webdesign & more | core23.de */
(function ($, global) {
    /**
     * Select2Autocomplete class.
     *
     * @param element
     * @param options
     * @constructor
     */
    var Select2Autocomplete = function (element, options) {
        var settings = $.extend({
            ajaxRoute: '',
            placeholder: ''
        }, options);

        element.select2({
            ajax: {
                url: settings.ajaxRoute,
                dataType: 'json',
                width: '100%',
                delay: 250,
                placeholder: settings.placeholder,
                data: function (params) {
                    return {
                        q: params
                    };
                },
                results: function (data) {
                    var results = [];
                    $.each(data, function (index, item) {
                        results.push({
                            id: item.id,
                            text: item.value
                        });
                    });
                    return {
                        results: results
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) {
                return markup;
            },
            minimumInputLength: 3
        });

        element.select2('data', {
            id: element.val(),
            text: settings.text
        });
    };

    global.Select2Autocomplete = Select2Autocomplete;

    // auto-initialize plugin
    $(function () {
        $('[data-select2-autocomplete]').each(function () {
            var $this = $(this),
                attr = $this.data('select2-autocomplete');
            $(this).data('select2-autocomplete-instance', new Select2Autocomplete($this, attr));
        });
    });
})(jQuery, window);
