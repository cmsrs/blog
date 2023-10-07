# BLOG 

Writing a Blog System in Laravel


# INSTALLATION

download
```bash
git clone  https://github.com/cmsrs/blog.git
cd blog
```

install php dependency
```bash
composer install
```

set up database in .env file, you can also add EXTERNAL_API, for example:
```bash
EXTERNAL_API="https://candidate-test-rs.com/api.php"
```

install js dependency
```bash
npm install
npm run dev
```

migrate data
```bash
php artisan migrate
```

create admin admin@example.com with password: secret123
```bash
php artisan   app:create-admin  admin@example.com secret123
```

start server
```bash
php artisan serve
```

# RUN TESTS

set up database in .env.testing file
```bash
./vendor/bin/phpunit
```

# To improve performance:

*    Utilize pagination data in the user zone.
*    Implement 'eager loading' to retrieve frontend data.
*    Create an index on the 'publication_date' database column.
*    Employ caching to display frontend data.
*    Utilize pagination data on the frontend site.
