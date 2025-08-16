$('document').ready(function()
{
    $('#progress_form').submit(function(event)
    {
        if(!validateForm())
        {
            event.preventDefault();
        }
    });

    function validateForm()
    {
        isValid=true;
        let percentage_san=$('#p_completed_per').val();
    
        if(percentage_san==='-1')
        {
            isValid=false;
            $("#p_completed_per").next(".error").remove();
            $("#p_completed_per").after("<span class='error'>Please select.</span>"); 
            return isValid;
        }
        else
        {
            $("#p_completed_per").next(".error").remove();
        }  
        return isValid;
    }
});