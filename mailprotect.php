<?php
/*
Plugin Name: nanomailprotect
Plugin URI: https://github.com/rnlf/nanomailprotect.git
Description: Tiny Wordpress plugin to obfuscate your mail address and decode with JavaScript.
Version: 1.0.0
Author: Florian Keßeler
Author URI: http://kesseler.org
License: MIT

Tiny plugin that adds a [mailprotect] shortcode. It removes any at signs and dots
in the tag's content with " (remove this) at " and " dot ", making it look like
a normal part of a sentence, so 

  "[mailprotect]foobar@example.com[/mailprotect]"

becomes

  "foobar (remove this) at example dot com"

In addition, it adds a short JavaScript snippet that replaces the obfuscated
address with a proper mailto:// link and the actual address. In this snippet,
the address is protected by base64 encoding first the address and then the
generated JavaScript to make it harder for crawlers to find the address.

This method of replacing the address in this way may not be enough to fight off
the smartest bots, but in some legislations (like Germany) you are required to
provide a readable mail address in your imprint (and there are court decisions
that say you cannot use images because it must be accessible to blind people, too.

So I assume this is the next best way to protect your address.



Copyright 2017 Florian Keßeler

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
of the Software, and to permit persons to whom the Software is furnished to do
so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/


$nanomailprotect_counter         = 0;

// Don't put any whitespace at beginning or end of this to avoid breaking the user's layout.
$nanomailprotect_html_template   = '<span id="%s" class="nanomailprotect">%s</span><script lang="javascript">eval(atob("%s"))</script>';

$nanomailprotect_at_replacement  = '&nbsp;(remove&nbsp;this)&nbsp;at&nbsp;';
$nanomailprotect_dot_replacement = '&nbsp;dot;&nbsp;';
$nanomailprotect_js_template     = <<<EOT
  var elem = document.getElementById("%s");
  if(elem) {
    var addr = atob("%s");
    elem.innerHTML='<a href="mailto://' + addr + '">' + addr + '</a>';
  }
EOT;


function nanomailprotect_build_tag( $atts, $addr ) {
  global $nanomailprotect_counter;
  global $nanomailprotect_js_template;
  global $nanomailprotect_html_template;
  global $nanomailprotect_at_replacement;
  global $nanomailprotect_dot_replacement;

  $nanomailprotect_counter++;

  $id       = 'nanomailprotect_' . $nanomailprotect_counter;

  $obf_addr = str_replace(array('@', '.'),
                          array($nanomailprotect_at_replacement,
                                $nanomailprotect_dot_replacement),
                          $addr);

  $b64_addr = base64_encode($addr);

  $js       = sprintf($nanomailprotect_js_template, $id, $b64_addr);
  $b64_js   = base64_encode($js);

  return sprintf($nanomailprotect_html_template, $id, $obf_addr, $b64_js);
}

add_shortcode( 'mailprotect', 'nanomailprotect_build_tag' );
