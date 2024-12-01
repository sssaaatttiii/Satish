$(document).ready(function() {
    $('#registrationForm').on('submit', function(e) {
        e.preventDefault();

        if (!validateForm()) {
            return;
        }

        var formData = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: 'process_registration.php',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    displayResult('success', response.message);
                    $('#registrationForm')[0].reset();
                } else {
                    displayResult('error', response.message);
                }
            },
            error: function() {
                displayResult('error', 'An unexpected error occurred.');
            }
        });
    });

    function validateForm() {
        var isValid = true;
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        var phoneRegex = /^\d{10}$/;

        $('.form-group').removeClass('error');

        if ($('#fullName').val().trim() === '') {
            $('#fullName').closest('.form-group').addClass('error');
            isValid = false;
        }

        if (!emailRegex.test($('#email').val())) {
            $('#email').closest('.form-group').addClass('error');
            isValid = false;
        }

        if (!phoneRegex.test($('#phone').val())) {
            $('#phone').closest('.form-group').addClass('error');
            isValid = false;
        }

        $('#registrationForm [required]').each(function() {
            if ($(this).val().trim() === '') {
                $(this).closest('.form-group').addClass('error');
                isValid = false;
            }
        });

        return isValid;
    }

    function displayResult(type, message) {
        var resultContainer = $('#resultContainer');
        resultContainer.html(message);
        resultContainer.removeClass('success error').addClass(type);
    }
});