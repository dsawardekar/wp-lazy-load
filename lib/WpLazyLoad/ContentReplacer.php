<?php

namespace WpLazyLoad;

class ContentReplacer {

  public $scriptPlacer;
  public $imageSourceReplacer;

  function needs() {
    return array('scriptPlacer', 'imageSourceReplacer');
  }

  function replace($content) {
    $content = $this->imageSourceReplacer->replace($content);
    if ($this->imageSourceReplacer->replaced) {
      $this->scriptPlacer->enable();
    }

    return $content;
  }

}
