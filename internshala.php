<?php 
	session_start();
	if (!isset($_SESSION['usr']) || $_SESSION['usr'] != 'internshala') {
		header('location: /FoodShala/');
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Internshala Page</title>
</head>
<body>
	<h1>Welcome to this Secret Page!</h1>
	<p>After you are done testing the project, there is a feedback in the console!</p>
	<p>I couldn't make a console on page like <a href="https://internshala.com/careers/web_developer" target="_blank">this page</a>. So please access console with Ctrl+Shift+I</p>
	<a href="#">I do nothing</a>
</body>
<script type="text/javascript">
	class Developer {
		constructor() {
			this.rating = 5;
			this.liked = true;
			this.comments = "";
			this.selected = true;
			this.decision = false;
			this.mail_body = "";
		}
		rate(rating) {
			this.rating = rating;
			return "Ratings recieved!"
		}
		
		liked_your_work() {
			this.liked = true;
			return "Thanks!";
		}

		disliked_your_work() {
			this.liked = false;
			return "I'll try to do better next time";
		}

		give_comment(comments) {
			this.comments = comments;
			return "Thank you for the additional comments!";
		}

		you_are_selected() {
			this.decision = true;
			this.selected = true;
			return "That is great!";
		}

		you_are_not_selected() {
			this.decision = true;
			this.selected = false;
			return "That is sad!";
		}

		need_to_review() {
			this.decision = false;
			return "I'll wait!"
		}


		send() {
			this.mail_body = "Hey Tushar,%0A";
			if (this.liked)
				this.mail_body += "%0AYou have done a really good work on the project.";
			else
				this.mail_body += "%0AYour work was not upto the mark.";

			this.mail_body += "%0AI would give a " + this.rating + " out of 10, for your work";

			if (this.comments != "")
				this.mail_body += "%0A"+this.comments;

			if (this.decision) {
				if (this.selected)
					this.mail_body += "%0AYou have been selected for the position.%0A";
				else
					this.mail_body += "%0AYou are not selected for the position, Better luck next time.%0A";
			}
			else {
				this.mail_body += "%0AWe still need to review your work, we will get back to you soon.%0A";	
			}
			document.getElementsByTagName('a')[1].href = "mailto:tushar.rajdev@gmail.com?subject=Web%20Developer&body=" + this.mail_body;
			document.getElementsByTagName('a')[1].textContent = "Click Me!"
			return "Click on the 'Click Me!' link on the page!";
		}
	}

	var developer = new Developer();
	console.log("I have created some functions for you in a similar way!");
	console.log("var developer = new Developer();");
	console.log("It has the following functions:");
	console.log("developer.liked_your_work();  or  developer.disliked_your_work();");
	console.log("developer.rate(<rating_out_of_10>);");
	console.log("developer.give_comment(<any_comments_for_the_work>);");
	console.log("developer.you_are_selected();  or  developer.you_are_not_selected();  or  developer.need_to_review();");
	console.log("After You are done, type developer.send();");
</script>
</html>