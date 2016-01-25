<?php

include __DIR__.'/../vendor/autoload.php';
use Acme\Application;
use Silex\Provider\DoctrineServiceProvider;
use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Acme\DemoPack\AcmeDemoPack;
use Silex\Provider\TwigServiceProvider;
use Quazardous\Silex\Provider\AsseticServiceProvider;
use Assetic\Filter\Yui\CssCompressorFilter;
use Silex\Provider\LocaleServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Assetic\Filter\Yui\JsCompressorFilter;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\SwiftmailerServiceProvider;
use Quazardous\Silex\UserPack\SilexUserPack;
use Acme\SecurePack\AcmeSecurePack;
use Silex\Provider\MonologServiceProvider;

/**
 * @var \Acme\Application $app;
 */
$app = new Application();

$app['debug'] = true;

$app['path_to_web'] = __DIR__ . '/../web';

$app->register(new MonologServiceProvider(), [
    'monolog.logfile' => __DIR__.'/demo.log',
]);

$app->register(new DoctrineServiceProvider, [
    'db.options' => [
        'driver' => 'pdo_sqlite',
        'path' => __DIR__ . '/db/acme_demo.db',
    ],
]);

$app->register(new DoctrineOrmServiceProvider, [
    'orm.proxies_dir' => __DIR__ . '/cache/orm/proxies',
]);

// we register a main twig.path
// we will search for overriden template in app/views/<namespace> which is app/views/AcmeDemo for our little demo pack
$app->register(new TwigServiceProvider(), ['twig.path' => __DIR__ . '/views']);

$app['assets_url'] = 'assets';

$app['twig'] = $app->extend('twig', function($twig, $app) {
    $twig->addFunction(new \Twig_SimpleFunction('asset', function ($asset, $sub = '') use ($app) {
        // implement whatever logic you need to determine the asset path
        $base = isset($app['request']) && $app['request'] ? $app['request']->getBasePath() . '/' : '';
        if ($sub) $base .= trim($sub, '/') . '/';
        return sprintf('%s%s', $base, ltrim($asset, '/'));
    }));

    return $twig;
});

// Silex 1.x style
$app['request'] = $app->factory(function ($app) {
    return $app['request_stack']->getCurrentRequest();
});

$app->register(new AsseticServiceProvider(),
    [
        'assetic.path_to_web' => __DIR__ . '/../web/assets',
        'assetic.path_to_source' => __DIR__ . '/assets',
        'assetic.options' => [
            'debug' => true,
            'formulae_cache_dir' => __DIR__ . '/cache/assetic/formulae',
            'auto_dump_assets' => false,
        ],
        'assetic.formulae' => [
        ],
    ]
);

$app->extend('assetic.filter_manager', function ($fm, $app) {
    $fm->set('yui_css', new CssCompressorFilter(__DIR__ . '/../vendor/bin/yuicompressor.jar'));
    $fm->set('yui_js', new JsCompressorFilter(__DIR__ . '/../vendor/bin/yuicompressor.jar'));
    return $fm;
});

// this will make use of the magic _locale url parameter
$app->register(new LocaleServiceProvider);

$app->register(new TranslationServiceProvider(), [
    'locale' => 'fr',
    'locale_fallbacks' => ['en'],
]);

// provide security
$app->register(new SecurityServiceProvider(), [
    'security.firewalls' => [
        'secured' => array(
            'pattern' => '^/acme/secured',
            'form' => [
                // user pack will populate the missing mandatory options but you have to set the 'form' key.
                // 'login_path' => '/login',
                // not specifying the login_path here means that user pack has to provide the path and the controller
                // the route 'user.login' will be automatically created derivated from the '/login' path prefixed by 'user.'
                // 'check_path' => '/admin/login_check'
                // you can add all the custom scurity options you need
                'default_target_path' => '/acme/secured',
                //'failure_path' => '/login',
            ], 
            'logout' => [
                // user pack will populate the missing mandatory options but you have to set the 'logout' key.
                //'logout_path' => '/admin/logout',
                //'invalidate_session' => true,
                'target_url' => '/acme',
            ], 
            'users' => null, // if empty or not set, user pack will provide it for you with the built in Doctrine implementation.
        ),
    ],
    'security.role_hierarchy' => [
        'ROLE_ADMIN' => ['ROLE_USER'],
    ],
    'security.access_rules' => [
        ['^/acme/secured/admin', 'ROLE_ADMIN'],
        ['^/acme/secured', 'ROLE_USER'],
    ],
]);

// register the user pack wich try to make the authentication easier...
// the user pack will try to complete the 'security.firewalls' for you
$app->register(new SilexUserPack(), [
    'user.firewalls' => [
        // one or more firewalls to manage, see below
        'secured' => [
            // 'secured_mount_prefix' => '/admin' // user pack will try to guess it from the 'pattern' key
            // you can specify non default values for:
            // 'login_path' => '/login', // default
            // 'check_path' => '/check_login', // default, prefixed with 'secured_mount_prefix'
            // 'logout_path' => '/lougout', // default, prefixed with 'secured_mount_prefix'
            // 'invalidate_session' => true, // default
        ],
    ]    
]);

$app->register(new SwiftmailerServiceProvider(), [
    'swiftmailer.options' => [
        'host' => 'localhost',
        'port' => '2525',
    ]]);

// we register our demo pack:
// - this will mount all the controllers on the given prefix
// - this will register the pack with the given namespace in Twig
// - this will allow template override
// - this will expose the entities of the pack to Doctrine
// - this will add the pack's commands
// - this will add assetic stuff
// - this will ass translation stuff
$app->register(new AcmeDemoPack(), [
    'acme_demo.mount_prefix' => '/acme/demo',
]);

// we register the secure pack
$app->register(new AcmeSecurePack(), [
    'acme_secure.mount_prefix' => '/acme',
]);


return $app;
