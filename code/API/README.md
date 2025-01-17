- `composer install`
- `php bin/console doctrine:database:drop --force --if-exists`
- `php bin/console doctrine:database:create`
- `php bin/console make:migration`
- `php bin/console doctrine:migrations:migrate`


## Lancement
 `symfony server:start`
