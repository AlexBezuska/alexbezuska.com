---
author: admin
comments: true
date: 2013-08-29 00:33:34+00:00
layout: post
link: http://blog.alexbezuska.com/jquery-timepicker-and-another-quicktip/
slug: jquery-timepicker-and-another-quicktip
title: jquery.timepicker and another quickTip
wordpress_id: 110
categories:
- javascript
- tips
tags:
- css
- javascript
- jQuery
- tips
---

So here is another useful jQuery plugin I found while doing a signup form project at work, the jquery.timepicker.  As you may have guessed - this is similar to the date picker  componant in jQueryUI, but as the name implies it is for Times only. This particular project called for me to create a means for the user to enter a time range they were available for an event. I started to create my own custom UI for this, but then the lack of  time made me search for an existing tool.  After a quick search I found[ jquery.timepicker](http://jonthornton.github.io/jquery-timepicker/) by [John Thornton](http://jonthornton.com/), here is the main demo site: [jQuery TImePicker](http://jonthornton.github.io/jquery-timepicker/)

I really love the simplicity of the plugin, I simply created a class called "timepicker" and gave that class to each of the input elements on the page that needed it.
Then I added this snippet of jQuery to the page:


All you need to get started once jquery.timepicker is included on your page:
```
    $('.timepicker').timepicker();
```

This is all you need to start a very basic time picker, but I wanted to restrict the user to a certain range, so I used the parameters that John describes on the [jquery.timepicker](http://jonthornton.github.io/jquery-timepicker/) site:

```    
    $('.timepicker').timepicker({
    	'disableTimeRanges': [
        ['12am', '10am'],
        ['4:30pm', '11:59pm'],
      ]
    });
```



## quickTip: Out of sight - Out of Mind


![jquery.timepicker](http://jslou.org/wp-content/uploads/2013/08/Screen-Shot-2013-08-28-at-7.01.31-PM.png)
Let's ditch these non-selectable times, and lower our chances of  confusing the user.


This worked great, but it does not hide the disabled times, it only grays them out and prevents selection, so today's quickTip is how to remove them from sight, and provide a better user experience.


I found out that John wrote [jquery.timepicker](http://jonthornton.github.io/jquery-timepicker/) to give disabled times a css class of "ui-timepicker-disabled", so it's very easy to hide these elements using either CSS or jQuery.

[![jquery.timepicker 2](http://jslou.org/wp-content/uploads/2013/08/Screen-Shot-2013-08-28-at-6.59.15-PM.png)](http://jslou.org/wp-content/uploads/2013/08/Screen-Shot-2013-08-28-at-6.59.15-PM.png)
Here is the result, only see what you can pick, only pick what you can see.


I have noted both methods below:




```
// The CSS Method

.ui-timepicker-disabled{ display:none; }





// The jQuery Method

$('.ui-timepicker-disabled').hide();
```

Have a great day, and if you have a quick tip be sure to pm me on twitter @alexbezuska and I can share it on the site.

**Learn. Create. Explore. Code. **
_-Alex Bezuska_
