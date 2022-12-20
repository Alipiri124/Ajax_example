<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{csrf_token()}}">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" 
	crossorigin="anonymous">    
	<title>Ajax</title>
	<link rel="stylesheet" href="css/style.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <h4 style="text-center">Ajax Example</h4>
<div class="table tab1 clearfix mx-auto">
 <div class="w-50 float-left">    
    <table class="table">All Student</table>
   <table class="table table-striped table-bordered table-hover">   
    <thead>
 	  <tr>
 		<th>#</th>
 		<th>Name</th>
 		<th>Surname</th>
 		<th>University</th> 
        <th>Action</th> 
 	  </tr>
    </thead>
   <tbody>
  	
   </tbody>	
  </table>
 </div>
<div class="w-50 float-right col-sm-5 mr-5">
    <div class="card">
        <div class="card-header">
            <span id="addS">Add New Student </span>
            <span id="updateS">Update Student </span>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" placeholder="Enter Name">  
                <span class="text-danger" id="nameError"></span>
            </div>
            <div class="form-group">
                <label for="surname">Surname</label>
                <input type="text" class="form-control" id="surname" placeholder="Enter Surname">
                <span class="text-danger" id="surnameError"></span>
            </div>
            <div class="form-group">
                <label for="univercity">Univercity</label>
                <input type="text" class="form-control" id="univercity" placeholder="Enter Univercity">
                <span class="text-danger" id="univercityError"></span>
            </div>
            <input type="hidden" id="id">
            <button type="submit" id="addButton" onclick="addData()" class="btn btn-primary">Add</button>
            <button type="submit" id="updateButton" onclick="updateData()" class="btn btn-primary">Update</button>
        </div>
    </div> 
  </div>
</div>    
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</script>
<script>
    $('#addS').show();
    $('#addButton').show();
    $('#updateS').hide();
    $('#updateButton').hide();

    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    function allData(){

      $.ajax({
        type: "GET",
        dataType:"json",
        url: "/student/all",
        success:function(response){
            var data = ""
            $.each(response,function(key, value){
              data = data + "<tr>"
              data = data + "<td>"+value.id+"</td>"
              data = data + "<td>"+value.name+"</td>"
              data = data + "<td>"+value.surname+"</td>"
              data = data + "<td>"+value.univercity+"</td>"
              data = data + "<td>"
              data = data + "<button class='btn btn-sm btn-primary mr-2' onclick='editData("+value.id+")'>Edit</button>"
              data = data + "<button class='btn btn-sm btn-danger' onclick='deleteData("+value.id+")'>Delete</button>"
              data = data + "</td>"
              data = data + "</tr>"
            })
            $('tbody').html(data);
        }
      })
    }
    allData();
   //clearData fuunksiyasi verende inputlarda evvelki yazilar qalmir
    function clearData()
    {
         $('#name').val('');
         $('#surname').val('');
         $('#univercity').val('');
         $('#nameError').text('');
         $('#surnameError').text('');
         $('#univercityError').text('');
     }

    function addData(){
        var name = $('#name').val();
        var surname = $('#surname').val();
        var univercity = $('#univercity').val();

        $.ajax({
           type: "POST",
           dataType : "json",
           data:{name:name,surname:surname,univercity:univercity},
           url:"/student/store",
           success:function(data){
             clearData();
             allData();
        //--------sweetalert--------
            const Msg = Swal.mixin({
             toast: true,
             position: 'top-end',
             icon: 'success',             
             showConfirmButton: false,
             timer: 2000
             })  

             Msg.fire({
             type: 'success',
             title: 'Data added success',             
             }) 
             //-------endalert             
           },
           error:function(error)
           {            
            $('#nameError').text(error.responseJSON.errors.name);
            $('#surnameError').text(error.responseJSON.errors.surname);
            $('#univercityError').text(error.responseJSON.errors.univercity);         
           }
        })
    }

    //---------------------start edit data----------------
     function editData(id)
     {
        $.ajax({
            type:"GET",
            dataType:"json",
            url:"/student/edit/"+id,
            success:function(data){
                $('#addS').hide();
                $('#addButton').hide();
                $('#updateS').show();
                $('#updateButton').show();

               $('#id').val(data.id);
               $('#name').val(data.name);
               $('#surname').val(data.surname);
               $('#univercity').val(data.univercity);

            }
        })
     }     
    //-------------------------end edit data---------

    //---------------------start update data----------------
     function updateData(){
        var id = $('#id').val();
        var name = $('#name').val();
        var surname = $('#surname').val();
        var univercity = $('#univercity').val();

        $.ajax({
            type:"POST",
            dataType:"json",
            data:{name:name,surname:surname,univercity:univercity},
            url:"/student/update/"+id,
            success:function(data)
            { 
                $('#addS').show();
                $('#addButton').show();
                $('#updateS').hide();
                $('#updateButton').hide();          
               clearData();
               allData();
               //--------sweetalert--------
            const Msg = Swal.mixin({
             toast: true,
             position: 'top-end',
             icon: 'success',             
             showConfirmButton: false,
             timer: 2000
             })  

             Msg.fire({
             type: 'success',
             title: 'Data Update success',             
             }) 
             //-------endalert
            },
            error:function(error)
            {
              $('#nameError').text(error.responseJSON.errors.name);
              $('#surnameError').text(error.responseJSON.errors.surname);
              $('#univercityError').text(error.responseJSON.errors.univercity); 
            }

        })
     }
    //-------------------------end update data-------------------------

    //------------------------start delete data------------------------
   function deleteData(id)
   {
    
        const swalWithBootstrapButtons = Swal.mixin({
               customClass: 
               {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
               },
               buttonsStyling: false
               })

                swalWithBootstrapButtons.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Yes, delete it!',
                  cancelButtonText: 'No, cancel!',
                  reverseButtons: true
                }).then((result) => {
                  if (result.isConfirmed) {     
                            $.ajax({
                                       type:"GET",
                                       dataType:"json",
                                       url:"/student/destroy/"+id,
                                        success:function(data)
                                        {
                                             $('#addS').show();
                                             $('#addButton').show();
                                             $('#updateS').hide();
                                             $('#updateButton').hide();                    
                                             allData();
                                        }
                                    })

                            swalWithBootstrapButtons.fire(
                              'Deleted!',
                              'Your file has been deleted.',
                              'success'
                            )
                          }
                          else if(
                            
                            result.dismiss === Swal.DismissReason.cancel
                          ) {
                            swalWithBootstrapButtons.fire(
                              'Cancelled',
                              'Your imaginary file is safe :)',
                              'error'
                            )
                          }
                        })                 
    }     
</script>
</body>
</html>