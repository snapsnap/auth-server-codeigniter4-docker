
$(document).ready(function(){

    /**
     * Delete App
     */
    $(document).on('click','#delApp',function(e){
        e.preventDefault();
        var appId = $(this).attr('data-appid');
        if(appId == ''){
            swal({
                icon: 'error',
                title: 'Oops...',
                text: 'ID kosong!',
                footer: '<a href>Why do I have this issue?</a>'
            });
        }else{
            swal({
                title: 'Anda Yakin ?',
                text: "Periksa kembali sebelum melanjutkan",
                icon: 'warning',
                closeOnClickOutside: false,
                buttons: ["Batal", "Ya, Hapus!"]
            }).then((willSave) => {
                if (willSave) {
                    $.ajax({
                        type: "POST",
                        url: base_url + 'partner/partner/deleteApp',
                        datatype: "php",
                        data: {
                            appId : appId
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
                            console.log(result);
                            HoldOn.close();
                            if (result.data) {
                                swal({
                                    icon: "success",
                                    title: "Berhasil!",
                                    text: "Sukses ...",
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
    
    /**
     * Open Modal
     */
    $(document).on('click','.ckey', function(e){
        e.preventDefault();
        var keyName = $(this).attr('data-key');
        var appId = $(this).attr('data-appid');
        if(keyName == ''){
            swal({
                icon: 'error',
                title: 'Oops...',
                text: 'Public Key kosong!',
                footer: '<a href>Why do I have this issue?</a>'
            });
        }else{
            $.ajax({
                type: "POST",
                url: base_url + 'partner/partner/getKeyContent',
                datatype: "php",
                data: {
                    keyName : keyName
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
                    $("#ModalKey").find('input#hkeyName').val(keyName);
                    $("#ModalKey").find('textarea#pubKey').html(result);
                    $("#ModalKey").modal('show', {
                        backdrop: 'true'
                    });
                }
            }); //end AJAX
        }
    });

    /**
     * Update Key
     */
    $(document).on('click','#upKey',function(e){
        e.preventDefault();
        var keyName = $(document).find('input#hkeyName').val();
        var newKey = $(document).find('textarea#pubKey').val();
        if(newKey == ''){
            swal({
                icon: 'error',
                title: 'Oops...',
                text: 'Public Key kosong!',
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
                        url: base_url + 'partner/partner/updateClientPubKey',
                        datatype: "php",
                        data: {
                            keyName : keyName,
                            newKey : newKey
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
                            if (result > 0) {
                                swal({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Gagal !',
                                    footer: '<a href>Why do I have this issue?</a>'
                                });
                            } else {
                                swal({
                                    icon: "success",
                                    title: "Berhasil!",
                                    text: "Data berhasil diproses.",
                                    button: "Okay"
                                }).then(function(url){
                                    location.reload();
                                });
                            }
                        }
                    }); //end AJAX
                }
            });
        }
    });
});