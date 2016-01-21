# silex-pack-demo
A POC Silex pack app (try to say it fast, not easy...) !

See Silex pack for more informations:
https://github.com/quazardous/silex-pack

## Install

```bash
# changeme
cd /path/to/htdocs

git clone git@github.com:quazardous/silex-pack-demo.git
cd silex-pack-demo
composer update
mkdir -p app/db/
touch app/db/acme_demo.db
vendor/bin/doctrine orm:schema-tool:create
vendor/bin/doctrine orm:generate-proxies
php ./app/console.php acme:demo:fixture
php ./app/console.php assetic:dump
php ./app/console.php pack:symlinks
```

## Test

- http://localhost/silex-pack-demo/web/acme/demo/hello/david : test translation and routing
- http://localhost/silex-pack-demo/web/acme/demo/foo : test template override and assets
- http://localhost/silex-pack-demo/web/acme/demo/items : test with doctrine


## Play

You may want to dump assets:

```
php ./app/console.php assetic:watch
```

