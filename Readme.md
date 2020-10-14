# How to run?
## Prerequests

 - docker and docker-compose installed
 -  ports 3306,8000 are free

 
## running project

 1. download and extract dockerized.zip in a directory(example:
        **project**) 
 2. open command line and change your directory to **project**

> cd project
3. Then run following commands
> $docker-compose up --build

**installing project dependencies**

> $docker-compose run php composer install
>
**creating tables for main database**

> $docker-compose run php ./bin/console doctrine:migration:migrate
>
**add some fake data to database**
>
> $docker-compose run php ./bin/console  composer doctrine:fixtures:load
>
**creating tables for test database**
>
> $docker-compose run php ./bin/console  doctrine:migration:migrate --env=test

**running test**
> $docker-compose run php ./bin/phpunit

## Useful Links
Api Documentation

http://localhost:8000/api/doc

Fake Records Data in Json

http://localhost:8000/api/records

## Comments 
- ##### this implementation is focused on api development, not focusing on database
- ##### I just used a simple 2 table for working with records "I prefer uuid for interacting with records by using ramsey library"
- #####  in real environment i prefer to add .env and .env.test to the .ignore

 - ##### based on this rule in readme 'The list must return the complete information of every record matching the search query' it means also, no need for pagination!
   
 -  ##### also as HATEOAS is not mentioned in task, so is not implemented
-   ##### dont blame me on plain text passwords and unique username I know that but is a just very fast solution !
