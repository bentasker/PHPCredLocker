PHPCredLocker
---------------

See http://www.bentasker.co.uk/documentation/phpcredlocker/163-phpcredlocker for
documentation.

Copyright (C) 2012 B Tasker
Released under GNU GPL V2 - http://www.gnu.org/licenses/gpl-2.0.html - All
rights not  explicitly permitted by the license are reserved

------------------------------

I'm not an interface designer, so the template is very rough around the edges.
It's designed to support custom templates though so you can skin and brand as
you see fit.

Passwords are encrypted with either OpenSSL or MCrypt (depending what you have
available). The system is intended for use over a https connection, though steps
have been taken to help reduce the likelihood of credential compromise over a
http connection. Still it's _STRONGLY_ recommended that connections be made over
https to ensure that all credentials are protected in transit.

You can view a demo at http://demo.bentasker.co.uk/PHPCredLocker/ including all
developed plugins.