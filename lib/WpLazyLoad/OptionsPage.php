<?php

namespace WpLazyLoad;

class OptionsPage extends \Arrow\OptionsManager\OptionsPage {

  function getTemplateContext() {
    return array(
      'effects'       => $this->pluginMeta->getEffectTypes(),
      'threshold'     => $this->getOption('threshold'),
      'effect'        => $this->getOption('effect'),
      'skipInvisible' => $this->getOption('skipInvisible'),
      'placeholder'   => $this->getOption('placeholder')
    );
  }

}
