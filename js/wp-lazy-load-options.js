(function($) {

  var getOptions = function() {
    var opts    = wp_lazy_load_options;
    var options = {};

    options.threshold      = parseInt(opts.threshold, 10);
    options.skip_invisible = opts.skipInvisible === '1';
    options.effect_speed   = parseInt(opts.effectSpeed, 10) || 400;

    if (opts.effect !== 'none') {
      options.effect = opts.effect;
    }

    if (opts.placeholder !== '') {
      options.placeholder = opts.placeholder;
    }

    return options;
  };

  $(document).ready(function() {
    var options = getOptions();
    $("img[data-original]").lazyload(options);
  });

}(jQuery));
