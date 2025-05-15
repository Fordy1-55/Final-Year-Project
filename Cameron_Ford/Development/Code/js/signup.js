document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('signupForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(form);

            fetch('../php/signup_process.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('signup-error').textContent = '';
                document.getElementById('signup-success').textContent = '';
                if (data.success) {
                    document.getElementById('signup-success').textContent = "Sign up successful! Redirecting...";
                    setTimeout(() => {
                        window.location.href = '../html/home.php';
                    }, 1500);
                } else {
                    document.getElementById('signup-error').textContent = data.message;
                }
            })
            .catch(() => {
                document.getElementById('signup-error').textContent = "An error occurred. Please try again.";
            });
        });
    }
});