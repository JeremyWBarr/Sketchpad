var col, sizeSlider;
var points = [];

function setup() {
	setBlack();
	createCanvas(
		windowWidth,
		windowHeight-170
	);
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
	createElement('br');
	ex = createButton('Export').size(150,75);
	ex.mousePressed(saveImg);
}

function draw() {
}

function mouseDragged() { 
	if(col == 'w') {
		stroke(255);
	} else if(col == 'l') {
		stroke(0);
	} else if(col == 'r') {
		stroke(229,20,0);
	} else if(col == 'g') {
		stroke(51,153,51);
	} else if(col == 'b') {
		stroke(27,161,226);
	}
	strokeWeight(sizeSlider.value());
	line(mouseX, mouseY, pmouseX, pmouseY);
}

function saveImg() {
	save('out.png');

	var test = JSON.parse('{"test":"True"}');
	var str_json = JSON.stringify(test);

	request = new XMLHttpRequest();
	request.open("POST","save.php", true);
	request.setRequestHeader("Content-type", "application/json")
	request.send(str_json)
	return false;
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
