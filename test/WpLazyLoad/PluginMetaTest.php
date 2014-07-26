<?php

namespace WpLazyLoad;

class PluginMetaTest extends \WP_UnitTestCase {

  public $pluginMeta;

  function setUp() {
    parent::setUp();

    $this->pluginMeta = new PluginMeta('wp-lazy-load.php');
  }

  function test_it_has_correct_version() {
    $actual = $this->pluginMeta->version;
    $this->assertEquals(Version::$version, $actual);
  }

  function test_it_has_default_options() {
    $actual = $this->pluginMeta->getDefaultOptions();
    $this->assertContains('threshold', $actual);
    $this->assertContains('effect', $actual);
    $this->assertContains('skipInvisible', $actual);
    $this->assertContains('placeholder', $actual);
  }

  function test_it_has_effect_types() {
    $actual = $this->pluginMeta->getEffectTypes();
    $this->assertContains('fadeIn', $actual);
    $this->assertContains('none', $actual);
  }

}
