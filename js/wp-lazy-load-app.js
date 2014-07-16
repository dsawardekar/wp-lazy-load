(function($) {

  var showNotice = function(type, message) {
    $('#message').attr('class', 'updated ' + type);
    $('#message p strong').text(message);
    $('#message').css('display', 'block');
  };

  var hideNotice = function() {
    $('#message').css('display', 'none');
  };

  var reveal = function() {
    hideNotice();
    $('#wp-lazy-load-form').css('display', 'block');
  };

  var getOption = function(name) {
    return wp_lazy_load_app_run[name];
  };

  /* AJAX helpers */
  var urlFor = function(controller, operation) {
    var params        = {};
    params.controller = controller;
    params.operation  = operation;
    params.nonce      = getOption('nonce');

    return getOption('apiEndpoint') + '&' + $.param(params);
  };

  var request = function(controller, operation, queryParams) {
    queryParams.url = urlFor(controller, operation);

    if (queryParams.type === 'POST' && queryParams.hasOwnProperty('data')) {
      queryParams.data = JSON.stringify(queryParams.data);
    }

    return $.ajax(queryParams)
      .then(function(response) {
        if (response === '0') {
          return $.Deferred().reject('Not Logged In').promise();
        } else if (response.success) {
          return response.data;
        } else {
          return $.Deferred().reject(response.data.error).promise();
        }

        return false;
      })
      .fail(function(response) {
        var error;
        if (response.statusText === 'timeout') {
          error = 'Request Timed Out.';
        } else if (response.responseJSON) {
          error = response.responseJSON.data.error;
        } else {
          error = 'Unknown Response.';
        }

        return $.Deferred().reject(error).promise();
      });
  };

  var getFormField = function(field) {
    return $("#wp-lazy-load-form").find('input[name=' + field + ']');
  };

  var setFormField = function(field, value) {
    var formField = getFormField(field);
    formField.val(value);
  };

  var getSelectOption = function(label, value, selected) {
    var option = '<option></option>';
    label      = label[0].toUpperCase() + label.substr(1);

    var optionField = $(option);
    optionField.val(value).html(label);
    optionField.prop('selected', selected);

    return optionField;
  };

  var initForm = function() {
    setFormField('threshold', getOption('threshold'));
    setFormField('placeholder', getOption('placeholder'));

    var skipInvisible = getOption('skipInvisible');
    skipInvisible = skipInvisible === true || skipInvisible === '1';
    getFormField('skipInvisible').prop('checked', skipInvisible);

    initSelect();

    $('#submit').on('click', handleSubmit);
    $('#reset').on('click', handleReset);
  };

  var initSelect = function() {
    var effectTypes  = getOption('effectTypes');
    var effect       = getOption('effect');
    var effectSelect = $('#effect');
    var n = effectTypes.length;
    var label, value, selected;

    effectSelect.find('option').remove();

    for (var i = 0; i < n; i++) {
      label = effectTypes[i];
      value = label;
      selected = effect === value;
      effectSelect.append(getSelectOption(label, value, selected));
    }
  };

  var resetForm = function(data) {
    for (var key in data) {
      if (data.hasOwnProperty(key)) {
        wp_lazy_load_app_run[key] = data[key];
      }
    }

    initForm();
  };

  var restoreDefaults = function(data) {
    var params = { type: 'POST', data: data };

    showNotice('progress', 'Restoring Defaults ...');

    request('options', 'delete', params)
      .then(function(data) {
        showNotice('success', 'Restored Defaults.');
        resetForm(data);
      })
      .fail(function(error) {
        showNotice('error', error);
      });
  };

  var saveOptions = function(data) {
    var params = { type: 'POST', data: data };

    showNotice('progress', 'Saving settings ...');

    request('options', 'patch', params)
      .then(function(data) {
        showNotice('success', 'Settings saved successfully.');
        resetForm(data);
      })
      .fail(function(error) {
        showNotice('error', error);
      });
  };

  var handleReset = function(event) {
    event.preventDefault();

    var confirmed = confirm('Restore Defaults: Are you sure?');
    if (confirmed) {
      restoreDefaults({});
    }
  };

  var handleSubmit = function(event) {
    event.preventDefault();

    var data = {
      threshold: getFormField('threshold').val(),
      effect: $('#effect').val(),
      skipInvisible: getFormField('skipInvisible').prop('checked'),
      placeholder: getFormField('placeholder').val()
    };

    saveOptions(data);
  };

  $(document).ready(function() {
    initForm();
    reveal();
  });

}(jQuery));
