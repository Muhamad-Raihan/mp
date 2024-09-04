let attempt = 3; // Variable to count the number of attempts.

document.getElementById("loginForm").onsubmit = function(event) {
    if (attempt <= 1) {
        alert("You have exhausted all attempts. The page will now close.");
        window.close(); // This will close the window after 3 unsuccessful attempts.
    } else {
        attempt--; // Decrementing the attempt.
    }
};
