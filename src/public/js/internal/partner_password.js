
$(document).ready(function(){

    /**
     * Update Profile
     */
    $(document).on('click','#savePass',function(e){
        e.preventDefault();

        var sandiLama = $(document).find('form#formPass').find('input#sandiLama').val();
        var sandiBaru = $(document).find('form#formPass').find('input#sandiBaru').val();
        var sandiKonfirm = $(document).find('form#formPass').find('input#sandiKonfirm').val();

        if(sandiLama == '' || sandiBaru == '' || sandiKonfirm == ''){
            swal({
                icon: 'error',
                title: 'Oops...',
                text: 'Data ada yang kosong!',
                footer: '<a href>Why do I have this issue?</a>'
            });
        }else if(sandiKonfirm != sandiBaru){
            swal({
                icon: 'error',
                title: 'Oops...',
                text: 'Konfirmasi password tidak sesuai!',
                footer: '<a href>Why do I have this issue?</a>'
            });
        }else if(sandiBaru.length < 6){
            swal({
                icon: 'error',
                title: 'Oops...',
                text: 'Sandi baru minimal 6 karakter!',
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
                        url: base_url + 'partner/partner/updatePassword',
                        datatype: "php",
                        data: {
                            oldPass : sandiLama,
                            newPass : sandiBaru
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
                                    location.href = base_url + 'auth/logout';
                                });
                            } else {
                                swal({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: result.message,
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