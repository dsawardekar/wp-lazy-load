<?php

namespace WpLazyLoad;

class OptionsManager extends \Arrow\OptionsManager\OptionsManager {

  function __construct($container) {
    parent::__construct($container);

    $container
      ->singleton('optionsValidator', 'WpLazyLoad\OptionsValidator')
      ->singleton('optionsPage', 'WpLazyLoad\OptionsPage');
  }

}
