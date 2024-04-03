
$(document).ready(function(){

    /**
     * Update Profile
     */
    $(document).on('click','#saveProfile',function(e){
        e.preventDefault();

        var partnerId = $(document).find('input#hPartnerId').val();

        var nama = $(document).find('form#formProfile').find('input#nama').val();
        var email = $(document).find('form#formProfile').find('input#email').val();
        var perusahaan = $(document).find('form#formProfile').find('input#perusahaan').val();
        var phone = $(document).find('form#formProfile').find('input#phone').val();
        var alamat = $(document).find('form#formProfile').find('input#alamat').val();

        if(partnerId == '' || nama == '' || email == '' || perusahaan == '' || phone == '' || alamat == ''){
            swal({
                icon: 'error',
                title: 'Oops...',
                text: 'Data ada yang kosong!',
                footer: '<a href>Why do I have this issue?</a>'
            });
        }else{
            swal({
                title: 'Anda Yakin ?',
                text: "Periksa kembali sebelum melanjutkan",
                icon: 'warning',
                closeOnClickOutside: false,
                buttons: ["Batal", "Ya, Simpan!"]
            }).then((willSave) => {
                if (willSave) {
                    $.ajax({
                        type: "POST",
                        url: base_url + 'partner/partner/updateProfile',
                        datatype: "php",
                        data: {
                            partnerId : partnerId,
                            nama : nama,
                            email : email,
                            perusahaan : perusahaan,
                            phone : phone,
                            alamat : alamat
                        },
                        cache: false,
                        beforeSend: function() {
                            HoldOn.open({
                                theme: "sk-circle",
                                message: "Wait A Minute... ",
                                backgroundColor: "#fff",
                                textColor: "#000"
                            });
                                    
                        },
                        success: function(ajaxData) {
                            result = JSON.parse(ajaxData);
                            HoldOn.close();
                            if (result.data == true) {
                                swal({
                                    icon: "success",
                                    title: "Berhasil!",
                                    text: "Data berhasil diproses.",
                                    button: "Okay"
                                }).then(function(url){
                                    location.reload();
                                });
                            } else {
                                swal({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Gagal !',
                                    footer: '<a href>Why do I have this issue?</a>'
                                });
                            }
                        }
                    }); //end AJAX
                }
            });
        }
    });
});