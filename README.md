Welcome to my approach to create a basic addressbook with Symfony 3.4

Installation
========================

1. Clone or download repository

    https://github.com/macstens/symfony-addressbook

2. Run installation scripts to install composer, create database based on fixtures data already provided, install yarn packages and create first encore webpack build. Note: skip all questions by composer as a local environment will be created and everything else is already set in the config files.

	sh bin/install.sh

3. Run encore when needed

    yarn run encore dev

4. Run server and go to http://localhost:8000/ 

    bin/console server:run

Login
-----------------

**Use this data to log in**

 username : admin
 password : kitten

Used technologies
--------------

The Symfony Standard Edition is configured with the following defaults:

  * Symfony 3.4
  * Twig
  * Doctrine
  * PHP 7.0.8

Any questions or feedback is welcome on github
