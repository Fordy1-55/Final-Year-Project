document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('forgotForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(form);

            fetch('../php/forgot_password.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('forgot-error').textContent = '';
                document.getElementById('forgot-success').textContent = '';
                if (data.success) {
                    document.getElementById('forgot-success').textContent = data.message;
                } else {
                    document.getElementById('forgot-error').textContent = data.message;
                }
            })
            .catch(() => {
                document.getElementById('forgot-error').textContent = "An error occurred. Please try again.";
            });
        });
    }
});