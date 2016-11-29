# Project 4: Developer's Best Friend

## Live URL
<http://p3.jonnymoon.com>

## Description
This website provides a few tools to help in web development. 
* Lorem Ipsum - Generate lorem ipsum text blocks.
* Password Generator - Create a memorable password that needs to match certain rules.
* User Generator - Generate user content for UI mock ups.
* Image Generator - Resize images and adjust quality.

## Demo
<https://youtu.be/CoPVCPMQxHs>

## Details for teaching team
The website is responsive. I converted/included the password generator from project 2.
I also created an image generator that can resize an uploaded image. As part of the 
image generator, I created a cache that clears out expired images so that images remain
on the server only for a short time.

I created a custom validator that I use for all of the controllers to validate the data.
If there is invalid data, it is usually auto-corrected to the default value and processed normally.

As part of the user generator, I added the ability to export the data in JSON format. This is 
useful for using the user content in a variety of programming languages.

I also added a JSON Formatter tool. It has a couple of display modes. It allows the user to display 
the JSON using "fullscreen" without the website header and footer. The editor uses the Ace editor.
I chose to use the Ace editor because I like how it can highlight the JSON and give visual indications 
of errors.

## Outside Code, Resources, and Packages
* Bootstrap: http://getbootstrap.com/
* Google Fonts: https://fonts.google.com/
* Font Awesome: http://fontawesome.io/
* Password Word List: http://www.wordfrequency.info/
* Lorem Ipsum Text: http://www.lipsum.com/
* First and Last Names: http://names.mongabay.com
* City, State, Zipcode: http://federalgovernmentzipcodes.us/
* Addresses: https://www.randomlists.com/random-addresses
* User Content Profile Images: http://uifaces.com
* JSON Editor: https://ace.c9.io
* Intervention Image: http://image.intervention.io/
* Webmozart JSON: https://github.com/webmozart/json




Planning Doc: https://docs.google.com/document/d/1ug1JlBP5GDxUHdlynbw9jHRmLjOwX79nr4yGDpMla3Y/edit?usp=sharing


https://openclipart.org/detail/187781/christmas-tree-coloring-page