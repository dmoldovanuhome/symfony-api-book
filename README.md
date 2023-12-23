# API BOOK

Project created on Symfony 5.4, using FOSRestBundle, for demonstration an elementary use of API.

### Start Docker
To start your database container run
```phpregexp
docker-compose up -d
```

### Fill DB
insert into DB 1 000 000 fake records with BookFixtures:
```phpregexp
symfony console doctrine:fixtures:load
```