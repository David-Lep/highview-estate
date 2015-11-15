$("#image-viewer img").click(
	function() {
		var thmb = this;
		var src = this.src;
		$('#featuredImage').fadeOut(400,function(){
			$(this).fadeIn(400)[0].src = src;
		});
	}
);



$("#contact_form input, #contact_form textarea").on('blur', function(event) {
	var active = '#' + this.id;
	$.ajax({
		type:"POST",
		url:"scripts/php/contact-form.php",
		data: {"field" : this.name, "value" : this.value},

		success:function(data) {
			if(data){
				console.log(data);
				$(active).css("border-color", "red");
				if($(active).next('.error').length === 0) {
					$(active).after("<div class='error'>" + data + "</div>");
				}
			} else {
				$(active).css("border-color", "#ABCDEF");
				if($(active).next('.error').length > 0) {
					$(active).next('.error').remove();
				}
			}
		}
	});
});


$("#article_select_button").on('click', function(event) {
	var active = $("#article_select").val()
	$.ajax({
		type:"POST",
		url:"scripts/php/cms.php",
		data: {"article" : active},

		success:function(data) {
			$('#editContent, #cke_editor1').empty();
			$('#editContent, #cke_editor1').remove();
			$('main').append(data);
			CKEDITOR.replace('editor1');
			metaTagLengthCounter();
		}
	});
});


$("#property_select_button, #land_select_button").on('click', function(event) {
	if(this.id === "property_select_button"){
		var type = 'house';
		var active = $("#property_select").val()
	} else if(this.id === "land_select_button"){
		var type = 'land';
		var active = $("#land_select").val()
	}
	$.ajax({
		type:"POST",
		url:"scripts/php/cms.php",
		data: {"propertySelect" : active, "propertyType" : type},

		success:function(data) {
			$('#editContent, #cke_editor1').empty();
			$('#editContent, #cke_editor1').remove();
			$('main').append(data);
			CKEDITOR.replace('editor1');
		}
	});
});



$("#property_create_button, #land_create_button").on('click', function(event) {
	if(this.id === "property_create_button"){
		var type = 'house';
	} else if(this.id === "land_create_button"){
		var type = 'land';
	}

	$.ajax({
		type:"POST",
		url:"scripts/php/cms.php",
		data: {"createProperty" : type},

		success:function(data) {
			$('#editContent, #cke_editor1').empty();
			$('#editContent, #cke_editor1').remove();
			$('main').append(data);
			CKEDITOR.replace('editor1');
		}
	});
});




$(document).ready(function() {
	//Toggle mobile menu on/off
	var menuToggle = $("#nav-btn");
	menuToggle.on("click", function(e) {
		$(menuToggle).hasClass("on") ? $(menuToggle).removeClass("on") : $(menuToggle).addClass("on");
		var nav = $("#navigation-menu");
		$(nav).slideToggle(function(){
			if($(nav).is(":hidden")) {
				$(nav).removeAttr("style");
			}
	    });
	});

	//Toggle Messages On / Off. Choice between new messages or all, which is "read" messages.
	var visible = 0;
	$("#viewMessages, #allMessages").on("click", function(e) {
		var msgContainer = $(".message-container");
		var type = this.id == "viewMessages" ? 'new' : 'all';
		if(visible)	{
			$(".message-container").remove();
			visible = 0;
		} else {
			$.ajax({
				type:"POST",
				url:"scripts/php/cms.php",
				data: {"viewMessageType" : type},
				success:function(data) {
					$("#messages").append(data);
					bindMessageHandlers();
				}
			});
			visible = 1;
		}
	});
	//End Message Toggle
});

//Message Class/Functions
function Message(id) {
	this.id = id;
};
Message.prototype.markRead = function() {
	$.ajax({
		type:"POST",
		url:"scripts/php/cms.php",
		data: {"markMsgRead" : this.id}
	});
};
Message.prototype.archiveMsg = function() {
	$.ajax({
		type:"POST",
		url:"scripts/php/cms.php",
		data: {"archiveMsg" : this.id},
	});
};




function bindMessageHandlers() { // Bind event handlers in function so it can be called after Ajax requests
	$(".msg-read, .msg-del").on("click", function(e) {
		var option = $(this).attr('class');
		var msgId = $(this).parent().parent().find('input:hidden').val(); //Step into li, then into ul, then grab the hidden input which contains the messages ID
		var parent = $(this).closest("div").parent();
		var message = new Message(msgId);
		/*
			If msg-read is pressed, call the method message.Read of the class message
			Get the number from the string of "# new messages"
			Subtract one from this number and update the text to reflect it
			Fade the message as markRead is pressed, before hiding it.
		*/
		if(option === 'msg-read'){
			message.markRead();
			$.notify("Message Read", "success");
		} else if (option === 'msg-del') {
			message.archiveMsg();
			$.notify("Message Archived", "info");
		};
		var newMessageCount = parseInt($('button#viewMessages').html());
		$('button#viewMessages').html((newMessageCount-1) + " New Messages");
		$(parent).fadeOut("slow", function(){
			$(parent).hide();
		});
	});
};



function metaTagLengthCounter() { //Give a guide for meta description length. Ideal range is between 150-160
	$("#metaDesc").on("input", function(){
		max = 160;
		c = this.value.length;
		var guide = $("#metaDescCount");
		$(guide).text(max - c);
		if((max - c) < 0){
			$(guide).append(" Too Long");
			$(guide).css({"color": "red"});
		} else if((max - c) < 10){
			$(guide).append(" Ideal Length");
			$(guide).css({"color": "green"});
		} else {
			$(guide).css({"color": "black"});
		};
	});
};
