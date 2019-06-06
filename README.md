# Slim Framework Starter with Authentication
A starting point for an MVC application using the Slim framework written in OOP Php 7.

It has basic authentication, sign-{in,up,out} routes, CSRF protection, Bootstrap 4, && not much else.

## Set Up

* Clone the repo
* Create a database with the necessary fields

```
CREATE TABLE users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  email VARCHAR(100) NOT NULL,
  password VARCHAR(100) NOT NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

* Install the dependencies: `composer install`
* Update `src/settings.php` w/ yer DB deetz
* Point Apache2 | Nginx @ `/public`
