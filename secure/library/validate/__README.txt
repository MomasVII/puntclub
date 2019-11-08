////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// File: __README.txt
// Purpose: Explaining validate framework library
// Author: Gordon MacK
// On Behalf: Raremedia Pty Ltd (Andrew Davidson)
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

Note: Please refer to usage.php for usage information, this text file offers general and conceptual information.

This library is designed to verify, filter, pass and handle all data traveling to the server from the client. Ideally,
all data should travel through this library before being processed in any way.

This library plays a major role in the security of raremedia's sites.

There is a file called 'magic' in this directory that must stay with the validate library in order for mime functions to
work. A similar file is bundled with most PHP installs but is not consistent across versions and therefore servers, so
instead we are using a copy extracted directly from PHP source in an effort to force consistent behaviour.