# Tennis API

This is an API developed in PHP meant to demonstrate a working model for an
API Driven website and mobile application. The API utilizes a construction of
functions in the url to allow more dynamic queries.

## Get Started.
- Download the project from github
- Create a mysql database and user for the API
- Create a config.php file and define `DB_HOST` `DB_USER` `DB_PASS` `DB_NAME`
- Edit the makefile's MYSQL command to reflect those same values
- Type `make data` and the API is set up for basic tests.

## What it can do.
The API is driven by the concept of sets of data. This comes in the form of
the class ApiDataSet which determines functionality for large groups of data.
Such as 'orderby' and 'first' or 'last'.

Each class determines its own functionality but must also implement the primary
methods of the API,
- GET
- PUT
- POST
- DELETE

As the functionality of these methods is implemented per class, complicated
sql queries such as joins may be used to create an instance. These are typically
placed in the `self::getInstance( $id )` function.

## Example

`/member/` -- Will return all members, as nothing is specified.

`/member/1` -- Will return the member with the id of 1

`/member/orderby/age/DESC` -- Will return all members but sorted by age.

`/member/orderby/age/DESC/first` -- Will return the olderst member.
