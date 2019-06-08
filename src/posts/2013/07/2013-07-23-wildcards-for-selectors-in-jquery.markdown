---
author: admin
comments: true
date: 2013-07-23 15:33:26-04:00
layout: post
link: http://blog.alexbezuska.com/wildcards-for-selectors-in-jquery/
slug: wildcards-for-selectors-in-jquery
title: jQuery and CSS 'Wildcard' element selection
wordpress_id: 80
categories:
- tips
tags:
- javascript
- jQuery
- tips
---

UPDATE 08-26-2013
No need for any quotes on either unless it is a number example [class$='23'], CSS and jQuery syntax is the same.


<blockquote>**Quick Start Guide:
**CSS and jQuery 'WIldcard' Syntax('type' = class or id):
**Starts with: [type^=keyword]  Ends with: [type$=keyword] contains: [type*=keyword]**</blockquote>


[ Try it yourself on CodePen!](http://codepen.io/AlexBezuska/pen/EtDJe)

This is a really awesome trick I found that you can do, comes in handy for dynamically incremented IDs :

To get all the elements starting with "awesomeSauce" you should use:

    
    <code>$("[id^=awesomeSauce]")</code>


To get those that end with "awesomeSauce"

    
    <code>$("[id$=awesomeSauce]")</code>


Also words for classes that start start with "awesomeSauce"  ex: "awesomeSauce123"

    
    <code>$('[class^=awesomeSauce]')
    </code>


Or if you have multiple classes on an object, like " best awesomeSauce123" or you want to find an id or class that _contains_ the keyword.

    
    <code>$('[class*=awesomeSauce]')</code>


Giving Credit where credit is due:

[http://stackoverflow.com/questions/5376431/wildcards-in-jquery-selectors](http://stackoverflow.com/questions/5376431/wildcards-in-jquery-selectors)
