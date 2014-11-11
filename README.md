PHPCredLocker
---------------

See http://www.bentasker.co.uk/documentation/phpcredlocker/163-phpcredlocker for
documentation.

Copyright (C) 2012 B Tasker
Released under GNU Affero GPL V3 - http://www.gnu.org/licenses/agpl-3.0.txt - All
rights not  explicitly permitted by the license are reserved

(Version 1 was released under GNU GPL V2, later versions are only available under the AGPL)

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



Issue Tracking
---------------

The project is managed within a private JIRA instance, however a HTML mirror of issues/features is maintained at http://projects.bentasker.co.uk/jira_projects/browse/PHPCRED.html

If you think you've found a bug, or want to ask a question, send an email to phpcredlocker **AT** bentasker.co.uk




Why the AGPL?
---------------

As a rule, I release most software under the GNU GPL V2 (or sometimes 3), PHPCredlocker is different however.

Given the intended use-case of CredLocker, it's not the operator who is 'at risk'. It's those who own the systems/sites that the stored credentials allow access to. Therefore, it's only right that they at least be given opportunity to inspect the source of the system holding their credentials. 

The AGPL works in exactly the same way as the GPL, but with an additional caveat - If you're running CredLocker as a web service, the users must be able to download a copy of the source.
