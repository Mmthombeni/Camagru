var video = document.getElementById('video');
var canvas = document.getElementById('canvas');
var canvas_top = document.getElementById('canvas_top');
var context = canvas.getContext('2d');
var context2 = canvas_top.getContext('2d');
var photo = document.getElementById('photo');
var vendorUrl = window.URL || window.webkitURL;
var timeout = null;
canvas.width = 640;
canvas.height = 480;

(function(){

	navigator.getMedia = 	navigator.getUserMedia || 
												navigator.webkitGetUserMedia ||
												navigator.mozGetUserMedia || 
												navigator.msGetUserMedia;

	navigator.getMedia({
			video: true,
			audio: false
	}, function(stream){
		video.src = vendorUrl.createObjectURL(stream);
		video.play();
		
	}, function(error){

	});

	video.addEventListener('play', function(){
		draw_bottom_layer(video, context, 640, 480);
    }, false);

	document.getElementById('startbutton').addEventListener('click', function(e){

			//context.drawImage(video, 0, 0, video.clientWidth, video.clientHeight);
			final_img();
			//photo.setAttribute('src', canvas.toDataURL('image/png'));
	});

	document.getElementById('upload').onchange = function(e){
		clearTimeout(timeout);
		var file = e.target.files[0];
		
        var image = new Image();
        image.src = URL.createObjectURL(file);
        image.onload = function(){
            context.drawImage(image, 0, 0, 640, 480);
        }
    };

})();

function draw_bottom_layer(strm, cntxt, w, h){
	cntxt.drawImage(strm, 0, 0, w, h);
	timeout = setTimeout(draw_bottom_layer, 10, strm, cntxt, w, h);
}

function do_super(src){
	//canvas.width = video.clientWidth;
	//canvas.height = video.clientHeight;
	canvas_top.width = 640;//video.clientWidth;
	canvas_top.height = 480;//video.clientHeight;

	img = new Image();
	img.src = src;
	img.onload = function(){
		context2.drawImage(img, 0, 0, img.width * 0.6, img.height * 0.6);
		document.getElementById('startbutton').style.display= "block";
	};
}

/*var snap = document.getElementById('snap');
snap.addEventListener('click', function(e){
			final_img();
});*/

function final_img(){
	var pic = canvas.toDataURL('image/png');
	var pic2 = canvas_top.toDataURL('image/png');

	send_pic(pic, pic2);
}

function send_pic(file, file2){
		var formdata = new FormData();
		var _http = new XMLHttpRequest();
		username = document.getElementById('username').value;

		formdata.append('image', file);
		formdata.append('image2', file2);
		formdata.append('username', username);
		formdata.append('image_width', 640);
		formdata.append('image_height', 480);

		_http.open('POST', 'merge.php', true);
		_http.send(formdata);
		_http.onload = function(){
			if (_http.status === 200){
				location.reload();;
			}
			else{
				//alert('error uploading');
			}
		}
		_http.onreadystatechange = function(){
			if (_http.readyState == 4 && _http.status == 200){
				console.log("PHP output:\n", _http.responseText);
			}
		}
}