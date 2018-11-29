class appService {
	constructor() {
		this.ssid = this.getCookie('ssid');
		if (this.ssid) {
			//this.getListProduct();
		}
	}
	reload() {
		window.location.href = window.location.href;
	}
	getArrayCookie(name) {
		return JSON.parse(this.getCookie(name));
	}
	deleteCookie(name) {
	  this.setCookie(name, "", {
		expires: -1
	  })
	}
	setCookie(name, value, options) {
	  options = options || {};

	  var expires = options.expires;

	  if (typeof expires == "number" && expires) {
		var d = new Date();
		d.setTime(d.getTime() + expires * 1000);
		expires = options.expires = d;
	  }
	  if (expires && expires.toUTCString) {
		options.expires = expires.toUTCString();
	  }

	  value = encodeURIComponent(value);

	  var updatedCookie = name + "=" + value;

	  for (var propName in options) {
		updatedCookie += "; " + propName;
		var propValue = options[propName];
		if (propValue !== true) {
		  updatedCookie += "=" + propValue;
		}
	  }

	  document.cookie = updatedCookie;
	}
	getCookie(name) {
	  var matches = document.cookie.match(new RegExp(
		"(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
	  ));
	  return matches ? decodeURIComponent(matches[1]) : undefined;
	}
	showMessage(obj = {message: '', error: 0}) {
		var typeMessage = 'success';
		if (obj.error) typeMessage = 'danger';
		if (obj.error == 2) typeMessage = 'primary';
			$.notify({
				message: obj.message
			},{
				type: typeMessage
			});	
	}
	base64_encode(str) {
		return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g,
			function toSolidBytes(match, p1) {
				return String.fromCharCode('0x' + p1);
		}));
	}

	//My functions 
	auth(variables = []) {
		if (!this.ssid) 
			if (variables['login'])
				if (variables['password'])
				{
					let t = this;
					$.post('/ssid/', {login: $('input[name=login]').val(), password: $('input[name=password]').val()}, function(e) {
						t.showMessage(JSON.parse(e));
						if (!JSON.parse(e)['error'])
							setTimeout(function() {
								t.reload();
							}, 1500);
					});
				} else {
					this.showMessage({message: 'Error, input not found'});
				}
			else this.showMessage({message: 'Error, input not found',  error: 1});
		else this.showMessage({message: 'Вы уже авторизованы!',  error: 2});
	}

	// product functions
	getListProduct() {
		let t = this;
		if (t.ssid)
			$.post('/get_list_products/', {}, function(e) {
				if (!JSON.parse(e)['error'])
					t.products = JSON.parse(e);
				else t.showMessage(JSON.parse(e));
			});
	}

	requestTo(variables = []) {
		try {
			let t = this;
			if (variables['method'] == 'post')
				$.post(variables['action'], variables['data'], function(d) {
					t.showMessage(JSON.parse(d));
					if (JSON.parse(d)['error'] == 0) {
						setTimeout(function() {
							window.location.reload();
						}, 1500)
					}
	            });
			else if (variables['method'] == 'get')
				$.get(variables['action'], variables['data'], function(d) {
					t.showMessage(JSON.parse(d));
	            });
			else $.error('invalid request method');
		} catch (e) {
			console.error(e);
			return false;
		}
    }
}

let app = new appService();

$(document).ready(function() {
	$('form').submit(function() {
		try {
			let t = $(this);
			let action = t.attr("action"),
				method = t.attr("method"),
				values = {},
				input;


			 	for (var i = 0; i < t[0].elements.valueOf().length-1; i++) {
			 		input = t[0].elements.valueOf()[i];
			 		values[input.name] = input.value;
			 	}
			
				app.requestTo({
					method: method, 
					action: action,
					data: values
				});
			return false;
		} catch (e) {
			console.error(e);
			return false;
		}
	});

	$('.auth-submit').click(function() {
		app.auth({
			login: $('input[name=login]').val(),
			password: $('input[name=password]').val()
		});
		return false;
	});

	// listproduct
	/*if (app.ssid) {
		var listP = '';
		for (var i = 0; i < app.products['count']; i++) {
			listP += "<option value=\""+app.products['rows'][i]['title']+"\" id=\""+app.products['rows'][i]['id']+"\">3600кг</option>";
		}
		$('datalist#products').html(listP);
	}*/
});