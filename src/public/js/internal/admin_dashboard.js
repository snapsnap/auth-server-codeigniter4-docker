
$(document).ready(function(){

    /**
     * Delete App
     */
    $(document).on('click','.delApp',function(e){
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
    $(document).on('click','.btnGenerate', function(e){
        e.preventDefault();
        var appId = $(this).attr('data-appid');
        $("#ModalGen").find('input#hAppId').val(appId);
        $("#ModalGen").modal('show', {
            backdrop: 'true'
        });
    });

    /**
     * Setujui
     */
    $(document).on('click','.btn-setuju',function(e){
        e.preventDefault();
        var appid = $(this).attr('data-appid');
        var appstatus = $(this).attr('data-val');

        if(appid == ''){
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
                buttons: ["Batal", "Ya, Lanjutkan!"]
            }).then((willSave) => {
                if (willSave) {
                    $.ajax({
                        type: "POST",
                        url: base_url + 'admin/admin/updateAppStatus',
                        datatype: "php",
                        data: {
                            appId : appid,
                            appStatus : appstatus
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
                            if (result.data) {
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

    /**
     * Tolak
     */
    $(document).on('click','.btn-tolak',function(e){
        e.preventDefault();
        var appid = $(this).attr('data-appid');
        var appstatus = $(this).attr('data-val');

        if(appid == ''){
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
                buttons: ["Batal", "Ya, Lanjutkan!"]
            }).then((willSave) => {
                if (willSave) {
                    $.ajax({
                        type: "POST",
                        url: base_url + 'admin/admin/updateAppStatus',
                        datatype: "php",
                        data: {
                            appId : appid,
                            appStatus : appstatus
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
                            if (result.data) {
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

    /**
     * Generate Client ID
     */
    $(document).on('click','.btnClientId',function(e){
        e.preventDefault();
        var appId = $("#ModalGen").find('input#hAppId').val();
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
                buttons: ["Batal", "Ya, Lanjutkan!"]
            }).then((willSave) => {
                if (willSave) {
                    $.ajax({
                        type: "POST",
                        url: base_url + 'admin/admin/generateClientId',
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
     * Generate Client Secret
     */
    $(document).on('click','.btnClientSecret',function(e){
        e.preventDefault();
        var appId = $("#ModalGen").find('input#hAppId').val();
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
                buttons: ["Batal", "Ya, Lanjutkan!"]
            }).then((willSave) => {
                if (willSave) {
                    $.ajax({
                        type: "POST",
                        url: base_url + 'admin/admin/generateClientSecret',
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
     * Generate MAF Key
     */
    $(document).on('click','.btnGenMafKey',function(e){
        e.preventDefault();
        var appId = $("#ModalGen").find('input#hAppId').val();
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
                buttons: ["Batal", "Ya, Lanjutkan!"]
            }).then((willSave) => {
                if (willSave) {
                    $.ajax({
                        type: "POST",
                        url: base_url + 'admin/admin/generateMafKey',
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
                            if (result) {
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
});