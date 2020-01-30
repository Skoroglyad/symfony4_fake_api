1. set paswword for DB
DATABASE_URL="mysql://root:password@127.0.0.1:3306/app"

2.  php bin/console doctrine:database:create

3. composer install

4. php bin/console doctrine:migrations:diff

5. php bin/console doctrine:migrations:migrate

6. Postman Collection
https://www.getpostman.com/collections/952dbd13bb8874d3d378
