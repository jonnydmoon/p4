// This file has been greatly modified from the original version 
// (https://github.com/williammalone/Simple-Drawing-App-with-Bucket-Tool/blob/master/drawing-app-with-bucket.js).
// The file started as the basis for writing this drawing app. William Malone posted
// a great tutorial on how to create a basic html canvas drawing app 
// (http://www.williammalone.com/projects/html5-canvas-javascript-drawing-app-with-bucket-tool/).
// I've modified it to add more features and reorganize it in a way that I found
// more fitting for my website. - Jonny Moon


// Copyright 2012 William Malone (www.williammalone.com)
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//   http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

/*jslint browser: true */
/*global G_vmlCanvasManager */

var drawingApp = (function () {
	"use strict";

	var contexts = {},
		context,
		canvasWidth = 490,
		canvasHeight = 260,
		outlineImage = new Image(),
		coloredImage = new Image(),
		paint = false,
		mouseDown = false,
		ctrlKey,
		shiftKey,
		curTool = "marker",
		curColor,
		sizeLineStartY = 228,
		resourcesLoadingCount = 0,
		colorLayerData,
		outlineLayerData,
		curSize = 20,
		currentClick = null,
		lastClick = null,
		lastClickForShift = null,

		addClick = function (x, y, dragging) {
			lastClick = currentClick;

			currentClick = {
				x:x,
				y:y,
				dragging:dragging
			};
		},

		// Redraws the canvas.
		redraw = function () {
			// Make sure required resources are loaded before redrawing
			if (resourcesLoadingCount) {
				return;
			}

			if (curTool === "bucket") {
				// Draw the current state of the color layer to the canvas
				contexts.drawing.putImageData(colorLayerData, 0, 0, 0, 0, contexts.drawing.canvas.width, contexts.drawing.canvas.height);

			} else {
				drawStroke(contexts.drawing, currentClick, lastClick, curColor, curTool );
				
			}

			// Overlay a crayon texture (if the current tool is crayon)
			if (curTool === "crayon") {
				contexts.texture.canvas.style.display = "block";
			} else {
				contexts.texture.canvas.style.display = "none";
			}
		},


		// Start painting with paint bucket tool starting from pixel specified by startX and startY
		paintAt = function (startX, startY) {
			colorLayerData = contexts.drawing.getImageData(0, 0, canvasWidth, canvasHeight);

			var pixelPos = (startY * contexts.drawing.canvas.width + startX) * 4,
				r = colorLayerData.data[pixelPos],
				g = colorLayerData.data[pixelPos + 1],
				b = colorLayerData.data[pixelPos + 2],
				a = colorLayerData.data[pixelPos + 3];

			if (r === curColor.r && g === curColor.g && b === curColor.b) {
				// Return because trying to fill with the same color
				return;
			}

			if (matchOutlineColor(r, g, b, a)) {
				// Return because clicked outline
				return;
			}

			floodFill(contexts.outline, contexts.drawing, outlineLayerData, colorLayerData, startX, startY, r, g, b);

			redraw();
		},

		// Add mouse and touch event listeners to the canvas
		createUserEvents = function () {

				var drag = function (e) {

					if(curTool === 'bucket' && mouseDown == true){
						paintAt(e.pageX - this.offsetLeft, e.pageY - this.offsetTop);
						redraw();
					}


					if (curTool !== "bucket") {
						if (paint) {
							addClick(e.pageX - this.offsetLeft, e.pageY - this.offsetTop, true);
							redraw();
						}
					}
					// Prevent the whole page from dragging if on mobile
					e.preventDefault();
				},

				release = function () {
					mouseDown = false;
					if (curTool !== "bucket") {
						paint = false;
					}
					lastClickForShift = currentClick;
					currentClick = null;
					redraw();
				},

				cancel = function () {
					if (curTool !== "bucket") {
						paint = false;
					}
				},

				pressDrawing = function (e) {

					mouseDown = true;

					// Mouse down location
					var mouseX = (e.changedTouches ? e.changedTouches[0].pageX : e.pageX) - this.offsetLeft,
						mouseY = (e.changedTouches ? e.changedTouches[0].pageY : e.pageY) - this.offsetTop;

					ctrlKey = e.ctrlKey || e.metaKey;
					shiftKey = e.shiftKey ? {x: e.pageX - this.offsetLeft, y:e.pageY - this.offsetTop} : false;

					if (curTool === "bucket") {
						// Mouse click location on drawing area
						paintAt(mouseX, mouseY);
					} else {
						paint = true;
						addClick(mouseX, mouseY, false);
					}

					redraw();
				},

				dragDrawing = function (e) {
					
					var mouseX = (e.changedTouches ? e.changedTouches[0].pageX : e.pageX) - this.offsetLeft,
						mouseY = (e.changedTouches ? e.changedTouches[0].pageY : e.pageY) - this.offsetTop;


					if(curTool === 'bucket' && mouseDown == true){
						paintAt(mouseX, mouseY);
						redraw();
					}

					if (curTool !== "bucket") {
						if (paint) {
							addClick(mouseX, mouseY, true);
							redraw();
						}
					}

					// Prevent the whole page from dragging if on mobile
					e.preventDefault();
				},

				releaseDrawing = function () {
					mouseDown = false;
					lastClickForShift = currentClick;
					currentClick = null;
					if (curTool !== "bucket") {
						paint = false;
						redraw();
					}
				},

				cancelDrawing = function () {
					if (curTool === "bucket") {
						paint = false;
					}
				};

			// Add mouse event listeners to canvas element
			context.canvas.addEventListener("mousemove", drag, false);
			context.canvas.addEventListener("mouseup", release);
			context.canvas.addEventListener("mouseout", cancel, false);

			// Add touch event listeners to canvas element
			context.canvas.addEventListener("touchmove", drag, false);
			context.canvas.addEventListener("touchend", release, false);
			context.canvas.addEventListener("touchcancel", cancel, false);

			// Add mouse event listeners to canvas element
			contexts.outline.canvas.addEventListener("mousedown", pressDrawing, false);
			contexts.outline.canvas.addEventListener("mousemove", dragDrawing, false);
			contexts.outline.canvas.addEventListener("mouseup", releaseDrawing);
			contexts.outline.canvas.addEventListener("mouseout", cancelDrawing, false);

			// Add touch event listeners to canvas element
			contexts.outline.canvas.addEventListener("touchstart", pressDrawing, false);
			contexts.outline.canvas.addEventListener("touchmove", dragDrawing, false);
			contexts.outline.canvas.addEventListener("touchend", releaseDrawing, false);
			contexts.outline.canvas.addEventListener("touchcancel", cancelDrawing, false);
		},

		// Calls the redraw function after all neccessary resources are loaded.
		resourceLoaded = function () {
			resourcesLoadingCount--;
			if (resourcesLoadingCount === 0) {
				redraw();
				createUserEvents();
			}
		},

		
		init = function(id, width, height, baseAssetUrl, outlineUrl, colorUrl){
			outlineImage.onload = function () {
				outlineImage.onload = null;

				var outlineHeight = outlineImage.height;
				var outlineWidth = outlineImage.width;
				var $canvas = $('#' + id);
				var availableHeight = $canvas.height();
				var availableWidth = $canvas.width();
				var outlineRatio = outlineHeight/outlineWidth;
				var availableRatio = availableHeight/availableWidth;

				var ratio = availableWidth/outlineWidth;

				if(availableRatio < outlineRatio){
					ratio = availableHeight/outlineHeight;
				}

				var newWidth = Math.round(outlineWidth * ratio);
				var newHeight = Math.round(outlineHeight * ratio);

				$canvas.width(newWidth);
				$canvas.height(newHeight);

				init2(id, newWidth, newHeight, baseAssetUrl, outlineUrl, colorUrl);

			};
			outlineImage.src = outlineUrl;

		},

		// This needs to execute after init finishes.
		// Creates a canvas element, loads images, adds events, and draws the canvas for the first time.
		init2 = function (id, width, height, baseAssetUrl, outlineUrl, colorUrl) {

			canvasWidth = width;
			canvasHeight = height;
			
			// Create the canvas (Neccessary for IE because it doesn't know what a canvas element is)
			context = addCanvas({width:canvasWidth, height:canvasHeight, id:'gui', divId: id});
			contexts.drawing = addCanvas({width:canvasWidth, height:canvasHeight, id:'drawing', divId: id});
			contexts.texture = addCanvas({width:canvasWidth, height:canvasHeight, id:'texture', divId: id});
			contexts.outline = addCanvas({width:canvasWidth, height:canvasHeight, id:'outline', divId: id});

			resourcesLoadingCount++;
			outlineImage.onload = function () {
				contexts.outline.drawImage(outlineImage, 0, 0, canvasWidth,  canvasHeight);
				// Test for cross origin security error (SECURITY_ERR: DOM Exception 18)
				try {
					outlineLayerData = contexts.outline.getImageData(0, 0, canvasWidth, canvasHeight);

					if(!colorUrl){
						colorLayerData = contexts.drawing.getImageData(0, 0, canvasWidth, canvasHeight); // I think this should be the outline?
					}
					swapColors(contexts.outline, outlineLayerData);
				} catch (ex) {
					//window.alert("Application cannot be run locally. Please run on a server.");
					//return;
				}

				resourceLoaded();
			};
			outlineImage.src = outlineUrl;

			if(!colorUrl){
				return;
			}
			resourcesLoadingCount++;
			coloredImage.onload = function () {

				contexts.drawing.drawImage(coloredImage, 0, 0, canvasWidth,  canvasHeight);
				// Test for cross origin security error (SECURITY_ERR: DOM Exception 18)
				try {
					//outlineLayerData = contexts.outline.getImageData(0, 0, canvasWidth, canvasHeight);
					colorLayerData = contexts.drawing.getImageData(0, 0, canvasWidth, canvasHeight);
					//swapColors(contexts.outline, outlineLayerData);
				} catch (ex) {
					//window.alert("Application cannot be run locally. Please run on a server.");
					//return;
				}

				resourceLoaded();
			};
			coloredImage.src = colorUrl;
		};

	return {
		init: init,
		setTool: function(tool){
			curTool = tool;
		},
		setColor: function(color){
			color = hexToRgb(color);
			curColor = color;
		},
		setSize: function(size){
			curSize = size;
		},
		reset: function(){
			clearContext(contexts.drawing);
		},
		changeImage: function(){
			clearContext(contexts.drawing);
			outlineImage.src = "images/pear.png";
			
		},
		swapColors: function(){
			var canvas = merge2( $('canvas#outline,canvas#drawing') );
			var mergedContext = canvas.getContext("2d");;
			var mergedContextLayerData = mergedContext.getImageData(0, 0, canvasWidth, canvasHeight);
			swapColors(contexts.outline, mergedContextLayerData);
		},
		save: function(){
			return $('canvas#drawing').get(0).toDataURL();
		},
		saveThumb: function(){
			return merge2( $('canvas#outline,canvas#drawing') ).toDataURL();
		},
		saveOutline: function(){
			var mergedCanvas = merge2( $('canvas#outline,canvas#drawing') );
			var mergedContext = mergedCanvas.getContext("2d");;
			var mergedContextLayerData = mergedContext.getImageData(0, 0, canvasWidth, canvasHeight);
			swapColors(mergedContext, mergedContextLayerData);
			return mergedCanvas.toDataURL();
		}
	};


	//If you save as outline, then dont save the color.

	function swapColors(destinationContext, contextData){

		var whiteColor = { r: 255, g: 255, b: 255, a:255 };
		var blackColor = { r: 0, g: 0, b: 0, a:255 };
		var transparentColor = { r: 0, g: 0, b: 0, a:0 };

		function isWithinFuzziness(color, color2, fuzziness){
			return color >= color2 - fuzziness && color <= color2 + fuzziness;
		}

		function isMatch(pixelR, pixelG, pixelB, pixelA, color, fuzziness){
			return isWithinFuzziness(pixelR, color.r, fuzziness) &&
				isWithinFuzziness(pixelG, color.g, fuzziness) &&
				isWithinFuzziness(pixelB, color.b, fuzziness) &&
				isWithinFuzziness(pixelA, color.a, fuzziness);
		}

		function setColor(pixel, r, newColor){
			pixel[r] = newColor.r; // Make it transparent
			pixel[r+1] = newColor.g; // Make it transparent
			pixel[r+2] = newColor.b; // Make it transparent
			pixel[r+3] = newColor.a; // Make it transparent
		}


		swap(destinationContext, contextData, function(pixel, r, g, b, a){ // need to remove colors that are not black, and not white.
			var fuzziness = 150;

			if(isMatch(pixel[r], pixel[g], pixel[b], pixel[a], blackColor, fuzziness)){ 
				setColor(pixel, r, blackColor); 
			} // Set dark colors to black
			else{  setColor(pixel, r, transparentColor); }
		}); 
	}


	function swap(destinationContext, contextData, pixelHandler){

		pixelHandler = pixelHandler || function(pixel, r, g, b, a){ // By default, this changes white to transparent.
			var fuzzines = 2;
			var oldColor = { r: 255, g: 255, b: 255, a:255 }; // white
			var newColor = { r: 0, g: 0, b: 0, a:0 };         // transparent

			if (
				pixel[r] > oldColor.r - fuzziness && pixel[r] < oldColor.r + fuzziness &&
				pixel[g] > oldColor.g - fuzziness && pixel[g] < oldColor.g + fuzziness &&
				pixel[b] > oldColor.b - fuzziness && pixel[b] < oldColor.b + fuzziness &&
				pixel[a] > oldColor.a - fuzziness && pixel[a] < oldColor.a + fuzziness ) // if white then change alpha to 0
			{
				pixel[p+r] = newColor.r;
				pixel[p+g] = newColor.g;
				pixel[p+b] = newColor.b;
				pixel[p+a] = newColor.a;
			}
		}


		var pixel = contextData.data;
		var r=0, g=1, b=2,a=3;

		for (var p = 0; p<pixel.length; p+=4){
			pixelHandler(pixel, p+r, p+g, p+b, p+a);
		}

		destinationContext.putImageData(contextData, 0, 0, 0, 0, destinationContext.canvas.width, destinationContext.canvas.height);
	}

	function clearContext(context){
		context.clearRect(0, 0, context.canvas.width, context.canvas.height);
	}

	// From: http://stackoverflow.com/questions/5623838/rgb-to-hex-and-hex-to-rgb
	function componentToHex(c) {
		var hex = c.toString(16);
		return hex.length == 1 ? "0" + hex : hex;
	}

	function rgbToHex(r, g, b) {
		return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
	}

	function hexToRgb(hex) {
		// Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
		var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
		hex = hex.replace(shorthandRegex, function(m, r, g, b) {
			return r + r + g + g + b + b;
		});

		var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
		return result ? {
			r: parseInt(result[1], 16),
			g: parseInt(result[2], 16),
			b: parseInt(result[3], 16)
		} : null;
	}

	function addCanvas(params){
		var canvasElement = document.createElement('canvas');
		canvasElement.setAttribute('width', params.width);
		canvasElement.setAttribute('height', params.height);
		canvasElement.setAttribute('id', params.id);
		document.getElementById(params.divId).appendChild(canvasElement);
		if (typeof G_vmlCanvasManager !== "undefined") {
			canvasElement = G_vmlCanvasManager.initElement(canvasElement);
		}
		return canvasElement.getContext("2d");
	}

	function matchOutlineColor(r, g, b, a) {
		return (r + g + b < 20 && a > 200); // This used to be == 255, which was too exact. 
	};


	function colorPixel (layerData, pixelPos, r, g, b, a) {
		layerData.data[pixelPos] = r;
		layerData.data[pixelPos + 1] = g;
		layerData.data[pixelPos + 2] = b;
		layerData.data[pixelPos + 3] = a !== undefined ? a : 255;
	}

	function matchStartColor (outlineLayerData, colorLayerData, pixelPos, startR, startG, startB) {
		var r = outlineLayerData.data[pixelPos],
			g = outlineLayerData.data[pixelPos + 1],
			b = outlineLayerData.data[pixelPos + 2],
			a = outlineLayerData.data[pixelPos + 3];

		// If current pixel of the outline image is black
		if (matchOutlineColor(r, g, b, a)) {
			return false;
		}

		r = colorLayerData.data[pixelPos];
		g = colorLayerData.data[pixelPos + 1];
		b = colorLayerData.data[pixelPos + 2];
		a = colorLayerData.data[pixelPos + 3];

		// If the current pixel matches the clicked color
		if (r === startR && g === startG && b === startB) {
			return true;
		}

		if(a < 255){ // This makes it so you don't get a fringe when doing a fill due to antialiasing.
			return true;
		}

		// If current pixel matches the new color
		if (r === curColor.r && g === curColor.g && b === curColor.b) {
			return false;
		}

		// Return the difference in current color and start color within a tolerance
		return (Math.abs(r - startR) + Math.abs(g - startG) + Math.abs(b - startB) < 10 && a > 240  ); // USED TO BE 255
	}

	function floodFill (outlineContext, colorContext, outlineLayerData, colorLayerData, startX, startY, startR, startG, startB) {

		var canvasWidth = colorContext.canvas.width;
		var canvasHeight = colorContext.canvas.height;

		var newPos,
			x,
			y,
			pixelPos,
			reachLeft,
			reachRight,
			drawingBoundLeft = 0,
			drawingBoundTop = 0,
			drawingBoundRight = canvasWidth - 1,
			drawingBoundBottom = canvasHeight - 1,
			pixelStack = [[startX, startY]];

		var i = 0;

		while (pixelStack.length) {

			newPos = pixelStack.pop();
			x = newPos[0];
			y = newPos[1];

			// Get current pixel position
			pixelPos = (y * canvasWidth + x) * 4;

			// Go up as long as the color matches and are inside the canvas
			while (y >= drawingBoundTop && matchStartColor(outlineLayerData, colorLayerData, pixelPos, startR, startG, startB)) {
				y -= 1;
				pixelPos -= canvasWidth * 4;
			}

			pixelPos += canvasWidth * 4;
			y += 1;
			reachLeft = false;
			reachRight = false;

			// Go down as long as the color matches and in inside the canvas
			while (y <= drawingBoundBottom && matchStartColor(outlineLayerData, colorLayerData, pixelPos, startR, startG, startB)) {
				y += 1;

				colorPixel(colorLayerData, pixelPos, curColor.r, curColor.g, curColor.b);

				if (x > drawingBoundLeft) {
					if (matchStartColor(outlineLayerData, colorLayerData, pixelPos - 4, startR, startG, startB)) {
						
						

						if (!reachLeft) {
							// Add pixel to stack
							pixelStack.push([x - 1, y]);
							reachLeft = true;
						}
					} else if (reachLeft) {
						reachLeft = false;
					}
				}

				if (x < drawingBoundRight) {
					if (matchStartColor(outlineLayerData, colorLayerData, pixelPos + 4, startR, startG, startB)) {
						
						

						if (!reachRight) {
							// Add pixel to stack
							pixelStack.push([x + 1, y]);
							reachRight = true;
						}
					} else if (reachRight) {
						reachRight = false;
					}
				}

				pixelPos += canvasWidth * 4;
			}
		}
	}


	function drawStroke(context, currentClick, lastClick, curColor, curTool){
		if (!currentClick && !shiftKey) { return; }

		context.beginPath();
		
		// If dragging then draw a line between the two points
		if (currentClick && lastClick) {
			context.moveTo(lastClick.x, lastClick.y);
		} else {
			// The x position is moved over one pixel so a circle even if not dragging
				context.moveTo(currentClick.x - 1, currentClick.y);
		}


		if(shiftKey){
			currentClick = lastClickForShift;
			shiftKey = null;
		}

		context.lineTo(currentClick.x, currentClick.y);

		// Set the drawing color
		if (curTool === "eraser") {

			context.globalCompositeOperation = "destination-out";
			context.strokeStyle = "rgba(0,0,0,1)";

			if(ctrlKey){

				contexts.outline.globalCompositeOperation = "destination-out";
				contexts.outline.strokeStyle = "rgba(0,0,0,1)";

				contexts.outline.beginPath();

				if (currentClick && lastClick) {
					contexts.outline.moveTo(lastClick.x, lastClick.y);
				} else {
					// The x position is moved over one pixel so a circle even if not dragging
					contexts.outline.moveTo(currentClick.x - 1, currentClick.y);
				}

				contexts.outline.lineTo(currentClick.x, currentClick.y);
				contexts.outline.lineCap = "round";
				contexts.outline.lineJoin = "round";
				contexts.outline.lineWidth = curSize;
				contexts.outline.stroke();
				contexts.outline.closePath();
				contexts.outline.globalCompositeOperation ="source-over";
			}
		} else {
			context.globalCompositeOperation ="source-over";
			context.strokeStyle = "rgb(" + curColor.r + ", " + curColor.g + ", " + curColor.b + ")";
		}

		context.lineCap = "round";
		context.lineJoin = "round";
		context.lineWidth = curSize;
		context.stroke();
		context.closePath();
	}

	//http://www.benknowscode.com/2012/10/html-canvas-imagedata-creating-layers_9883.html
	function merge2(layers)
	{
		var tmpc = $('<canvas>')[0],
		dstc = tmpc.getContext('2d'),
		h = layers[0].height,
		w = layers[0].width;

		tmpc.height = h;
		tmpc.width = w;
		dstc = tmpc.getContext('2d');

		layers.each(function (idx)
		{
			dstc.globalAlpha = +$(this).css('opacity');
			dstc.drawImage(this, 0, 0);
		});

		return tmpc;
	}

}());
