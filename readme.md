# Project 4: Coloring Pages

## Live URL
<http://p4.jonnymoon.com>

## Description
This website allows for users to color coloring pages online. The main focus is to 
provide a place where children can easily navigate and color pages. There are some
tasks that would most likely be performed by the child's parent, such as uploading new
pages to color.

## Demo
<https://youtu.be/8AnRLG702Og>

## Details for teaching team
I found a tutorial about how to use html canvas at <http://www.williammalone.com/articles/create-html5-canvas-javascript-drawing-app/>
I used this code as the basis for the drawing portion of the site, but ended up significantly rewriting most of it to work for 
my application. Some notable changes include:
* A completely different UI.
* The ability to export pictures to save them to a server.
* The paint bucket tool was enhanced to support intersecting colors from the base layer and the color layer.
* The paint bucket tool was enhanced to support dragging.
* The eraser tool was updated to actually erase instead of drawing a white stroke.
* Added the ability to dynamically load an image and convert the light colors to transparent.
* Allowed the canvas to scale to the largest possible size available for the given window size.
* Much of the code was refactored to be more modular and reusable.
* Double clicking the eraser clears all the colored layer.
* Erasing while pressing Control key will erase the base outline layer.

Once logged in, a user has the ability to click on their name to edit his/her email or password.

In order to upload, edit, create, and save pages and books, a user must be logged in.

Whenever a coloring page is saved, I actually save 2 portions. One layer is the base layer with the black outlines.
The other layer is the colored layer. This allows a user to come back to a drawing later on and modify the coloring
without the black outlines being erased.

When a coloring page is saved, if it does not have a book, it will be placed in the "My Extra Pages" section on the home page.
A user can drag and drop these pages to their own coloring books.

Most of the forms use ajax for submission.

If the user is the owner of a coloring page, the page supports drag and drop on books and the home icon.

## CRUD Operations
You must be logged in to perform the CRUD operations, which include all operations for both Coloring Books and Pages:
* Create - On the home page a user can "Add a Coloring Book". Once inside a book, a user can "Add a page". In addition, when users save a coloring page, they can chose "Save As" which also creates a new page. 
* Read - On the home page all the books are displayed. Click a book to view the pages for that book.
* Update - When viewing a coloring book that you own, a gear icon will be next to the title of the book in the header. Clicking this allows a user to edit the book name. For coloring pages, if a user is the owner, they can click the save icon at the top of the page after coloring the page. This allow a user to also modify the name. Updates are also performed when a user drags pages around to books.
* Delete - There is delete icon that is next to the book/page title in the edit screens displayed in the previous bullet point. This deletes the book/page.

## Planning Doc: 
<https://docs.google.com/document/d/1ug1JlBP5GDxUHdlynbw9jHRmLjOwX79nr4yGDpMla3Y/edit?usp=sharing>

## Outside Code, Resources, and Packages
* Bootstrap: http://getbootstrap.com/
* Google Fonts: https://fonts.google.com/
* Font Awesome: http://fontawesome.io/
* Intervention Image: http://image.intervention.io/
* Laravel Debugbar: https://github.com/barryvdh/laravel-debugbar
* Coloring Pages: https://openclipart.org
