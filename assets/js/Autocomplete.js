import 'awesomplete';
import 'awesomplete/awesomplete.css'

export default class Autocomplete {
  /**
   * @param {Element|string} element
   * @param {Object=} options
   *
   * @constructor
   */
  constructor(element, options) {
    if (typeof element === 'string') {
      this.element = document.querySelector(element);
    } else {
      this.element = element;
    }

    if (!this.element) {
      return;
    }

    const defaults = {
      ajaxRoute: '',
      minChars: 3,
    };

    this.settings = {...defaults, ...options};

    const self = this;

    this.shadowElement = this.createShadowElement(element);

    const autocomplete = new Awesomplete(element, {
      minChars: self.settings.minChars,
      list: [],

      replace: function (suggestion) {
        self.element.value = suggestion.label;
        self.shadowElement.value = suggestion.value;
      },
    });

    element.addEventListener('input', async function () {
      autocomplete.list = await self.fetchResult(element.value);

      if (autocomplete.list.length === 0) {
        self.shadowElement.value = '0';
      }

      autocomplete.evaluate();
      autocomplete.open();
    });
  }

  createShadowElement(element) {
    const shadowInput = document.createElement('input');
    shadowInput.setAttribute("type", "hidden");
    shadowInput.setAttribute("name", element.name);

    element.parentNode.insertBefore(shadowInput, element.nextSibling);
    element.setAttribute("name", element.name + '_autocomplete');

    return shadowInput;
  }

  async fetchResult(query) {
    if (!query || query.length < this.settings.minChars) {
      return [];
    }

    return await this.sendRequest(query)
      .then(function (result) {
        return JSON.parse(result).map(function (item) {
          return {
            value: item.id,
            label: item.value
          }
        });
      }).catch(function (err) {
        console.error(err)
      });
  }

  async sendRequest(query) {
    const self = this;

    const xhr = new XMLHttpRequest();
    return new Promise(function (resolve, reject) {
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
          if (xhr.status >= 300) {
            reject("Error, status code = " + xhr.status)
          } else {
            resolve(xhr.responseText);
          }
        }
      };
      xhr.open('get', self.settings.ajaxRoute + '?q=' + query, true);
      xhr.send();
    });
  }
}

// Bind
const weakMap = new WeakMap();

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-autocomplete]').forEach((element) => {
    if (weakMap.has(element) && weakMap.get(element).autocomplete) {
      return;
    }

    const options = JSON.parse(element.getAttribute('data-autocomplete') || {});

    weakMap.set(element, {
      autocomplete: new Autocomplete(element, options)
    });
  });
});
