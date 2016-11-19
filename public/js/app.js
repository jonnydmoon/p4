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
	var name = prompt("Please give this page a name", 'My Coloring Page');
	save(name);
})

function save(name){
	$('#hidden-name-field').val(name);
	$('#hidden-img-field').val(drawingApp.save());
	$('#color-form').get(0).submit();
}