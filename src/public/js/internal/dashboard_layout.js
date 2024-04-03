
$(document).ready(function(){

    $(document).on('click','#signOut',function(e){
        e.preventDefault();
        swal({
            title: 'Anda Yakin ?',
            text: "Anda akan keluar aplikasi",
            icon: 'warning',
            closeOnClickOutside: false,
            buttons: ["Batal", "Ya, Keluar!"]
        }).then((willSave) => {
            if (willSave) {
                location.href= base_url+'auth/logout';
            }
        });
    });

});