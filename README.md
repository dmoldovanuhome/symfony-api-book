# API BOOK

Project created on Symfony 5.4, using FOSRestBundle, for demonstration an elementary use of API.

### Start Docker
To start your database container run
```text
docker-compose up -d
```

### Fill DB
insert into DB 1 000 000 fake records with BookFixtures:
```text
symfony console doctrine:fixtures:load
```

### Auth
Security is configured so that endpoints of method type `GET` to be public. Methods:
`POST, PUT, DELETE` require authorization.
For demo, here the user is `in_memory` (hardcoded), but in real project this is not recommended, as noted in Symfony's
docs. 
To access secured endpoints, use e.g. Postman, in tab 'Authorization' select `Basic Auth`,
and fill in fields `Username`: `api_user`, `Password`:`qwerty`. Click button `Preview Request` to update header
`Authorization`.

Credentials are hardcoded in `config/packages/security.yaml`:
```yaml
in_memory:
    memory:
        users:
            api_user: { password: 'qwerty', roles: [ 'ROLE_API_USER' ] }
```
here you can change credentials, or add new user.

Note: Better method of authorization is to use the tokens of users from DB.


### API documentation
The documentation is made by NelmioApiDocBundle and Swagger UI.  You can access it by 
[app.domain/api/doc](http://app.domain/api/doc) directly in browser w/o auth.
The documentation is created from annotations written in Controller's methods.