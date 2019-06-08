---
author: admin
comments: false
date: 2017-04-05 13:52:49-04:00
layout: post
link: http://blog.alexbezuska.com/finding-home-devlog/
slug: finding-home-devlog
title: Finding Home Devlog - Game animation with Spriter
wordpress_id: 709
---

I originally set out to re-create my ld37 game Finding Home in Unity under the assumption that the code would be my largest hurdle and I should take that on first. The first couple sessions of working on the project went well and I had a game mostly up-to-par with the jam version, then disaster... It seemed like the more I worked on the game, the more things starting breaking and I didn't fully understand the example code in the utility I was using for my dialogue so trying to fix things or make changes was really hit-or-miss. I basically have come to the conclusion that I will create my own branching dialogue system and start simple as I did for the Superpowers version of the game. I have not started on that yet but I might this week.

Having been a bit frustrated the last couple programming sessions I took some advice from a trusted source and switched to working on art and animation. I recently worked on a sample game to impress a client during a contract proposal and the team I was on used [Spriter](https://brashmonkey.com/), a 2d sprite animation tool. Spriter, while it has it's quirks, reminds me of the best parts of Flash and I really think it is my new go-to for creating game animations.

I started off by doing some planning, the goal is to have a game that looks and plays well on iPad, that is my target for sure. I took some notes on iPad and a few other tablet's screen resolutions and figured out max sizes for my sprites and avatars. I began working on the main character, the kid snail, and redrew the avatar art basically the same but around 16 times as large. I painted in Photoshop using the pencil tool at various sizes just as I did during the jam, this produces a really nice "blob paint" look which I like a lot. When crating the artwork I considered the layers carefully as they would later be exported individually for use in Spriter.

![Finding Home snail avatar spriter](/images/2017/04/avatar-snail-spriter.gif)

Once in Spriter I started on 2 animations, an "idle", and a "talking" state for the player's avatar. I recently found out there is a Spriter importer  called [SpriterDotNet](https://github.com/loodakrawa/SpriterDotNet) that will let you use the .scml files that Spriter creates along with the original images so the animation is done live in Unity - no spritesheets! The importer even allows you to transition smoothly between animations which is impressive to look at.

![Finding Home snail avatar spriter](/images/2017/04/avatar-snail-idle.gif)

![Finding Home snail avatar spriter](/images/2017/04/avatar-snail-talk.gif)

Overall I am content with the progress I made on the first avatar, and I am looking forward to redoing the others.

-Alex Bezuska
Follow Alex on twitter: [@alexbezuska](http://twitter.com/alexbezuska)

About Finding Home
Finding Home is an upcoming interactive storybook game that children can play along with their parents. Coming soon to the iOS and Android.
[http://findinghomegame.com](http://findinghomegame.com)
