document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(form);

            fetch('../php/login_process.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '../html/home.php';
                } else {
                    document.getElementById('login-error').textContent = data.message;
                }
            })
            .catch(() => {
                document.getElementById('login-error').textContent = "An error occurred. Please try again.";
            });
        });
    }
});