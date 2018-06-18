---
author: admin
comments: true
date: 2014-04-14 17:40:35+00:00
layout: post
link: http://blog.alexbezuska.com/turn-php-errors-on-via-an-htaccess-file/
slug: turn-php-errors-on-via-an-htaccess-file
title: 'Turn PHP errors on via an .htaccess file '
wordpress_id: 339
---

.htaccess:

    
    <code>php_flag display_startup_errors on
    php_flag display_errors on
    php_flag html_errors on
    php_flag  log_errors on
    php_value error_log  /home/path/public_html/domain/PHP_errors.log</code>
