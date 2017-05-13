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


SUPPORT REQUESTS
----------------

Before posting a support request, carefully read the installation
instructions provided in module documentation page.

Before posting a support request, check Recent log entries at
admin/reports/dblog

When posting a support request, please inform if you were able to see any errors
in Recent log entries.
