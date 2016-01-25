<?php
namespace Acme;

use Quazardous\Silex\PackableApplication;
use Silex\Application\TwigTrait;
use Silex\Application\UrlGeneratorTrait;
use Silex\Application\FormTrait;
use Quazardous\Silex\Application\NamedFormTrait;

class Application extends PackableApplication {
    use TwigTrait;
    use UrlGeneratorTrait;
    use FormTrait;
    use NamedFormTrait;
}