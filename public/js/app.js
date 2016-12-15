// Used for the drawingApp.
var currentColor = '#b11f24';
var currentTool = 'marker';
var currentSize = 40;

// Renders the UI for the drawing app.
function render(){
	var colors = [
		'#fff110',
		'#f8981d',
		'#f26823',
		'#ee3738',
		'#b11f24',
		'#5e1214',
		'#b91f6d',
		'#f171a7',
		'#9c66ac',
		'#3d2666',
		'#212060',
		'#2c3f99',
		'#60a9dd',
		'#179a99',
		'#50a947',
		'#166633',
		'#183319',
		'#3d2015',
		'#e8c477',
		'#f3c8af',
		'#ffffff',
		'#898989',
		'#080808',
		'#999999'
	];
	
	var check = '<path fill="white" d="M452.112,35.988l4.193,3.578L464.7,31l3.3,3.534L456.644,46s-6.271-5.975-8.644-6.783Z" transform="scale(0.7 0.7) translate(-444 -25) "/>';
	
	var svgs = colors.map(function(color){ return '<svg viewBox="0 0 20 20" class="paint-circle"><circle cx="10" cy="10" r="10" fill="' + color + '" />'+ ( currentColor === color ? check : '' ) + '</svg>'; });
	
	$('.selected-tool').removeClass('selected-tool');
	$('[data-tool=' + currentTool + ']').addClass('selected-tool');
	$('#paints').html(svgs.join(''));
	$('body').attr('data-brush', currentSize);
	$('body').attr('data-tool', currentTool);
}

// Makes the coloring pages draggable.
$('[draggable=true]').draggable({
	cursor:'move',
	revert: true
});

// Makes the books and the home icon be droppables.
$('[droppable=true]').droppable({
	classes: {
		"ui-droppable-hover": "droppable-hover",
		"ui-droppable-active": "droppable-active"
	},
	tolerance: 'pointer',
	drop: function(event, ui){
		var pageId = ui.draggable.data('page-id');
		var book_id = $(this).data('book-id');
		book_id = book_id === 'null' ? null : book_id;
		var data = new FormData();
		data.append('book_id', book_id);
		data.append('id', pageId);
		ui.draggable.remove();

		ajax('POST', '/move-coloring-page', data).then(function(data){
			if(data.result){
				
			}
		});
	}
 });

// Base ajax function for app. Handles showing the loading and displayinh errors.
function ajax(type, url, data){
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': Laravel.csrfToken
		}
	});

	if(type === 'PUT' || type === 'DELETE'){
		data = data || new FormData();
		data.append('_method',type);
		type = 'POST';
	}

	app.loading(true);

	return $.ajax({
		type: type,
		url: app.baseUrl + url,
		data: data,
		dataType: 'json',
		cache: false,
		processData: false, // Don't process the files
		contentType: false, // Set content type to 
	}).then(function(data){
		if(data.errors && !_.isEmpty(data.errors)){
			app.flash(_.values(data.errors));
			throw data;
		}
		app.loading(false);
		return data;
	}).catch(function(e){
		console.error('Error from server:', e);
		app.loading(false);
		throw e;
	});
}

app.templates = {};

// Search for all the templates and store them as compiled lodash templates.
$('script[type="text/template"]').each(function(){
	var $el = $(this);
	app.templates[$el.data('name')] = _.template($el.html());
});

app.showFullscreenPopover = function(){
	$('body').addClass('fullscreen-popover-active');
}

app.hideFullscreenPopover = function(){
	$('body').removeClass('fullscreen-popover-active');
}

app.loading = function(value){
	$('body').toggleClass('loading', value);
}

app.showTemplate = function(templateId, data){
	app.showFullscreenPopover();
	$('.fullscreen-popover').html(app.templates[templateId]({data:data}));
}

app.redirectTo = function(path){
	document.location = app.baseUrl + path;
}

app.flash = function(messages){
	messages = _.isArray(messages) ? messages : [messages];
	$('#messageArea').html(messages.map(function(message){ return app.templates.flashMessage({data:{message: message}}); }));
}

// Shows the form for editing 
app.showAddPage = function(){
	app.showTemplate('pageForm', {
		pageTitle: 'Add A Coloring Page',
		name: ''
	});
};

// Posts a page to the server.
app.addPage = function(){
	var files = $('#file-input').get(0).files;
	var data = new FormData();
	$.each(files, function(key, value)
	{
		data.append('photo', value, value.name);
	});

	if(app.currentBook){
		data.append('book_id', app.currentBook.id);
	}

	data.append('name', $('input[name="name"]').val());

	ajax('POST', '/pages', data).then(function(data){
		document.location.reload();
	});
}

// Shows the creaet/edit book form.
app.showEditBook = function(useCurrentBook){
	var name = '';
	var id = null;

	if(useCurrentBook){
		name = app.currentBook.name;
		id = app.currentBook.id;
	}

	app.showTemplate('bookForm', {
		isNew: !useCurrentBook,
		pageTitle: useCurrentBook ? 'Edit Coloring Book' : 'Add A Coloring Book',
		name: name,
		id: id
	});
};

