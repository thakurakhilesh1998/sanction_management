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
        let isCompleted=$('#isCompleted').val();
        let uc_file=$('#uc_file')[0];
        let selectedFile=uc_file.files[0];
        let images=$('#imageInput')[0];
    
        // Validate isCompleted
        if(isCompleted==='-1')
        {
            isValid=false;
            $("#isCompleted").next(".error").remove();
            $("#isCompleted").after("<span class='error'>Please select.</span>"); 
            return isValid;
        }
        else
        {
            $("#isCompleted").next(".error").remove();
        }
       
        if(isCompleted==='no')
        {
        // Validate Percentage
         if(!isValidPercentage(percentage_san))
            {
                isValid=false;
                $("#p_completed_per").next(".error").remove();
                $("#p_completed_per").after("<span class='error'>Please enter valid percentage value</span>");
                return isValid;
            }
            else
            {
                $("#p_completed_per").next(".error").remove();
            }   
        }
        if(isCompleted==='yes')
        {
              // Validate If UC file is selected
        if(!selectedFile)
        {
            isValid=false;
            $("#uc_file").next(".error").remove();
            $("#uc_file").after("<span class='error'>Please select a file.</span>");
            return isValid;
        }
        else if(selectedFile.type!=="application/pdf")
        {   
            isValid=false;
            $("#uc_file").next(".error").remove();
            $("#uc_file").after("<span class='error'>Please select a PDF file.</span>");
            return isValid;
        }
        else if(selectedFile.size>2*1024*1024)
        {
            isValid=false;
            $("#uc_file").next(".error").remove();
            $("#uc_file").after("<span class='error'>File size should not exceed 2 MB.</span>");
            return isValid;
        }
        else
        {
            $("#uc_file").next(".error").remove();
        }
        }
        if(images.files.length!=='')
        {
            isValid=validateProgressImage();
            return isValid;
        }
        return isValid;
    }

    function isValidPercentage(value) {
        if(value===-1)
        {
            return false;
        }
        else
        {
            return true;
        }
  }

  function validateProgressImage()
  {
    let images=$('#imageInput')[0];
    let files=images.files;
    if(files.length>3)
    {
        $("#imageInput").next(".error").remove();
        $("#imageInput").after("<span class='error'>You can select only 3 images.</span>");
        return false;
    }
    for( let i=0;i<files.length;i++)
    {
        let file=files[i]
        if(file.size>400*1024)
        {
            $("#imageInput").next(".error").remove();
            $("#imageInput").after("<span class='error'>Image size should be less than 400Kb.</span>");
            return false;
        }
        let allowedTypes=['image/jpeg', 'image/jpg', 'image/png'];

        if(allowedTypes.indexOf(file.type)===-1)
        {
            $("#imageInput").next(".error").remove();
            $("#imageInput").after("<span class='error'>Images of only jpeg, jpg and png are allowed.</span>");
            return false; 
        }
    }
    $("#imageInput").next(".error").remove();
    return true;
  }

});