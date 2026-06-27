window.addEventListener("load", function() {
    
    // 1. Signup Form Logic
    var signup_form = document.getElementById("signup-form");
    if (signup_form) {
        signup_form.addEventListener("submit", function(event) {
            event.preventDefault();
            var formData = new FormData(signup_form);

            fetch("api/signup_submit.php", {
                method: "POST",
                body: formData
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(json) {
                alert(json.message);
                if (json.success) {
                    window.location.reload(); 
                }
            })
            .catch(function(error) {
                alert("Something went wrong!");
            });
        });
    }

    // 2. Login Form Logic
    var login_form = document.getElementById("login-form");
    if (login_form) {
        login_form.addEventListener("submit", function(event) {
            event.preventDefault();
            var formData = new FormData(login_form);

            fetch("api/login_submit.php", {
                method: "POST",
                body: formData
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(json) {
                if (json.success) {
                    window.location.reload(); 
                } else {
                    alert(json.message);
                }
            })
            .catch(function(error) {
                alert("Something went wrong!");
            });
        });
    }

    // 3. Heart Icon Logic (Toggling)
    var is_interested_images = document.getElementsByClassName("is-interested-image");
    for (var i = 0; i < is_interested_images.length; i++) {
        is_interested_images[i].addEventListener("click", function(event) {
            var clicked_element = event.target;
            var property_id = clicked_element.getAttribute("property_id");
            
            var formData = new FormData();
            formData.append("property_id", property_id);

            fetch("api/toggle_interested.php", {
                method: "POST",
                body: formData
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(json) {
                if (json.success) {
                    // Update classes: let CSS handle the color to prevent blinking
                    if (json.is_interested) {
                        clicked_element.classList.remove("far");
                        clicked_element.classList.add("fas");
                    } else {
                        clicked_element.classList.remove("fas");
                        clicked_element.classList.add("far");
                    }
                } else {
                    alert(json.message);
                }
            })
            .catch(function(error) {
                console.error("Error:", error);
            });
        });
    }

 // 4. Final Review Form Logic (No Alert, Just Reload)
var review_form = document.getElementById("review-form");
if (review_form) {
    review_form.addEventListener("submit", function(event) {
        event.preventDefault();
        
        var formData = new FormData(review_form);

        fetch("api/submit_review.php", {
            method: "POST",
            body: formData
        })
        .then(function() {
            // No matter what the server sends back, just reload the page.
            // This is the safest way to ensure your review shows up.
            window.location.reload(); 
        });
    });
}

});