$(document).ready(function() {
    let csrfToken = $('meta[name="csrf-token"]').attr('content');
    let currentStatus = $('#progressData').data('completion-status');
    let updateUrl = $('#progressData').data('update-url');

    // console.log({{$progress->completion_percentage}});
    // Progress stages order
    const stageOrder = ['Tender Floated', 'Tender Awarded', 'Work Started', 'Partial Completion', 'Work Completed', 'Tender Cancelled'];

    // Show or hide image upload based on status
    function toggleImageInputs(selectedStatus) {
        $('#imageUploadRow').hide();
        
        if (selectedStatus === 'Work Started' || selectedStatus === 'Partial Completion' || selectedStatus === 'Work Completed') {
            $('#imageUploadRow').show();  // Show image upload only for these stages
        }
    }

    // Disable invalid progress stages based on the current stage
    function disableInvalidOptions(currentStatus) {
        const currentIndex = stageOrder.indexOf(currentStatus);

        $('#completion_percentage option').each(function() {
            let optionValue = $(this).val();
            let optionIndex = stageOrder.indexOf(optionValue);

            // Enable the next valid option in the sequence, and "Tender Cancelled" at any time
            if (optionIndex === currentIndex + 1) {
                $(this).attr('disabled', false);
            } else {
                $(this).attr('disabled', true);
            }

            if(optionValue==='Tender Cancelled')
            {
                if(currentStatus === 'Tender Floated' || currentStatus === 'Tender Awarded')
                {
                    $(this).attr('disabled', false);
                }
                else
                {
                    $(this).attr('disabled', true); 
                }
            }
        });
    }
    
    // Initial call on page load
    disableInvalidOptions(currentStatus);

    // Listen for status change to update UI
    $('#completion_percentage').change(function() {
        let selectedStatus = $(this).val();
        toggleImageInputs(selectedStatus);
    });

    // Handle the update button click
    $('#updateStatusBtn').click(function() {
        let selectedStatus = $('#completion_percentage').val();
        let isValid = true;

        // Validate image upload for required stages
        if (['Work Started', 'Partial Completion', 'Work Completed'].includes(selectedStatus)) {
            let imageFile = $('#statusImage')[0].files[0];
            if (!imageFile) {
                alert('Please upload an image for this stage.');
                isValid = false;
            } else if (!validateImageSize(imageFile)) {
                isValid = false;
            }
        }

        // Proceed with AJAX if validation passes
        if (isValid) {
            let remarks=$('#remarks').val();
            let formData = new FormData();
            formData.append('completion_status', selectedStatus);
            formData.append('remarks',remarks);
            if ($('#statusImage')[0].files.length > 0) {
                formData.append('status_image', $('#statusImage')[0].files[0]);
            }
            
            // Perform AJAX request
            $.ajax({
                url: updateUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    alert('Status updated successfully!');
                    if(response.redirect_url)
                    {
                        window.location.href=response.redirect_url;
                    }
                    else
                    {
                        location.reload();  // Reload page on success
                    }
                    
                },
                error: function(xhr) {
                    var response = JSON.parse(xhr.responseText);
                    alert(response.error);
                }
            });
        }
    });

    // Validate image size (max 400KB)
    function validateImageSize(file) {
        const maxSize = 1000 * 1024;  // 400KB
        if (file.size > maxSize) {
            alert('Image must be less than 400KB in size.');
            return false;
        }
        return true;
    }
});