// Posts to the server the new/edited book.
app.editBook = function(id){
	var data = new FormData();
	data.append('name', $('input[name="name"]').val());

	ajax(id ? 'PUT' : 'POST', '/books' + (id ? '/'+ id : '' ), data).then( function(data){
		app.redirectTo('/books/' + data.book.id);
	});
}

// Shows the form to save a coloring page.
$('.save-page-button').on('click', function(e){
	var currentPage = app.currentPage || {};

	app.showTemplate('saveColoringForm', {
		pageTitle: 'Save',
		name: currentPage.name,
		id: currentPage.id,
		isOwner: currentPage.user_id === app.user_id
	});
});

// Posts the coloring page to the server.
app.saveColoredPage = function(doSaveAs){
	var data = new FormData();
	data.append('name', $('input[name="name"]').val());
	data.append('img', drawingApp.save());
	data.append('thumb', drawingApp.saveThumb());
	data.append('outline', drawingApp.saveOutline());
	data.append('book_id', app.currentPage.book_id);
	data.append('id', app.currentPage.id);
	data.append('saveAs', doSaveAs ? 1 : 0);

	var saveOutline = $('#update-black-lines').is(':checked');

	if(saveOutline){
		data.append('saveOutline', saveOutline ? 1 : 0);
	}

	ajax('POST', '/coloring-page', data).then(function(data){
		if(data.id == app.currentPage.id){
			document.location.reload();
		}
		else{
			app.redirectTo('/pages/' + data.id);
		}
	});
}

// Deletes a book from the server.
app.deleteBook = function(id){
	if(!confirm('Are you sure you want to delete this book?')){ return; }
	ajax('DELETE', '/books/' + id).then( function(data){
		app.redirectTo('/books');
	});
}

// Deletes a page from the server.
app.deletePage = function(id){
	if(!confirm('Are you sure you want to delete this page?')){ return; }
	ajax('DELETE', '/pages/' + id).then( function(data){
		app.redirectTo('/books/' + app.currentPage.book_id);
	});
}

// Sets everything up for coloring a page.
app.initColoringPage = function(){
	drawingApp.setColor(currentColor);
	drawingApp.setSize(currentSize);
	render();

	$('#paints').on('click', function(e){
		currentColor = $(e.target).closest('svg').find('circle').attr('fill');
		drawingApp.setColor(currentColor);
		render();
	});

	$('#tools').on('click', function(e){
		currentTool = $(e.target).closest('svg').attr('data-tool');
		drawingApp.setTool(currentTool);
		render();
	});

	$('#tools').on('dblclick', function(e){
		currentTool = $(e.target).closest('svg').attr('data-tool');
		if(currentTool === 'eraser'){
			drawingApp.reset();
		}
	});

	$('.brush-size').on('click', function(e){
		currentSize = $(e.target).closest('circle').attr('data-brush');
		drawingApp.setSize(currentSize);
		render();
	})

	$(document).keydown(function(e){
		// Handle some keyboard shortcuts.
		var arrow = {left: 37, up: 38, right: 39, down: 40 }
		if (e.keyCode == arrow.left) { 
			if(currentTool == 'eraser'){ currentTool = 'bucket'; }
			else if(currentTool == 'bucket'){ currentTool = 'marker'; }
			else if(currentTool == 'marker'){ currentTool = 'eraser'; }
			drawingApp.setTool(currentTool);
			render();
			return false;
		}
		if (e.keyCode == arrow.right) { 
			if(currentTool == 'eraser'){ currentTool = 'marker'; }
			else if(currentTool == 'bucket'){ currentTool = 'eraser'; }
			else if(currentTool == 'marker'){ currentTool = 'bucket'; }
			drawingApp.setTool(currentTool);
			render();
			return false;
		}
		if (e.keyCode == arrow.down) { 
			if(currentSize == '100'){ currentSize = '60'; }
			else if(currentSize == '60'){ currentSize = '40'; }
			else if(currentSize == '40'){ currentSize = '20'; }
			else if(currentSize == '20'){ currentSize = '10'; }
			else if(currentSize == '10'){ currentSize = '5'; }
			drawingApp.setSize(currentSize);
			render();
			return false;
		}
		if (e.keyCode == arrow.up) { 
			if(currentSize == '60'){ currentSize = '100'; }
			else if(currentSize == '40'){ currentSize = '60'; }
			else if(currentSize == '20'){ currentSize = '40'; }
			else if(currentSize == '10'){ currentSize = '20'; }
			else if(currentSize == '5'){ currentSize = '10'; }
			drawingApp.setSize(currentSize);
			render();
			return false;
		}
	});
}

if(app.isColoringPage){
	app.initColoringPage();
}