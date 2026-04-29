import $ from 'jquery';

/* =====================
   GLOBAL FUNCTION (WAJIB DI LUAR)
===================== */

/* ---------- TOAST ---------- */
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

/* ---------- ERROR HANDLER ---------- */
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
}


/* =====================
   CREATE
===================== */
window.createData = function (formSelector, url, callback = null) {

    $(document).on('submit', formSelector, function (e) {
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
                showToast(res.message ?? "Berhasil ditambahkan");
                form.reset();

                if (callback) callback(res);
            },

            error: handleError,

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

    $(document).on('submit', formSelector, function (e) {
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
                showToast(res.message ?? "Berhasil diupdate");

                if (callback) callback(res);
            },

            error: handleError,

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

    if (!confirm("Yakin ingin hapus data?")) return;

    $.ajax({
        url: url,
        type: "POST",
        data: { _method: "DELETE" },

        success: function (res) {
            showToast(res.message ?? "Berhasil dihapus");

            if (callback) callback(res);
        },

        error: handleError
    });
};


/* =====================
   INIT (HANYA SETUP)
===================== */
$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        }
    });

});