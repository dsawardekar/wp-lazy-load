<?php

namespace WpLazyLoad;

use Encase\Container;

class OptionsValidatorTest extends \WP_UnitTestCase {

  public $container;
  public $pluginMeta;
  public $validator;

  function setUp() {
    parent::setUp();

    $this->pluginMeta = new PluginMeta('wp-lazy-load.php');
    $this->container = new Container();
    $this->container
      ->object('pluginMeta', $this->pluginMeta)
      ->singleton('optionsValidator', 'WpLazyLoad\OptionsValidator');

    $this->validator = $this->container->lookup('optionsValidator');
  }

  function test_it_has_plugin_meta() {
    $this->assertSame($this->pluginMeta, $this->validator->pluginMeta);
  }

  function test_it_knows_if_custom_stylesheet_is_absent() {
    $actual = $this->validator->isLazyPlaceholder('placeholder', 'placeholder.gif', null);
    $this->assertFalse($actual);
  }

  function test_it_can_load_custom_rules() {
    $this->validator->loadCustomRules();

    $validator = new \Valitron\Validator(array('placeholder' => 'placeholder.gif'));
    $validator->rule('lazyPlaceholder', 'placeholder');

    $this->assertFalse($validator->validate());
  }

  function test_it_can_validate_valid_user_input() {
    \Arrow\OptionsManager\CustomValitronRules::load();
    $input = array(
      'threshold' => '400',
      'effect' => 'fadeIn',
      'skipInvisible' => '1',
      'placeholder' => ''
    );

    $actual = $this->validator->validate($input);
    $this->assertTrue($actual);
  }

  function test_it_can_validate_invalid_user_input() {
    \Arrow\OptionsManager\CustomValitronRules::load();
    $input = array(
      'threshold' => '400000',
      'effect' => 'foo',
      'placeholder' => 'foo.gif'
    );

    $actual = $this->validator->validate($input);
    $this->assertFalse($actual);

    $this->assertEquals(3, count($this->validator->errors()));
  }

}
