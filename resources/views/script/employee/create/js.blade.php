
<script>
$(document).ready(function(){

    $('#e_id').on('blur', function(){
        var itemName = "{{ $ApplicationSetting->item_name  }}";
        var e_id = $("#e_id").val();
        if(e_id)
        {
            $.ajax({
                url: '{{ url('employee/uniqueEidCheck') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                type:"GET",
                dataType : 'JSON',
                data:{e_id:e_id},
                success:function(response){
                    console.log(response);
                    if(response.status === 1){
                        $("#e_id").val("");
                        Swal.fire(
                            itemName,
                            '{{ __('Employee Id Already Use In This Company') }}',
                            'warning'
                        );
                        return;
                    }
                }
            });
        }


    });
});

$(document).ready(function() {

    $('.birth_date').flatpickr({
        dateFormat: "Y-m-d"
    });

    $('.datepicker').flatpickr({
        dateFormat: "Y-m-d"
    });

    $('.dropify').dropify();
    $('.dropify-fr').dropify({
        messages: {
            default: 'Glissez-déposez un fichier ici ou cliquez',
            replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
            remove: 'Supprimer',
            error: {
                'fileSize': 'The file size is too big  max.',
                'fileFormat': 'The image format is not allowed only.'
            }
        }
    });
    var drEvent = $('#input-file-events').dropify();
    drEvent.on('dropify.beforeClear', function(event, element) {
        return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
    });
    drEvent.on('dropify.afterClear', function(event, element) {
        alert('File deleted');
    });
    drEvent.on('dropify.errors', function(event, element) {
        console.log('Has Errors');
    });
    var drDestroy = $('#input-file-to-destroy').dropify();
    drDestroy = drDestroy.data('dropify')
    $('#toggleDropify').on('click', function(e) {
        e.preventDefault();
        if (drDestroy.isDropified()) {
            drDestroy.destroy();
        } else {
            drDestroy.init();
        }
    })
});
</script>

<script>

    var form = $(".validation-wizard").show();

    $(".validation-wizard").steps({
        headerTag: "h6",
        bodyTag: "section",
        transitionEffect: "fade",
        titleTemplate: '<span class="step">#index#</span> #title#',
        labels: {
            finish: "Submit"
        },
        onStepChanging: function(event, currentIndex, newIndex) {
            return currentIndex > newIndex || !(3 === newIndex && Number($("#age-2").val()) < 18) && (currentIndex < newIndex && (form.find(".body:eq(" + newIndex + ") label.error").remove(), form.find(".body:eq(" + newIndex + ") .error").removeClass("error")), form.validate().settings.ignore = ":disabled,:hidden", form.valid())
        },
        onFinishing: function(event, currentIndex) {
            return form.validate().settings.ignore = ":disabled", form.valid()
        },
        onFinished: function(event, currentIndex) {
            var itemName = "{{ $ApplicationSetting->item_name  }}";
            var queryString = new FormData($("#employee_create_form")[0]);
            $.ajax({
                url: "{{ route('employee.store') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                type:'POST',
                data:queryString,
                dataType : 'JSON',
                cache: false,
                contentType: false,
                processData: false,
                success:function(response){
                    if(response.status === "failed"){
                        toastr.error('Oops Something Wrong').then(function() {
                            document.location.href="{{ route('employee.create') }}";
                        });
                    }
                    else if(response.status === "success"){
                        toastr.success('','Employee Create Successfully', {
                            timeOut: 2000,
                            onHidden: function() {
                                document.location.href="{{ route('employee.index') }}";
                            }
                        })
                    }
                },
                error:function(response) {
                     $.each(response.responseJSON.errors,function(field_name,error){
                        toastr.error(error);
                    })
                }
            });
        }
    }), $(".validation-wizard").validate({
        ignore: "input[type=hidden]",
        errorClass: "element",
        successClass: "text-success",
        style: "color:red",
        highlight: function(element, errorClass) {
            $(element).removeClass(errorClass)
        },
        unhighlight: function(element, errorClass) {
            $(element).removeClass(errorClass)
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element) 
        },
        rules: {
            email: {
                email: !0
            },

            password:{
                required: true,
                minlength: 6
            },
            confirm_password:{
                required: true,
                minlength: 6,
                equalTo: "#password"
            }


        }
    })
</script>