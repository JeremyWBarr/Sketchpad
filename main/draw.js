var c, col, sizeSlider, titleStr;
var points = JSON.parse('{"data":[]}')

function setup() {
	setBlack();
	c = createCanvas(
		windowWidth,
		windowHeight-150
	);
	c.parent('canvas');
	whiteB = createButton('').size(75,75);
	whiteB.style('background-color',color(255,255,255));
	whiteB.mousePressed(setWhite);
	blackB = createButton('').size(75,75);
	blackB.style('background-color',color(0,0,0));
	blackB.mousePressed(setBlack);
	redB = createButton('').size(75,75);
	redB.style('background-color',color(229,20,0));
	redB.mousePressed(setRed);
	greenB = createButton('').size(75,75);
	greenB.style('background-color',color(51,153,51));
	greenB.mousePressed(setGreen);
	blueB = createButton('').size(75,75);
	blueB.style('background-color',color(27,161,226));
	blueB.mousePressed(setBlue);
	sizeSlider = createSlider(0, 100, 10);
	// createElement('br');
	ex = createButton('Publish').size(75,75);
	ex.mousePressed(saveImg);
	ex.style('float', 'right');
	title = createInput('drawing_1');
	title.style('float', 'right');
	title.input(getTitle);
	p = createP('Title: ');
	p.style('float','right');
	// im = createButton('Import').size(75,75);
	// im.mousePressed(loadImg);
	// im.style('float', 'right');
}

function draw() {
	background(255);
	for(i in points['data']) {
		if(points['data'][i]['col'] == 'w') {
			stroke(255);
		} else if(points['data'][i]['col'] == 'l') {
			stroke(0);
		} else if(points['data'][i]['col'] == 'r') {
			stroke(229,20,0);
		} else if(points['data'][i]['col'] == 'g') {
			stroke(51,153,51);
		} else if(points['data'][i]['col'] == 'b') {
			stroke(27,161,226);
		}
		strokeWeight(points['data'][i]['size']);
		line(points['data'][i]['mouseX'], points['data'][i]['mouseY'], points['data'][i]['pmouseX'], points['data'][i]['pmouseY']);
	}
}

function mouseDragged() { 
	size = sizeSlider.value();
	points['data'].push({mouseX, mouseY, pmouseX, pmouseY, col, size});
}

function getTitle() {
	titleStr = this.value();
}

function saveImg() {
	request = new XMLHttpRequest();

	request.onreadystatechange = function() {
	    if (request.readyState == XMLHttpRequest.DONE) {
	        console.log(request.responseText);
	    }
	}

	request.open("POST",'save.php', true);
	request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
	// saveFrames('out', 'png', 1, 1, function(data) {
	// 	request.send("data="+data[0].imageData);
	// });
	request.send("data="+JSON.stringify(points)+"&title="+titleStr);

	// document.location = 'http://digdug.cs.endicott.edu/~jbarr/sketchpad/main/main.html';
	return false;
}

function loadImg() {
	var id = 2;
	var url = 'http://digdug.cs.endicott.edu/~jbarr/sketchpad/main/load.php?id='+id;
	// console.log(loadJSON('http://digdug.cs.endicott.edu/~jbarr/sketchpad/main/load.php?id=1'));
	httpGet(url, 'json', false, function(response){
		points = response;
	});
}

function setWhite() {
	col = 'w';
}

function setBlack() {
	col = 'l';
}

function setRed() {
	col = 'r';
}

function setGreen() {
	col = 'g';
}

function setBlue() {
	col = 'b';
}
