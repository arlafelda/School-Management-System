$(document).ready(function () {

    /* =====================
       CSRF SETUP
    ===================== */
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        }
    });


    /* =====================
       TOAST NOTIFICATION
    ===================== */
    function showToast(message, type = "success") {

        let bg = type === "success" ? "bg-green-500" : "bg-red-500";

        let toast = $(`
            <div class="fixed top-5 right-5 ${bg} text-white px-4 py-2 rounded shadow z-50">
                ${message}
            </div>
        `);

        $("body").append(toast);

        setTimeout(() => {
            toast.fadeOut(300, function () {
                $(this).remove();
            });
        }, 2500);
    }


    /* =====================
       HANDLE ERROR LARAVEL
    ===================== */
    function handleError(xhr) {

        if (xhr.status === 422) {
            let errors = xhr.responseJSON.errors;
            let message = "";

            Object.values(errors).forEach(err => {
                message += err[0] + "\n";
            });

            showToast(message, "error");
            return;
        }

        showToast("Terjadi kesalahan server", "error");
        console.log(xhr.responseText);
    }


    /* =====================
       CREATE
    ===================== */
    window.createData = function (formSelector, url, callback = null) {

        $(formSelector).off('submit').on('submit', function (e) {
            e.preventDefault();

            let form = this;
            let formData = new FormData(form);

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,

                beforeSend: function () {
                    $(form).find("button[type=submit]").prop("disabled", true);
                },

                success: function (res) {

                    showToast(res.message ?? "Data berhasil ditambahkan", "success");

                    form.reset();

                    if (callback) callback(res);
                    if (typeof loadData === "function") loadData();
                },

                error: function (xhr) {
                    handleError(xhr);
                },

                complete: function () {
                    $(form).find("button[type=submit]").prop("disabled", false);
                }
            });
        });
    };


    /* =====================
       UPDATE
    ===================== */
    window.updateData = function (formSelector, url, callback = null) {

        $(formSelector).off('submit').on('submit', function (e) {
            e.preventDefault();

            let form = this;
            let formData = new FormData(form);
            formData.append("_method", "PUT");

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,

                beforeSend: function () {
                    $(form).find("button[type=submit]").prop("disabled", true);
                },

                success: function (res) {
                    showToast(res.message ?? "Data berhasil diupdate", "success");
                    if (callback) callback(res);
                },

                error: function (xhr) {
                    handleError(xhr);
                },

                complete: function () {
                    $(form).find("button[type=submit]").prop("disabled", false);
                }
            });
        });
    };


    /* =====================
       DELETE
    ===================== */
    window.deleteData = function (url, callback = null) {

        if (!confirm("Yakin ingin menghapus data?")) return;

        $.ajax({
            url: url,
            type: "POST",
            data: {
                _method: "DELETE"
            },

            success: function (res) {
                showToast(res.message ?? "Data berhasil dihapus", "success");
                if (callback) callback(res);
            },

            error: function (xhr) {
                handleError(xhr);
            }
        });
    };


    /* =====================
       GET DATA
    ===================== */
    window.getData = function (url, callback) {

        $.ajax({
            url: url,
            type: "GET",

            success: function (res) {
                callback(res);
            },

            error: function (xhr) {
                handleError(xhr);
            }
        });
    };

});