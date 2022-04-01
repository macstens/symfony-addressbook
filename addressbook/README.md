Welcome to my approach to create a basic addressbook with Symfony 3.4

Installation
========================

1. Clone or download repository

    https://github.com/macstens/symfony-addressbook

2. Execute composer

	composer install

3. Copy path to sqlite database to /app/config/parameters.yml:

    database_path: '%kernel.project_dir%/var/data/addressbook.db'

4. Run installation script to create database based on fixtures data already provided

	sh bin/install.sh

5. Run server and go to http://localhost:8000/ 

    bin/console server:run

Login
-----------------

**Use this data to log in**

 username : admin
 password : kitten

 username : user
 password : user


Used technologies
--------------

The Symfony Standard Edition is configured with the following defaults:

  * Symfony 3.4
  * Twig
  * Doctrine
  * PHP 7.0.8

Any questions or feedback is welcome on github or email (which you or the HR team should know)