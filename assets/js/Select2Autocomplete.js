import jQuery from 'jquery';
import 'select2';

// TODO: Load dynamic
import 'select2/select2_locale_de';

// Styles
import 'select2/select2.css';
import 'select2-bootstrap-css/select2-bootstrap.min.css';

class Select2Autocomplete {
  /**
   * @param {Element} element
   * @param {Object=} options
   *
   * @constructor
   */
  constructor(element, options) {
    const settings = jQuery.extend({
      ajaxRoute: '',
      placeholder: ''
    }, options);

    const select = jQuery(element);

    select.select2({
      ajax: {
        url: settings.ajaxRoute,
        dataType: 'json',
        delay: 250,
        placeholder: settings.placeholder,
        data: function (params) {
          return {
            q: params.term,
          };
        },
        results: function (data) {
          let results = [];
          jQuery.each(data, function (index, item) {
            results.push({
              id: item.id,
              text: item.value
            });
          });
          return {
            results: results
          };
        },
        cache: true,
        escapeMarkup: function (markup) {
          return markup;
        },
        minimumInputLength: 3
      }
    });


    select.select2('data', {
      id: select.val(),
      text: settings.text
    });
  }
}

// Bind
const weakMap = new WeakMap();

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-select2-autocomplete]').forEach((element) => {
    if (weakMap.has(element) && weakMap.get(element).select2Autocomplete) {
      return;
    }

    const options = JSON.parse(element.getAttribute('data-select2-autocomplete') || {});

    weakMap.set(element, {
      select2Autocomplete: new Select2Autocomplete(element, options)
    });
  });
});
