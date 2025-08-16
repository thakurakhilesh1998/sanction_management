$(document).ready(function () {
    $('#gp_status').submit(function(event) {
        if (!validateForm()) {
            event.preventDefault();  // Prevent form submission if validation fails
        }
    });

    function validateForm() {
        let isValid = true;
        let rooms = $('#rooms').val();
        let lat = $('#lat').val();
        let long = $('#long').val();
        

        // Clear previous error messages
        $(".error").remove();

        // Validate rooms (must be a positive integer)
        if (!/^\d+$/.test(rooms) || parseInt(rooms) <= 0) {
            isValid = false;
            $('#rooms').after("<span class='error'>Please enter a valid Room number.</span>");
        }

        // Validate latitude (exactly 2 digits before decimal and 6 digits after)
        if (!/^\d{2}\.\d{6}$/.test(lat)) {
            isValid = false;
            $('#lat').after("<span class='error'>Please enter a valid Latitude with 2 digits before the decimal and 6 digits after.</span>");
        }

        // Validate longitude (exactly 2 digits before decimal and 6 digits after)
        if (!/^\d{2}\.\d{6}$/.test(long)) {
            isValid = false;
            $('#long').after("<span class='error'>Please enter a valid Longitude with 2 digits before the decimal and 6 digits after.</span>");
        }

        // Validate the file input
        let pgharupload = $('#pgharupload')[0].files[0];
        if (!pgharupload) {
            isValid = false;
            $('#pgharupload').after("<span class='error'>Please select a Panchayat Ghar Image.</span>");
        } else {
            let filename = pgharupload.name;
            let fileExtension = filename.split('.').pop().toLowerCase();
            let allowedExtensions = ['jpeg', 'jpg', 'png'];

            // Validate file extension
            if (allowedExtensions.indexOf(fileExtension) === -1) {
                isValid = false;
                $('#pgharupload').after("<span class='error'>Please select a valid Panchayat Ghar Image (jpeg, jpg, png).</span>");
            }

            // Validate file size (must be less than 400 KB)
            let fileSize = pgharupload.size;
            if (fileSize > 400 * 1024) {
                isValid = false;
                $('#pgharupload').after("<span class='error'>Panchayat Ghar Image should be less than 400 KB.</span>");
            }
        }

        return isValid;
    }
});
