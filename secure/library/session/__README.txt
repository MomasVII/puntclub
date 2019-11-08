////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// File: __README.txt
// Purpose: Explaining session framework library
// Author: Gordon MacK
// On Behalf: Raremedia Pty Ltd (Andrew Davidson)
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

Note: Please refer to usage.php for usage information, this text file offers general and conceptual information.

This library is used to handle sessions. It changes the default behavior of the sessions system drastically, the most
notable of which is that it causes sessions to be stored in a db table rather than the file system.

It is considerably faster and more secure than the standard sessions method. It also doesn't affect the way you
interact with $_SESSION, operation code essentially remains the same.

Gotchya: This library needs the database definitions to function but not the mysqli library or the validate class, this is
explained further in the usage page.