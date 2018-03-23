var app = {
	server: 'app/server.php',
	promoter_subjects: [],
	login: function() {
		var login = document.getElementById('login_name').value;
		var password = document.getElementById('login_password').value;
		$.ajax({ type: 'POST', url: app.server, data: {task: 2, login: login, password: password} })
		.done(function(msg) {
			if(parseInt(msg) == 1) {
				location.reload();
			} else {
				app.modalAlert(msg);
			}
		});
	},
	userTypeChange: function() {
		var type = document.getElementById('user_type').value;
		if(type == 1) {
			document.getElementById('student_subject').style.display = 'block';
			document.getElementById('promoter_subject').style.display = 'none';
		} else {
			document.getElementById('student_subject').style.display = 'none';
			document.getElementById('promoter_subject').style.display = 'block';
		}
	},
	onSubjectChange: function(id) {
		var b = document.getElementById('subject_'+id).checked;
		if(b) {
			this.promoter_subjects.push(id);
		} else {
			var a = this.promoter_subjects;
			var cache = [];
			var i;
			var l = a.length;
			for(i = 0; i < l; i++) {
				if(a[i] != id) {
					cache.push(a[i]);
				}
			}
			this.promoter_subjects = cache;
		}
		document.getElementById('subject_count').innerHTML = this.promoter_subjects.length;
	},
	check_key: function(e) {
		var unicode = e.keyCode? e.keyCode : e.charCode;
		if(unicode == 13) {
			if(this.getElement('loginBox')) this.login();
			else this.register();
		}
	},
	promoterChooseSubjects: function() {
		$.ajax({ type: 'POST', url: app.server, data: {task: 3, window: 3} })
		.done(function(msg) {
			document.getElementById('box').innerHTML += '<div id="modalWindow"><div id="shadow2" onClick="app.closeModal();"></div>'+msg+'</div>';
			var l = app.promoter_subjects.length;
			if(l > 0) {
				for(var i = 0; i < l; i++) {
					document.getElementById('subject_'+app.promoter_subjects[i]).checked = true;
				}
			}
		});
	},
	register: function() {
		var name         = document.getElementById('register_name').value;
		var lastname     = document.getElementById('register_lastname').value;
		var login        = document.getElementById('register_login').value;
		var mail         = document.getElementById('register_mail').value;
		var pass1        = document.getElementById('register_pass1').value;
		var pass2        = document.getElementById('register_pass2').value;
		var account_type = document.getElementById('user_type').value;
		var subjects     = document.getElementById('subject').value;
		var indeks       = document.getElementById('indeks').value;

		if (parseInt(account_type) > 1) {
			subjects = document.getElementById('p_subject').value;
		}

		$.ajax({ type: 'POST', url: app.server, data: {task: 1, name: name, lastname: lastname, login: login, mail:mail, pass1: pass1, pass2: pass2, type: account_type, subjects: subjects, indeks: indeks} })
		.done(function(msg) {
			if(parseInt(msg) == 1) {
				location.reload();
			} else {
				app.modalAlert(msg);
			}
		});
	},
	logout: function() {
		$.ajax({ type: 'POST', url: app.server, data: {task: 4} })
		.done(function(msg) {
			location.reload();
		});
	},
	modalAlert: function(t) {
		alert(t);
	},
	openWindow: function(i) {
		$.ajax({ type: 'POST', url: app.server, data: {task: 3, window: i} })
		.done(function(msg) {
			if(!app.getElement('modalWindow'))
				document.getElementById('page').innerHTML += '<div id="modalWindow"><div id="shadow" onClick="app.closeModal();"></div>'+msg+'</div>';
		});
	},
	closeModal: function() {
		this.removeElement('modalWindow');
	},
	removeElement: function(i) {
		if(this.getElement(i)) {
			el = document.getElementById(i);
			el.parentNode.removeChild(el);
		}
	},
	getElement: function(i) {
		return document.getElementById(i);
	}
};

/*
	advAJAX.post({
                url: "/server",
                parameters: {
                    ev: 10,
                    id: e
                },
                onSuccess: function(n) {
                    g.small_window("options_" + e, "ulubione", n.responseText)
                }
            });
*/