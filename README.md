# nanomailprotect
Tiny Wordpress plugin to obfuscate your mail address and decode with JavaScript.

## Description
Tiny plugin that adds a [mailprotect] shortcode. It removes any at signs and dots
in the tag's content with " (remove this) at " and " dot ", making it look like
a normal part of a sentence, so 

&nbsp;&nbsp;"[mailprotect]foobar@example.com[/mailprotect]"

becomes

&nbsp;&nbsp;"foobar (remove this) at example dot com"

In addition, it adds a short JavaScript snippet that replaces the obfuscated
address with a proper mailto:// link and the actual address. In this snippet,
the address is protected by base64 encoding first the address and then the
generated JavaScript to make it harder for crawlers to find the address.

This method of replacing the address in this way may not be enough to fight off
the smartest bots, but in some legislations (like Germany) you are required to
provide a readable mail address in your imprint (and there are court decisions
that say you cannot use images because it must be accessible to blind people, too.

So I assume this is the next best way to protect your address.
