<?php

namespace WpLazyLoad;

use Encase\Container;

class ScriptPlacerTest extends \WP_UnitTestCase {

  public $container;
  public $placer;
  public $pluginMeta;
  public $loader;
  public $store;

  function setUp() {
    parent::setUp();

    $this->pluginMeta = new PluginMeta('wp-lazy-load.php');
    $this->container = new Container();
    $this->container
      ->object('pluginMeta', $this->pluginMeta)
      ->packager('optionsPackager', 'Arrow\Options\Packager')
      ->singleton('scriptPlacer', 'WpLazyLoad\ScriptPlacer');

    $this->loader = $this->container->lookup('scriptLoader');
    $this->placer = $this->container->lookup('scriptPlacer');
    $this->store  = $this->container->lookup('optionsStore');
  }

  function test_it_has_plugin_meta() {
    $this->assertSame($this->pluginMeta, $this->placer->pluginMeta);
  }

  function test_it_has_a_script_loader() {
    $this->assertSame($this->loader, $this->placer->scriptLoader);
  }

  function test_it_has_a_options_store() {
    $this->assertSame($this->store, $this->placer->optionsStore);
  }

  function test_it_is_not_enabled_initially() {
    $this->assertFalse($this->placer->enabled);
  }

  function test_it_can_be_enabled() {
    $this->placer->enable();
    $this->assertTrue($this->placer->enabled);
  }

  function test_it_can_build_plugin_options_without_placeholder() {
    $this->store->load();
    $this->store->setOption('threshold', 300);
    $this->store->setOption('effect', 'fade');
    $this->store->setOption('skipInvisible', true);
    $this->store->setOption('placeholder', '');

    $actual = $this->placer->getPluginOptions(null);
    $expected = array(
      'threshold' => 300,
      'effect' => 'fade',
      'skipInvisible' => true,
      'placeholder' => ''
    );

    $this->assertEquals($expected, $actual);
  }

  function test_it_can_build_plugin_options_with_placeholder() {
    $this->store->load();
    $this->store->setOption('threshold', 100);
    $this->store->setOption('effect', 'none');
    $this->store->setOption('skipInvisible', false);
    $this->store->setOption('placeholder', 'transparent.gif');

    $actual = $this->placer->getPluginOptions(null);

    $this->assertEquals(100, $actual['threshold']);
    $this->assertEquals('none', $actual['effect']);
    $this->assertEquals(false, $actual['skipInvisible']);
    $this->assertStringEndsWith('wp-lazy-load/transparent.gif', $actual['placeholder']);
  }

}
