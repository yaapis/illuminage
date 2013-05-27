<?php
use Illuminate\Container\Container;
use Illuminage\Facades\Illuminage;

abstract class IlluminageTests extends PHPUnit_Framework_TestCase
{
  protected $hash = '5bc55bd291fdac30ff0ed10958f2aa04.jpg';
  protected static $app;

  public static function setUpBeforeClass()
  {
    $app = new Container;

    $app->bind('config', function() {
      return Mockery::mock('config', function($mock) {
        $mock->shouldReceive('get')->with('config.image_engine', '')->andReturn('Gd');
        $mock->shouldReceive('get')->with('config.cache_folder', '')->andReturn('');
      });
    });

    $app['path.public'] = 'tests/public';

    $app->bind('illuminage', function($app) {
      return new \Illuminage\Illuminage($app);
    });

    Illuminage::setFacadeApplication($app);
  }

  public function setUp()
  {
    $this->cache = new \Illuminage\Cache(Illuminage::getFacadeRoot());
    $this->image = Illuminage::image('foo.jpg');
    $this->thumb = Illuminage::thumb('foo.jpg', 100, 100);
  }

  public function tearDown()
  {
    $testThumb = __DIR__.'/public/'.$this->hash;
    if (file_exists($testThumb)) unlink($testThumb);
  }
}