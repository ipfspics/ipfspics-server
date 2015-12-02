

var isMobile = window.matchMedia("only screen and (max-width: 760px)").matches;
uploads = getCookie("uploads").split(",");
votes = getCookie("votes").split(",");
votesType = getCookie("votesType").split(",");

$( document ).ready(function () {
	$(".shareFields").click(function () {
		$(this).select();
	});

	$(".voteButton").click(function (e) {
		e.preventDefault();
		voteType = $(e.currentTarget).data("vote");
		vote($(this).data("hash"), voteType, function () { 
			updateVoteButtonColor();	
			if (voteType == "upvote") {
				change = 1;
			}
			if (voteType == "downvote") {
				change = -1;
			}
			
			$(this).parent().children(".voteScore")
				.html( parseInt( $(this).parent().children(".voteScore").text() ) + change );
		});
	});

	$(".voteScore").each(function (i) {
			
		position = $.inArray($(this).data("hash"), votes);	
		if (votesType[position] == "upvote") {
			$(this).children("span").css("background-color", "green");
		} 
		if (votesType[position] == "downvote") {
			$(this).children("span").css("background-color", "red");
		}
	});

	if (!isMobile) {
		$(document).scroll(function(){
			if($(this).scrollTop() > 40) {   
				$('#mastheadBackground').show("slide", {direction: "up"}, 300);
			} else {
				$('#mastheadBackground').hide("slide", {direction: "up"}, 200);
			}
		});
	}



	updateVoteButtonColor();
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

function vote (hash, type, callback) {
	$.ajax("/api/v1/"+type+"/"+hash).done(function () { 

		votePosition = $.inArray(hash, votes);
		if (votePosition > -1) {
			votesType[votePosition] = type;
		} else {
			votes.push(hash);
			votesType.push(type);
		}
		setCookie("votes", votes, 30);
		setCookie("votesType", votesType, 30);

		callback();
	});
}

$(document).keydown(function(e) {
	if (e.which == 82) {
		window.location = "//" + window.location.hostname + "/random";
	}
});

function updateVoteButtonColor() {
	$(".voteButton").each(function (i) {
		position = $.inArray($(this).data("hash"), votes);	
		if (position > -1 && votesType[position] == $(this).data("vote")) {
			if (votesType[position] == "upvote") {
				color = "green";
			}
			if (votesType[position] == "downvote") {
				color = "red";
			}
		} else {
			color = "white";
		}
		$(this).children("span").css("color", color);
	});

	$(".voteScore").each(function (i) {
		position = $.inArray($(this).data("hash"), votes);	
		if (position > -1) {
			if (votesType[position] == "upvote") {
				color = "green";
				amountToChange = 1;
			}
			if (votesType[position] == "downvote") {
				color = "red";
				amountToChange = -1;
			}
		} else {
			color = "grey";
			amountToChange = 0;
		}
		$(this).children("span")
			.css("background-color", color)
			.html( parseInt( $(this).text() ) + amountToChange );
	});
}

