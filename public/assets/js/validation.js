$(document).ready(function()
{
     // Validation function start
     $('#sanction').submit(function(event)
     {
         if(!validateForm())
         {
             event.preventDefault();
         }
     });
});
function validateForm()
{
    let isValid=true;
    let selectedFY=$('#financial_year').val();
    let districtSelected=$('#district-list').val();
    let blockSelected=$('#block-list').val();
    let gpSelected=$('#panchayat-list').val();
    let newGP=$("input[name='newGP']:checked").val();
    let sanctionAmount=$('#sanction_amt').val();
    let sanctionDate=$('#sanction_date').val();
    let sanction_Head=$('#sanction_head').val();
    let sanction_purpose=$('#sanction_purpose').val();
    // Validate Financial Year
    if(selectedFY==="-1")
    {
        isValid=false;
        $("#financial_year").next(".error").remove();
        $("#financial_year").after("<span class='error '>Please select Finacial Year</span>");
    }
    else
    {
        $("#financial_year").next(".error").remove();
    }
    // Validate District
    if(districtSelected==="-1")
    {
        isValid=false;
        $('#district-list').next(".error").remove();
        $('#district-list').after("<span class='error'>Please select District</span>")
    }
    else
    {
        $('#district-list').next(".error").remove();
    }
    // validate Block
    if(blockSelected==="-1")
    {
        isValid=false;
        $('#block-list').next(".error").remove();
        $('#block-list').after("<span class='error'>Please select Block</span>")
    }
    else
    {
        $('#block-list').next(".error").remove();
    }

    // Validate Gram Panchayat
    if(gpSelected==="-1")
    {
        isValid=false;
        $('#panchayat-list').next(".error").remove();
        $('#panchayat-list').after("<span class='error'>Please select Gram Panchayat</span>")
    }
    else
    {
        $('#panchayat-list').next(".error").remove();
    }

    // Validate New GP field
    if(!newGP)
    {
        isValid=false;
        $("input[name='newGP']").parent().next(".error").remove();
        $("input[name='newGP']").parent().after("<span class='error'>Please select if Gram Panchayat is new of old.</span>");
    }
    else
    {
        $("input[name='newGP']").parent().next(".error").remove();
    }

    // Validate Sanction amount
    
    if(sanctionAmount <=0 ||sanctionAmount==='')
    {
        isValid=false;
        $('#sanction_amt').next(".error").remove();
        $('#sanction_amt').after("<span class='error'>Please enter the valid amount</span>");
    }
    else
    {
        $('#sanction_amt').next(".error").remove();
    }
    // Validate Selected Date
    let currentDate = new Date().toISOString().split('T')[0]; // Get current date in YYYY-MM-DD format
    if(sanctionDate> currentDate ||sanctionDate=='')
    {
        isValid=false;
        $('#sanction_date').next(".error").remove();
        $('#sanction_date').after("<div class=' mt-2 alert alert-danger error'>Please enter the valid date</div>");
    }
    else
    {
        $('#sanction_date').next(".error").remove();
    }
    // Validate Sanction Head
    if(sanction_head==='-1')
    {
        isValid=false;
        $('#sanction_head').next(".error").remove();
        $('#sanction_head').after("<div class=' mt-2 alert alert-danger error'>Please enter the valid Sanction</div>");
    }
    else
    {
        $('#sanction_head').next(".error").remove();
    }
    // Validate Sanction Purpose
    if(sanction_purpose==='-1')
    {
        isValid=false;
        $('#sanction_purpose').next(".error").remove();
        $('#sanction_purpose').after("<div class=' mt-2 alert alert-danger error'>Please select Sanction Purpose</div>");
    }
    else
    {
        $('#sanction_purpose').next(".error").remove();
    }
    return isValid;
}