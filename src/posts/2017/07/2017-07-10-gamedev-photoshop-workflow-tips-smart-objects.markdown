---
author: admin
comments: false
date: 2017-07-10 13:20:19+00:00
layout: post
link: http://blog.alexbezuska.com/gamedev-photoshop-workflow-tips-smart-objects/
slug: gamedev-photoshop-workflow-tips-smart-objects
title: GameDev Photoshop workflow Tips - Smart Objects
wordpress_id: 747
category: Game Development
tags:
- Game Development
- Kick Bot
- photoshop
- pixel art
- Smart Objects
- Workflow
coverPhoto: /images/2017/07/kick-bot-dx-devlog-photoshop-smart-object.png
---

One of the new steps in my workflow lately has been creating Smart Objects for each art element in the Photoshop files for my games.  Smart Objects are a lot like prefabs if you have an understanding of the Unity game engine.
<!--more-->

Smart Objects are like child Photoshop documents nested inside of a parent Photoshop document. You can turn any layer, selection of multiple layers,  or group into a smart object by right-clicking it in the layers menu and selecting “Convert to Smart Object”.

![Photoshop gamedev workflow smart object](/images/2017/07/kick-bot-dx-devlog-photoshop-smart-object.png)
How to create a Smart Object from any layer, layers, or group in Photoshop CC



To edit the contents of a smart object you simply double click the layer, it will open up what looks like another photoshop file and you can make your edits.

When you are editing a smart object; saving will update the parent document.

You can close the smart object you are editing (be sure to save if you need to) and you will see the changes instantly in the parent document.

The great thing about this workflow allows me to make duplicates of a spike or a wall piece for example and repeat it to create a simulated screenshot of my game, then when I edit the Smart Object all the other copies update as well.

![Simulated screenshot of Kick Bot DX by Two Scoop Games in Photoshop](/images/2017/07/kick-bot-dx-devlog-2017-06-05.png) Simulated screenshot of Kick Bot DX by Two Scoop Games in Photoshop

In the screenshot above of  Kick Bot DX, the walls were created using duplicate smart objects that will all update if one of them is edited.

Smart Objects act like their own Photoshop files but are stored int he parent .psd file so there are no extra files to manage.

Smart Objects can also have their own Smart Objects inside of them, BUT I recently tried to share a child of a child of two children of a parent and it unlinked them. This means Smart Objects inside of children cannot be shared.

If you would like to learn more about Smart Objects here are some great resources:





  * [https://helpx.adobe.com/photoshop/using/create-smart-objects.html](https://helpx.adobe.com/photoshop/using/create-smart-objects.html)


  * [https://design.tutsplus.com/tutorials/10-things-you-need-to-know-about-smart-objects-in-photoshop--cms-20268](https://design.tutsplus.com/tutorials/10-things-you-need-to-know-about-smart-objects-in-photoshop--cms-20268)
