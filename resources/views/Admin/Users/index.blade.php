@extends('layouts/admin')
@section('main')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="container text-black">
        <h1 class="h3 mb-3 text-gray-800">Add User</h1>
        @if($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{$error}}</div>
            @endforeach
        </div>
        @endif
        <form method="POST" action="{{url('admin/add-user')}}" id="createUser">
            @csrf
            <div class="mb-3">
              <label for="Username" class="form-label">Username</label>
              <input type="text" class="form-control" id="username" aria-describedby="username" name="username" autocomplete="off">
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" autocomplete="off">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" oncopy="return false;" onpaste="return false;" oncut="return false;" autocomplete="off">
                <small>Password must be 8 of character, must have 1 Uppercase,1 Lowercase, one Digit and 1 Special character.</small>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control password_cnf" id="password" name="password_confirmation" oncopy="return false;" onpaste="return false;" oncut="return false;" autocomplete="off">
                <small>Password must be 8 of character, must have 1 Uppercase,1 Lowercase, one Digit and 1 Special character.</small>
            </div>
            <div class="mb-3">
                <label for="User Role" class="form-label">Select UserRole</label>
                <select name="role" id="role" class="form-control">
                    <option value="0">--Select Role--</option>
                    <option value="admin">Admin</option>
                    <option value="dir">Directorate</option>
                    <option value="district">District</option>
                    <option value="block">Block</option>
                    <option value="gp">Gram Panchayat</option>
                    <option value="xen">Executive Engineer</option>
                </select>
            </div>

            <div class="mb-3" id="districts_select">
                <label for="District Name" class="form-label">Select District</label>
                <select name="district" id="districtlist" class="form-control">
                    <option value="0">--Select District--</option>
                    <option value="Bilaspur">Bilaspur</option>
                    <option value="Chamba">Chamba</option>
                    <option value="Hamirpur">Hamirpur</option>
                    <option value="Kangra">Kangra</option>
                    <option value="Kinnaur">Kinnaur</option>
                    <option value="Kullu">Kullu</option>
                    <option value="Lahul And Spiti">Lahul And Spiti</option>
                    <option value="Mandi">Mandi</option>
                    <option value="Shimla">Shimla</option>
                    <option value="Sirmaur">Sirmaur</option>
                    <option value="Solan">Solan</option>
                    <option value="Una">Una</option>
                </select>
            </div>
            <div class="mb-3" id="block_select">
            </div>
            <div class="mb-3" id="gp_select">
            </div>
            <div class="mb-3" id="xen_select">
                <label for="Xen Select">Select Executive Engineer</label>
                <select class='form-control' name="zone" id='xen'>
                    <option value="-1">--Select Executive Engineer--</option>
                    <option value="Shimla">Shimla</option>
                    <option value="Mandi">Mandi</option>
                    <option value="Dharamshala">Dharamshala</option>
                    <option value="Bangana">Bangana</option>
                    </select>
            </div>
            <button type="submit" class="btn btn-primary">Create User</button>
          </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    async function encryptPassword(password) {
        const encoder = new TextEncoder();
        const data = encoder.encode(password);
        const aesKey = '0d78c5f79ece7388c918eac45a7aad89';
        const keyData = new TextEncoder().encode(aesKey);
        const key = await crypto.subtle.importKey('raw', keyData, 'AES-GCM', false, ['encrypt']);
        
        const iv = crypto.getRandomValues(new Uint8Array(12));
        const encrypted = await crypto.subtle.encrypt({ name: 'AES-GCM', iv: iv }, key, data);
        
        const encryptedArray = new Uint8Array(encrypted);
        const tag = encryptedArray.slice(-16);
        const cipherText = encryptedArray.slice(0, -16);

        return {
            encryptedPassword: btoa(String.fromCharCode(...cipherText)),
            iv: btoa(String.fromCharCode(...iv)),
            tag: btoa(String.fromCharCode(...tag))
        };
    }



    $(document).ready(function()
    {
        $('#xen_select').hide();
        let dataFromJson;
        $.getJSON("{{ asset('assets/json/output.json') }}",function(data)
        {
            dataFromJson=data;
        })

            $('#districts_select').hide();
        $("select#role").change(function()
        {
            $('#districts_select').hide();
            let selectedRole=$(this).children("option:selected").val();
            if(selectedRole==='district')
            {
                $('#xen_select').hide();
                $("#districts_select").show();
            }
            if(selectedRole==='block')
            {
                $('#xen_select').hide();
                $("#districts_select").show();
                $('#districts_select').on("change","#districtlist",function()
                {
                    let selectedDistrict=$(this).val();
                    showBlocks(Object.keys(dataFromJson.data[selectedDistrict]));
                });
            }
            if(selectedRole==='gp')
            {
                $('#xen_select').hide();
                let selectedDistrict;
                $("#districts_select").show();
                $('#districts_select').on("change","#districtlist",function()
                {
                    selectedDistrict=$(this).val();
                    showBlocks(Object.keys(dataFromJson.data[selectedDistrict]));
                });
                $('#block_select').on("change","#block-list",function()
                {
                    let selectedBlock=$(this).val();
                    displayPanchayats(dataFromJson.data[selectedDistrict][selectedBlock]);
                });
            }

            if(selectedRole==='xen')
            {
                $('#xen_select').show();
            }
        });

        function showBlocks(blocks) {
            var blockList = '<label for="Block name" class="form-label">Select Block Name</label><select id="block-list" class="form-control" name="block_name"><option value="-1">--Select Block--</option>';
            $.each(blocks, function(index, block) {
                blockList += '<option value="' + block + '">' + block + '</option>';
            });
            blockList += '</select>';
            $("#block_select").html(blockList);
        }

        function displayPanchayats(panchayats) {
             var panchayatList = '<label for="Gram Panchayat name" class="form-label">Select Gram Panchayat Name</label><select id="panchayat-list" class="form-control" name="gp_name"><option value="-1">--Select Gram Panchayat--</option>';
            $.each(panchayats, function(panchayat, constituencies) {
            panchayatList += '<option value="' + panchayat + '">' + panchayat + '</option>';
             });
             panchayatList += '</select>';
            $("#gp_select").html(panchayatList);
        }

        $('form').on('submit',async function (event) {
            event.preventDefault();
            if(!validateForm()) return;
            const password=$('#password').val();
            const passwordConfirm=$('.password_cnf').val();
            const {encryptedPassword:encryptedP,iv:ivp,tag:tagp}=await encryptPassword(password);
            const {encryptedPassword:encryptcnf,iv:ivc,tag:tagc}=await encryptPassword(passwordConfirm);
            $('#password').val(encryptedP);
            $('.password_cnf').val(encryptcnf);

            $('<input>').attr({ type: 'hidden', name: 'iv_p', value: ivp }).appendTo('#createUser');
            $('<input>').attr({ type: 'hidden', name: 'iv_cnf', value: ivc }).appendTo('#createUser');

            $('<input>').attr({ type: 'hidden', name: 'tag_p', value: tagp }).appendTo('#createUser');
            $('<input>').attr({ type: 'hidden', name: 'tag_cnf', value: tagc }).appendTo('#createUser');

            this.submit();
        })

        function validateForm()
        {
            let isValid=true;
            const password=$('#password').val();
            const passwordConfirm=$('.password_cnf').val();
            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/;
            if(!passwordRegex.test(password))
            {
                isValid=false;
                $("#password").next(".error").remove();
                $("#password").after("<span class='error'>Please enter valid password.</span>"); 
                e.preventDefault();
                return false;
                
            }

            if(password!=passwordConfirm)
            {
                isValid=false;
                $("#password").next(".error").remove();
                $("#password").after("<span class='error'>Password field and confirm password should be same.</span>"); 
                e.preventDefault();
                return false;
            }
            return isValid;
        }
    });
</script>
@endsection
