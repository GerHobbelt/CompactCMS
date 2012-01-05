
/* [i_a] now also returns the score as a result value, to be used by randomPassword() */
function passwordStrength(password)
{
	var score = 0;
	if (password.length > 5) {
		score++;
	}
	if (password.match(/[a-z]/) && password.match(/[A-Z]/)) {
		score++;
	}
	if (password.match(/\d+/)) {
		score++;
	}
	if (password.match(/[!@#$%\^&*?_~\-()., ]/)) {  // NOTE DUE TO TYPO FIX: FF3 will silently stop parsing this JS file when a faulty regex is specced, e.g. /[~-(]/ which is a range that's impossible ('(' comes BEFORE '~'!)
		score++;
	}
	if(password.length > 12) {
		score++;
	}
	document.getElementById("passwordStrength").className = "strength" + score;
	return score;
}

/* [i_a] added loop to make sure password is ALWAYS strong enough (sometimes the random generated one isn't) */
function randomPassword(len)
{
	var x, i, pass, score, setlen, chars;
	var target = document.getElementById("userPass");

	chars = "ABCDEFGHJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz0123456789=*&^?!@#$%_~-()";
	setlen = chars.length;

	do {
		pass = "";
		for (x = 0; x < len; x++) {
			i = Math.floor(Math.random() * setlen);
			pass += chars.charAt(i);
		}
		score = passwordStrength(pass);
	} while (score < 4);

	target.value = pass;
	target.type = 'text'; /* make sure the generated password is visible (readable on screen) */

	return pass;
}


