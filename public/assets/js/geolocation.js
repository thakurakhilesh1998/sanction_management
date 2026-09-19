document.addEventListener('DOMContentLoaded', function () {

    const button = document.getElementById('getLocation');

    if (!button) {
        return;
    }

    const status = document.getElementById('locationStatus');
    const coordinates = document.getElementById('coordinates');

    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const accuracyInput = document.getElementById('location_accuracy');
    const capturedAtInput = document.getElementById('location_captured_at');

    const latitudeDisplay = document.getElementById('latitudeDisplay');
    const longitudeDisplay = document.getElementById('longitudeDisplay');
    const accuracyDisplay = document.getElementById('accuracyDisplay');
    const capturedAtDisplay = document.getElementById('capturedAtDisplay');

    const accuracyWarning = document.getElementById('accuracyWarning');
    const accuracyGood = document.getElementById('accuracyGood');

    const form = document.getElementById('gp_status');


    // Get Location
    button.addEventListener('click', function () {

        if (!navigator.geolocation) {

            status.innerHTML =
                '<span class="text-danger">' +
                '<i class="fas fa-times-circle me-1"></i>' +
                'Geolocation is not supported by this device.' +
                '</span>';

            return;
        }


        button.disabled = true;

        button.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2"></span>' +
            'Getting Location...';

        status.innerHTML =
            '<span class="text-primary">' +
            '<i class="fas fa-spinner fa-spin me-1"></i>' +
            'Obtaining your current location...' +
            '</span>';

        accuracyWarning.classList.add('d-none');
        accuracyGood.classList.add('d-none');


        navigator.geolocation.getCurrentPosition(

            function (position) {

                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;
                const accuracy = position.coords.accuracy;
                const timestamp = position.timestamp;


                // Store values
                latitudeInput.value = latitude;
                longitudeInput.value = longitude;
                accuracyInput.value = accuracy;

                capturedAtInput.value =
                    new Date(timestamp).toISOString();


                // Display values
                latitudeDisplay.textContent =
                    latitude.toFixed(6);

                longitudeDisplay.textContent =
                    longitude.toFixed(6);

                accuracyDisplay.textContent =
                    accuracy.toFixed(1);

                capturedAtDisplay.textContent =
                    new Date(timestamp).toLocaleString();


                coordinates.classList.remove('d-none');


                // Accuracy message
                if (accuracy > 30) {

                    accuracyWarning.classList.remove('d-none');

                } else {

                    accuracyGood.classList.remove('d-none');

                }


                status.innerHTML =
                    '<span class="text-success">' +
                    '<i class="fas fa-check-circle me-1"></i>' +
                    'Location captured successfully' +
                    '</span>';


                button.disabled = false;

                button.innerHTML =
                    '<i class="fas fa-location-crosshairs me-2"></i>' +
                    'Refresh Location';

            },


            function (error) {

                let message =
                    'Unable to get your location.';


                switch (error.code) {

                    case error.PERMISSION_DENIED:
                        message =
                            'Location permission was denied.';
                        break;

                    case error.POSITION_UNAVAILABLE:
                        message =
                            'Location information is unavailable.';
                        break;

                    case error.TIMEOUT:
                        message =
                            'Location request timed out.';
                        break;

                }


                status.innerHTML =
                    '<span class="text-danger">' +
                    '<i class="fas fa-exclamation-triangle me-1"></i>' +
                    message +
                    '</span>';


                button.disabled = false;

                button.innerHTML =
                    '<i class="fas fa-location-crosshairs me-2"></i>' +
                    'Try Again';

            },


            {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 0
            }

        );

    });


    // Prevent form submission without location
    form.addEventListener('submit', function (event) {

        if (!latitudeInput.value || !longitudeInput.value) {

            event.preventDefault();

            status.innerHTML =
                '<span class="text-danger">' +
                '<i class="fas fa-exclamation-triangle me-1"></i>' +
                'Please capture the Geo-Location before saving the asset.' +
                '</span>';

            button.focus();
        }

    });

});