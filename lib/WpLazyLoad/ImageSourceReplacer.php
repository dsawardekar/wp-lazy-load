<?php

namespace WpLazyLoad;

class ImageSourceReplacer {

  public $placeholder = null;
  public $replaced = false;
  public $scriptPlacer;

  function needs() {
    return array('optionsStore', 'pluginMeta', 'scriptPlacer');
  }

  function enable() {
    add_action('the_content', array($this, 'replaceAndEnqueue'), 99);
  }

  function replaceAndEnqueue($content) {
    $content = $this->replace($content);
    if ($this->replaced) {
      $this->scriptPlacer->enable();
    }

    return $content;
  }

  function replace($content) {
    $this->replaced = false;
    $matches = array();
    $imgs    = preg_match_all('/<img[^>]+>/', $content, $matches);

    if (count($matches) > 0) {
      foreach ($matches[0] as $img) {
        $content = str_replace($img, $this->toPlaceholderImg($img), $content);
        $this->replaced = true;
      }
    }

    return $content;
  }

  function toPlaceholderImg($img) {
    $pattern = "/src=['\"](.*)['\"]/";
    $replacement = <<<HTML
data-original="$1" src="{$this->getPlaceholder()}"
HTML;
    $newImg  = preg_replace($pattern, $replacement, $img);
    $newImg .= "<noscript>$img</noscript>";

    return $newImg;
  }

  function getPlaceholder() {
    if (is_null($this->placeholder)) {
      $placeholder = $this->optionsStore->getOption('placeholder');

      if ($placeholder === '') {
        $placeholder = $this->pluginMeta->getDefaultPlaceholder();
      } else {
        $placeholder = get_stylesheet_directory_uri() .
          '/' . $this->pluginMeta->getSlug() . '/' . $placeholder;
      }

      $this->placeholder = $placeholder;
    }

    return $this->placeholder;
  }

}
