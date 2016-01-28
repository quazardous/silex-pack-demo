# silex-pack-demo
A POC Silex pack app (try to say it fast, not easy...) !

See [Silex pack](https://github.com/quazardous/silex-pack) for more informations.

The demo uses the [Silex user pack](https://github.com/quazardous/silex-user-pack) to test secure access.

It shows how to implement the user authentication mercanism with forms and database users.

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
php ./app/console.php silex:user:fixture
php ./app/console.php assetic:dump
php ./app/console.php pack:symlinks

# monitor e-mails
java -jar app/bin/fakeSMTP-2.0.jar -m -p 2525
```

## Test

- http://localhost/silex-pack-demo/web/acme/demo/hello/david : test translation and routing
- http://localhost/silex-pack-demo/web/acme/demo/foo : test template override and assets
- http://localhost/silex-pack-demo/web/acme/demo/items : test with doctrine
- http://localhost/silex-pack-demo/web/acme : user pack with security/firewall
    - users: **johndoe@sup.net/johndoe** ans **admin@sup.net/admin**


## Play

### Assets

You may want to dump assets:

```
php ./app/console.php assetic:watch
```

### Doctrine

```
# modify entity and update setters
vendor/bin/doctrine orm:generate-entities src/
vendor/bin/doctrine orm:generate-proxies
# update the db
vendor/bin/doctrine orm:schema-tool:update
```
Need a good SQLite admin tool: [SQLite Browser](http://sqlitebrowser.org/).

