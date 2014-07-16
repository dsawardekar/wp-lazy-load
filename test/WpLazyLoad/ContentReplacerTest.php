<?php

namespace WpLazyLoad;

use Encase\Container;

class ContentReplacerTest extends \WP_UnitTestCase {

  public $container;
  public $pluginMeta;
  public $store;
  public $replacer;

  function setUp() {
    parent::setUp();

    $this->pluginMeta = new PluginMeta('wp-lazy-load/wp-lazy-load.php');
    $this->container = new Container();
    $this->container
      ->object('pluginMeta', $this->pluginMeta)
      ->packager('optionsPackager', 'Arrow\Options\Packager')
      ->singleton('scriptPlacer', 'WpLazyLoad\ScriptPlacer')
      ->singleton('imageSourceReplacer', 'WpLazyLoad\ImageSourceReplacer')
      ->singleton('contentReplacer', 'WpLazyLoad\ContentReplacer');

    $this->replacer      = $this->container->lookup('contentReplacer');
    $this->imageReplacer = $this->container->lookup('imageSourceReplacer');
    $this->store         = $this->container->lookup('optionsStore');
    $this->placer        = $this->container->lookup('scriptPlacer');
  }

  function test_it_has_a_script_placer() {
    $this->assertSame($this->placer, $this->replacer->scriptPlacer);
  }

  function test_it_has_an_image_source_replacer() {
    $this->assertSame($this->imageReplacer, $this->replacer->imageSourceReplacer);
  }

  function test_it_enables_script_placer_if_content_is_replaced() {
    $this->replacer->replace("<img src='foo.jpg'>");
    $this->assertTrue($this->placer->enabled);
  }

  function test_it_does_not_enabled_script_placer_if_content_is_not_replaced() {
    $this->replacer->replace("foo.jpg");
    $this->assertFalse($this->placer->enabled);
  }

}
