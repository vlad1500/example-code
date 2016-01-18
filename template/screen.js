/*
1024x768
1280x800
1440x960
1920x1200



740 - 1024 screen
940PX - 1280 screen
1240PX - 1440 screen
1600px - 1920 screen


740 x 290 57%
940 x 370 72%
1240 x 480 95%
1600 x 630 125%

*/

var book_width = 740,
	book_height = 290,
	book_font_size = 12,
	font_size = 12,
	__pagenum;
	
var book_sizes = new Array();
	book_sizes[0] = new Array(2);
	book_sizes[0][0] = 740;
	book_sizes[0][1] = 290;
	book_sizes[0][2] = 0.57;
	book_sizes[0][3] = 1024;
	book_sizes[1] = new Array(2);
	book_sizes[1][0] = 940;
	book_sizes[1][1] = 370;
	book_sizes[1][2] = 0.72;
	book_sizes[1][3] = 1280;
	book_sizes[2] = new Array(2);
	book_sizes[2][0] = 1240;
	book_sizes[2][1] = 480;
	book_sizes[2][2] = 0.95;
	book_sizes[2][3] = 1440
	book_sizes[3] = new Array(2);
	book_sizes[3][0] = 1600;
	book_sizes[3][1] = 630;
	book_sizes[3][2] = 1.25;
	book_sizes[3][3] = 1920;

var sizeHT = new Array(4);
	sizeHT[0] = 290;
	sizeHT[1] = 370;
	sizeHT[2] = 480;
	sizeHT[3] = 630;
 
$(window).bind("resize", resizeWindow);
 function resizeWindow( e ) {
 
	var screen_width = $(window).width();
	var screen_height =$(window).height();
	if (screen_width < book_sizes[0][3]) {
		book_width = book_sizes[0][0]; // width 
		book_height = book_sizes[0][1]; // height
		font_size = book_font_size * book_sizes[0][2]; // scale percentage
	}
	else if (screen_width >= book_sizes[0][3] && screen_width < book_sizes[1][3]) {
		book_width = book_sizes[0][0]; // width 
		book_height = book_sizes[0][1]; // height
		font_size = book_font_size * book_sizes[0][2]; // scale percentage
	}
	else if (screen_width > book_sizes[1][3] && screen_width < book_sizes[2][3]) {
		book_width = book_sizes[1][0]; // width 
		book_height = book_sizes[1][1]; // height
		font_size = book_font_size * book_sizes[1][2]; // scale percentage
	}
	else if (screen_width > book_sizes[2][3] && screen_width < book_sizes[3][3]) {
		book_width = book_sizes[2][0]; // width 
		book_height = book_sizes[2][1]; // height
		font_size = book_font_size * book_sizes[2][2]; // scale percentage
	}
	else if (screen_width > book_sizes[3][3]) {
		book_width = book_sizes[3][0]; // width 
		book_height = book_sizes[3][1]; // height
		font_size = book_font_size * book_sizes[3][2]; // scale percentage
	}
	font_size = Math.round(font_size);
 }