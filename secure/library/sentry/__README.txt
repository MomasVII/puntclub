////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// File: __README.txt
// Purpose: Explaining sentry framework library
// Author: Gordon MacK
// On Behalf: Raremedia Pty Ltd (Andrew Davidson)
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

Note: Please refer to usage.php for usage information, this text file offers general and conceptual information.

This library is used to track end users actions in an effort to spot malicious users or at the very least, have a place
to start if an end user manages to do some damage to a site.

There is only so much we can legally and morally do in terms of catching a user's information, but anything is better
than nothing.

Generally, if the site has a database you should also be using this library.

Gotchya: This library will currently only log to a database table. This library may be expanded in the future to log to a
flat file.