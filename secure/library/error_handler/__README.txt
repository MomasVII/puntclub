////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// File: __README.txt
// Purpose: Explaining error hander framework library
// Author: Gordon MacK
// On Behalf: Raremedia Pty Ltd (Andrew Davidson)
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

Note: Please refer to usage.php for usage information, this text file offers general and conceptual information.

This library is designed to handle all PHP errors (including fatal) in the nicest way possible. It allows errors to be
turned on for live sites without being accessable to end users, this means a persistent error record can be kept 
revealing any problems that didn't present themselves during testing.

It relies on a mysql database being available.