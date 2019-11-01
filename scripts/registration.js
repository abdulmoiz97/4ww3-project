// Following function is used for form validation in Java script
// Function goes through inputs on page and checks for completion and data pattern matching.
function validate() {

    // Username checked for empty string
    if (document.getElementById("username").value == "") {
        alert("Please select your Username!");
        document.getElementById("username").focus();
        return false;
    }

    // Email checked to contain an @ sign along with a period as is standard
    var email = document.getElementById("email").value;
    if (!email.includes("@") || !email.includes(".")) {
        alert("Please enter a correct email!");
        document.getElementById("email").focus();
        return false;
    }

    // Password is pattern matched against criteria below
    // ensures the password is 6 to 12 digits with 1 lower case, 1 upper case and 1 digit.
    var criteria = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,12}$/;
    if (!document.getElementById("password").value.match(criteria)) {
        alert("Password must be 6 to 12 characters with at least one numeric digit, one uppercase and one lowercase letter");
        document.getElementById("password").focus();
        return false;
    }

    // Confirmed password checked to equal original password as is standard
    if (document.getElementById("password2").value != document.getElementById("password").value){
        alert("Passwords must match!");
        document.getElementById("password2").focus();
        return false;
    }

    // All three radio buttons are scanned to make sure atleast one of them was selected
    if (!(document.getElementById("male").checked || 
        document.getElementById("female").checked || 
        document.getElementById("other").checked))
        {
            alert("Please select your gender!");
            document.getElementById("male").focus();
            return false;
    }

    // Only a series of only successful passes will give a pass
    alert("Form validation in Javascript complete!")
    return (true);
}