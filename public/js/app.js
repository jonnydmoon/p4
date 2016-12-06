var currentColor = '#b11f24';
var currentTool = 'marker';
var currentSize = 40;

drawingApp.setColor(currentColor);
drawingApp.setSize(currentSize);

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
		'#999999',

	];
	
	var check = '<path fill="white" d="M452.112,35.988l4.193,3.578L464.7,31l3.3,3.534L456.644,46s-6.271-5.975-8.644-6.783Z" transform="scale(0.7 0.7) translate(-444 -25) "/>';
	
	var svgs = colors.map(function(color){ return '<svg viewBox="0 0 20 20" class="paint-circle"><circle cx="10" cy="10" r="10" fill="' + color + '" />'+ ( currentColor === color ? check : '' ) + '</svg>'; });
	
	$('.selected-tool').removeClass('selected-tool');
	$('[data-tool=' + currentTool + ']').addClass('selected-tool');
	$('#paints').html(svgs.join(''));
	$('body').attr('data-brush', currentSize);
	$('body').attr('data-tool', currentTool);
}

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

$('.brush-size').on('click', function(e){
	currentSize = $(e.target).closest('circle').attr('data-brush');
	drawingApp.setSize(currentSize);
	render();
})

$('.save-page-button').on('click', function(e){
	
	var currentPage = app.currentPage || {};

	app.showTemplate('saveColoringForm', {
		pageTitle: 'Save',
		name: currentPage.name,
		id: currentPage.id,
		isOwnPage: currentPage.user_id === app.user_id
	});
})



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
		return data;
	}).catch(function(e){
		console.error('Error from server:', e);
		throw e;
	});
}



app.templates = {};

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

app.createPage = function(){
	ajax('POST', '/pages', {
		name: 'hey'
	}).then(function(data){

		console.log('MY RESULT ',data);
	});
}


app.showEditPage = function(){
	app.showTemplate('pageForm', {
		pageTitle: 'Add A Coloring Page',
		name: ''
	});
};

app.editPage = function(){
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

app.showEditBook = function(useCurrentBook){

	var name = '';
	var id = null;

	if(useCurrentBook){
		name = app.currentBook.name;
		id = app.currentBook.id;
	}

	app.showTemplate('bookForm', {
		pageTitle: useCurrentBook ? 'Edit Coloring Book' : 'Add A Coloring Book',
		name: name,
		id: id
	});
};

app.editBook = function(id){

	var data = new FormData();
    data.append('name', $('input[name="name"]').val());

   	ajax(id ? 'PUT' : 'POST', '/books' + (id ? '/'+ id : '' ), data).then( function(data){
		app.redirectTo('/books/' + data.book.id);
	});
}

app.saveColoredPage = function(id){

	var data = new FormData();
    data.append('name', $('input[name="name"]').val());
    data.append('img', drawingApp.save());
    data.append('thumb', drawingApp.saveThumb());
    data.append('outline', drawingApp.saveOutline());
    data.append('id', app.currentPage.id);

   	ajax('POST', '/coloring-page', data).then(function(data){
		document.location.reload();
	});
}

app.deleteBook = function(id){
	if(!confirm('Are you sure you want to delete this book?')){ return; }
   	ajax('DELETE', '/books/' + id).then( function(data){
		app.redirectTo('/books');
	});
}

app.onPageDragStart = function(event, id){
	app.currentlyDraggedPage = event.currentTarget;
	var crt = event.currentTarget.cloneNode(true);
    crt.style.position = "absolute"; crt.style.top = "-50px"; crt.style.right = "0px";
    document.body.appendChild(crt);
    event.dataTransfer.setDragImage(crt, 100, 100);
	event.dataTransfer.setData("text", id);
}

app.onDropBook = function(event, id){
	event.preventDefault();
    var pageId = event.dataTransfer.getData("text");

    var data = new FormData();
    data.append('book_id', id);
    data.append('id', pageId);

    ajax('POST', '/move-coloring-page', data).then(function(data){
		if(data.result){
			$(app.currentlyDraggedPage).remove();
		}
	});

}

app.onDragOverBook = function(event, id){
	event.preventDefault();
}

app.onDragEnterBook = function(event, id){
	event.preventDefault();
	$(event.currentTarget).toggleClass('drag-hover', true);
}

app.onDragLeaveBook = function(event, id){
	event.preventDefault();
	$(event.currentTarget).toggleClass('drag-hover', false);
}

document.ondragstart = function(){$('body').toggleClass('dragging', true);}
document.ondragend = function(){$('body').toggleClass('dragging', false);  $('.drag-hover').removeClass('drag-hover')}