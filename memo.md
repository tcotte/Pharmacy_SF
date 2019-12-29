Eiffage project
===============



FOSUserBundle
-------------

Find various roads of FOSUserBunde functions 
```
php bin/console debug:router
```

User creation
```
php bin/console fos:user:create
```

Promote user
```
$ php bin/console fos:user:promote testuser ROLE_ADMIN
```


Database
---------------
1. Create database
php bin/console doctrine:database:create

2. Create an entity
php bin/console generate:doctrine:entity AppBundle:Article

3. Send the entity to the database
php bin/console doctrine:schema:update --force

4. Regenerate entity
php bin/console doctrine:generate:entities AcmeBundle:Entity






Controller
----------
Create a controller
php bin/console generate:controller --no-interaction --controller=AppBundle:Article


Form 
---
1. Generate a form linked with an entity
php bin/console generate:doctrine:form AcmeBlogBundle:Post