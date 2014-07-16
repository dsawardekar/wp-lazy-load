<?php

namespace WpLazyLoad;

use Encase\Container;

class ImageSourceReplacerTest extends \WP_UnitTestCase {

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
      ->singleton('replacer', 'WpLazyLoad\ImageSourceReplacer');

    $this->replacer = $this->container->lookup('replacer');
    $this->store    = $this->container->lookup('optionsStore');
  }

  function test_it_has_plugin_meta() {
    $this->assertSame($this->pluginMeta, $this->replacer->pluginMeta);
  }

  function test_it_has_options_store() {
    $this->assertSame($this->store, $this->replacer->optionsStore);
  }

  function test_it_can_get_path_to_default_placeholder() {
    $actual = $this->replacer->getPlaceholder();
    $this->assertStringEndsWith('wp-lazy-load/img/placeholder.gif', $actual);
  }

  function test_it_can_get_path_to_custom_placeholder() {
    $this->store->load();
    $this->store->setOption('placeholder', 'transparent.gif');

    $actual = $this->replacer->getPlaceholder();
    $this->assertStringEndsWith('wp-lazy-load/transparent.gif', $actual);
  }

  function test_it_add_no_script_tag_with_replaced_img() {
    $html = "<img src='foo.jpg'>";
    $actual = $this->replacer->replace($html);

    $matcher = array(
      'tag' => 'noscript',
      'descendant' => array(
        'tag' => 'img',
        'attributes' => array('src' => 'foo.jpg')
      )
    );

    $this->assertTag($matcher, $actual);
  }

  function test_it_replaces_img_src_with_placeholder() {
    $html = "<img src='bar.jpg'>";
    $actual = $this->replacer->replace($html);

    $matcher = array(
      'tag' => 'img',
      'attributes' => array('src' => 'regexp:/placeholder.gif$/')
    );

    $this->assertTag($matcher, $actual);
  }

  function test_it_adds_placeholder_to_img_tag() {
    $html = "<img src='apple.jpg'>";
    $actual = $this->replacer->replace($html);

    $matcher = array(
      'tag' => 'img',
      'attributes' => array('data-original' => 'apple.jpg')
    );

    $this->assertTag($matcher, $actual);
  }

  function test_it_can_replace_multiple_images() {
    $html  = "<img src='lorem.jpg'>";
    $html .= "<img src='ipsum.jpg'>";
    $html .= "<img src='dolor.jpg'>";
    $actual = $this->replacer->replace($html);

    $matcher = array(
      'tag' => 'img', 'attributes' => array(
        'src' => 'regexp:/placeholder.gif$/',
        'data-original' => 'lorem.jpg'
      ),
    );

    $this->assertTag($matcher, $actual);

    $matcher = array(
      'tag' => 'img', 'attributes' => array(
        'src' => 'regexp:/placeholder.gif$/',
        'data-original' => 'ipsum.jpg'
      ),
    );

    $this->assertTag($matcher, $actual);
  }
}
