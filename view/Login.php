<?php 
include ("www/header.inc.php"); ?>
<div class="container mt-4">
    <h2>Login</h2>
    <form id="loginForm" method="post">
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control" aria-describedby="passwordHelpBlock">
        </div>
        <button type="submit" class="btn btn-primary">Sign in</button>
    </form>
</div>
<?php include ("www/footer.inc.php"); ?>

<script>
// Listen for the form submission event
document.getElementById("loginForm").addEventListener("submit", function(event) {
    // Prevent the default form submission behavior
    event.preventDefault(); 
    
    // Get the values of the email and password fields
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;

    // Send a POST request to a specified URL with email and password in JSON format
    fetch("https://dev-lecordi223.users.info.unicaen.fr/sae401/bikestores/login/e8f1997c763/", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ email: email, password: password })
    })
    // Handle the response
    .then(response => {
        // Check if the response is not OK
        if (!response.ok) {
            throw new Error("Login failed");
        }
        // Parse the response as JSON
        return response.json();
    })
    // Further processing of the data received
    .then(data => {
        // Check if login was successful
        if (data.success) {
            // Encode certain data to Base64
            var role = btoa(data.employee_role);
            var id = btoa(data.store_id);
            var employeeId = btoa(data.employee_id);
            // Set cookies with encoded data
            document.cookie = "role=" + role + "&" + id + "; expires= " + new Date(Date.now() + 3600 * 1000).toUTCString() + "; path=/";
            document.cookie = "id=" +employeeId+ "; expires= " + new Date(Date.now() + 3600 * 1000).toUTCString() + "; path=/";
            // Redirect to the home page
            window.location.href = "index.php?action=home";
        } else {
            // If login was not successful, throw an error with the message from the server response or a default message
            throw new Error(data.message || "Login failed");
        }
    })
    // Catch any errors that occur during the process
    .catch(error => {
        // Display an alert with the error message
        alert("Login failed: " + error.message);
    });
});
</script>

