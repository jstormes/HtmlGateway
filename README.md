# HtmlGateway
A simple Html Gateway library.

## Usage

  PHP is itself a template engine.  Rather than reinvent the wheel, I used PHP itself as the template language and
wrapped it in design patterns I liked.  This package is my favorite design pattern packaged in a nice composer format.

  There are three core concepts in this pattern, templates, helpers, and post back processing.  

  A template is nothing more than an arbitrary text file wrapped in a PHP class.  This lets us use PHP interspersed
inside the text.  Templates can be included inside templates allowing the text to be built in blocks.  If this sounds
familiar, it is.  This is basically what PHP does.  As this template is inside a PHP object it has it’s "scope"
set to `$this->`.  It would be very bad practice to use anything outside the scope directly in a template.

## Command Line Docker Quick Start:

* Make sure you have Docker and Docker-Compose installed on your workstation.
    * [https://www.docker.com/community-edition](https://www.docker.com/community-edition)
* Run composer install:
    * BASH (Linux/OS X)    `docker run --rm -it -v $(pwd):/app composer install`
    * PowerShell (Windows) `docker run --rm -it -v ${pwd}:/app composer install`
    * CMD (Old Windows)    `docker run --rm -it -v %cd%:/app composer install`
* Run PhpUnit:
    * BASH (Linux/OS X)    `docker run --rm -it -v $(pwd):/app jstormes/phpunit -c phpunit.xml.dist`
    * PowerShell (Windows) `docker run --rm -it -v ${pwd}:/app jstormes/phpunit -c phpunit.xml.dist`
    * CMD (Old Windows)    `docker run --rm -it -v %cd%:/app jstormes/phpunit -c phpunit.xml.dist`

### Example of instantiating a template:

### Example of including a template inside a template:

### Example using for formatting email message:

  A helper is a class that contains logic that you want to use over and over.  Such logic might include translating
between languages or displaying dates and currencies in a local format.

### Example of a translate helper:

### Example of a date helper:

### Demo video at:

### Blog Post at:


## Unit testing

### To build the unit testing environment run:

`docker-composer run build`

### To run the unit tests at the command line run:

`docker-compose run phpunit`

### Unit testing with PhpStorm:


### Video of Unit Testing:


### GitHub Page:
https://github.com/jstormes/HtmlGateway

### Packagist Page:
https://packagist.org/packages/jstormes/html-gateway



