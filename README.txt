SOCIAL AUTH LINKEDIN MODULE

INTRODUCTION
------------

Social Auth LinkedIn Module is a LinkedIn Authentication integration for Drupal.
It is based on the Social Auth Google and Social API projects, as well as in the
LinkedIn PHP Library developed by ashwinks

https://github.com/ashwinks/PHP-LinkedIn-SDK

It adds to the site:
* A new url: /user/login/linkedin
* A settings form on /admin/config/social-api/social-auth/linkedin page
* A LinkedIn Logo in the Social Auth Login block.

REQUIREMENTS
------------

This module requires the following modules:

 * Social Auth (https://drupal.org/project/social_auth)
 * Social API (https://drupal.org/project/social_api)

HOW IT WORKS
------------

User can click on the LinkedIn logo on the Social Auth Login block
You can also add a button or link anywhere on the site that points
to /user/login/linkedin, so theming and customizing the button or link
is very flexible.

When the user opens the /user/login/linkedin link, it automatically takes
user to LinkedIn for authentication. LinkedIn then returns the user to
Drupal site. If we have an existing Drupal user with the same email address
provided by LinkedIn, that user is logged in. Otherwise a new Drupal user is
created.

CREATE LINKEDIN APPLICATION
---------------

Go to https://www.linkedin.com/developer/apps and create a new application. After creating it, make sure both r_basicprofile and r_emailaddress permissions are checked.

The Client ID and Client Secret values will be required when configuring the module

Your Oauth 2.0 authorized redirect url will be
[your_base_url]/user/login/linkedin/callback


INSTALL SOCIAL AUTH LINKEDIN
-----------------------------

Install module using composer to take care of dependencies

composer require "drupal/social_auth_linkedin:~1.0"

Activate the Social Auth LinkedIn module and configure the Client ID and Client Secret acquired using the values acquired from LinkedIn.

The LinkedIn Auth button should now be appearing in the Social Auth Block


SUPPORT REQUESTS
----------------

Before posting a support request, carefully read the installation
instructions provided in module documentation page.

Before posting a support request, check Recent log entries at
admin/reports/dblog

When posting a support request, please inform if you were able to see any errors
in Recent log entries.

RUNNING TESTS
-----------------

Running PHPUnit tests

cd core
 ../vendor/bin/phpunit ../modules/contrib/social_auth_linkedin/tests/src/Unit/LinkedinAuthManagerTest.php

Running Functional Tests

php core/scripts/run-tests.sh --browser --verbose --class "Drupal\Tests\social_auth_linkedin\Functional\LinkedinAuthTest"