# BLOG 
Writing a Blog System in Laravel


# INSTALLATION

*
```bash
git clone  https://github.com/cmsrs/blog.git
cd blog
```

*
```bash
composer install
```

*
set up database in .env file
you can add EXTERNAL_API, for example:
EXTERNAL_API="https://candidate-test-rs.com/api.php"

*
```bash
npm install
npm run dev
```

*
```bash
php artisan migrate
```

create admin admin@example.com with password: secret123 :
```bash
php artisan   app:create-admin  admin@example.com secret123
```

```bash
php artisan serve
```

# RUN TESTS

set up database in .env.testing file

./vendor/bin/phpunit


# To improve performance:

*    Utilize pagination data in the user zone.
*    Implement 'eager loading' to retrieve frontend data.
*    Create an index on the 'publication_date' database column.
*    Employ caching to display frontend data.
*    Utilize pagination data on the frontend site.


