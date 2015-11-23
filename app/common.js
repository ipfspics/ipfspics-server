

var isMobile = window.matchMedia("only screen and (max-width: 760px)").matches;
uploads = getCookie("uploads").split(",");

$( document ).ready(function () {
	$(".shareFields").click(function () {
		$(this).select();
	});

	$(".voteButton").click(function (e) {
		e.preventDefault();
		voteType = $(e.currentTarget).data("vote");
		vote(voteType, function () { 
			$(e.currentTarget).effect("highlight");
		});
	});

});

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
} 

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
	var c = ca[i];
	while (c.charAt(0)==' ') c = c.substring(1);
	if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}

function vote (type, callback) {
	$.ajax("/api/v1/"+type+"/"+hash).done(callback());
}

$(document).keydown(function(e) {
	if (e.which == 82) {
		window.location = "//" + window.location.hostname + "/random";
	}
});
