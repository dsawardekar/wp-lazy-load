<?php

namespace WpLazyLoad;

class PluginMeta extends \Arrow\PluginMeta {

  public $defaultPlaceholder;

  function __construct($file) {
    parent::__construct($file);
    $this->version = Version::$version;
  }

  function getDefaultOptions() {
    return array(
      'threshold'     => 200,
      'effect'        => 'fadeIn',
      'skipInvisible' => true,
      'placeholder'   => ''
    );
  }

  function getEffectTypes() {
    return array('fadeIn', 'none');
  }

  function getDefaultPlaceholder() {
    if ($this->defaultPlaceholder === null) {
      $this->defaultPlaceholder = plugins_url(
        'img/placeholder.gif', $this->getFile()
      );
    }

    return $this->defaultPlaceholder;
  }

  function getOptionsContext() {
    $optionsStore           = $this->lookup('optionsStore');
    $options                = $optionsStore->getOptions();
    $options['effectTypes'] = $this->getEffectTypes();

    return $options;
  }

}
