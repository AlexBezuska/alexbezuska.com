---
author: admin
comments: true
date: 2012-11-11 15:59:11+00:00
layout: post
link: http://blog.alexbezuska.com/jquery-tip-different-keys-for-the-same-thing/
slug: jquery-tip-different-keys-for-the-same-thing
title: jQuery Tip - Different Keys for the same Thing
wordpress_id: 231
categories:
- javascript
tags:
- game design
- jQuery
- keys
---

So here is something I was struggling with and found a solution that might help other people trying to do the same thing. My goal was to have two keyboard keys do the same thing, in this case move the character in my game. So a player could use W A D S or the arrow keys. My code before was this:

    
    
                     case 40:  //down
    	         	squid.css('top' , position.top + 50 + 'px')
    	         		.removeClass("idle")
    	         		.addClass("down");
    	         		break;
    
    	         case 83:  // also down
    	         	squid.css('top' , position.top + 50 + 'px')
    	         		.removeClass("idle")
    	         		.addClass("down");
    	         		break;


But you can do this instead to help reduce the amount of code, and organize your code better.

    
                   
                     case 40:
    	         case 83:  //Use either of these keys
    	         	squid.css('top' , position.top + 50 + 'px')
    	         		.removeClass("idle")
    	         		.addClass("down");
    	         		break;


I am still trying to figure out how to have key combos like W + D to move up-right or A + S to move down-left so my character can have 8 different directions to move, making it a bit more fluid. If you know how to do this drop me a comment, if I figure it out on my own or have time to google around for it some more, I will post it here.
