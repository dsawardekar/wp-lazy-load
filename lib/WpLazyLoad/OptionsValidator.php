<?php

namespace WpLazyLoad;

class OptionsValidator extends \Arrow\OptionsManager\OptionsValidator {

  function loadRules($validator) {
    $slug = $this->pluginMeta->getSlug();
    $validator
      ->rule('required', 'threshold')
      ->rule('integer', 'threshold')
      ->rule('min', 'threshold', 0)
      ->rule('max', 'threshold', 5000)

      ->rule('required', 'effect')
      ->rule('in', 'effect', $this->pluginMeta->getEffectTypes())

      ->rule('safeText', 'placeholder')
      ->message('{field} must not contain markup')

      ->rule('lazyPlaceholder', 'placeholder')
      ->message("{field} not found in current theme's $slug directory");
  }

  function loadCustomRules() {
    $slug = $this->pluginMeta->getSlug();
    \Valitron\Validator::addRule(
      'lazyPlaceholder', array($this, 'isLazyPlaceholder'),
      "Placeholder not found in your current theme's $slug directory"
    );
  }

  function isLazyPlaceholder($field, $value, $params) {
    if ($value !== '') {
      return $this->pluginMeta->hasCustomStylesheet($value);
    } else {
      return true;
    }
  }

}
