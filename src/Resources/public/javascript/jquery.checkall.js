/*! (c) Christian Gripp, core23 - webdesign & more | core23.de */
(function ($) {
    $.checkAll = {version: '1.00'};

    $.fn.checkAll = function (options) {
        var $master = this,
            settings = {
                items: null,
                check: null
            };

        if (typeof options === 'string') {
            settings.items = options;
        } else {
            settings = $.extend({}, settings, options);
        }

        function sync() {
            var inputs = $(settings.items);
            var checked = inputs.filter(':checked');

            if (inputs.length === checked.length) {
                $master.prop('checked', true);
                $master.prop('indeterminate', false);
            } else if (checked.length === 0) {
                $master.prop('checked', false);
                $master.prop('indeterminate', false);
            } else {
                $master.prop('indeterminate', true);
            }
        }

        if (settings.check === null) {
            // Bind checkbox listeners
            $(document).on('change', settings.items, function () {
                sync();
            });

            // Bind master listener
            $master.on('change', function () {
                $(settings.items).prop('checked', $(this).prop('checked'));
            });

            sync();
        } else {
            // Bind master listener
            $master.on('click', function () {
                $(settings.items).prop('checked', settings.check).trigger('change');
            });
        }

        return $(this);
    };

    // auto-initialize plugin
    $(function () {
        $('[data-checkall]').each(function () {
            var $this = $(this);

            $this.checkAll($this.data('checkall'));
        });
    });
}(jQuery));
