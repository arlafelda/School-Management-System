import $ from 'jquery';

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
window.createData = function (formSelector, url, options = {}) {

    $(document).off('submit', formSelector).on('submit', formSelector, function (e) {
        e.preventDefault();

        let form = this;
        let formData = new FormData(form);

        window.dispatchEvent(new CustomEvent('modal-loading', { detail: true }));

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

                window.dispatchEvent(new CustomEvent('close-modal', {
                    detail: form.dataset.modal
                }));

                if (options.onSuccess) options.onSuccess(res);
            },

            error: function (xhr) {
                handleError(xhr);
            },

            complete: function () {
                $(form).find("button[type=submit]").prop("disabled", false);

                window.dispatchEvent(new CustomEvent('modal-loading', { detail: false }));
            }
        });
    });
};

/* =====================
   UPDATE
===================== */
window.updateData = function (formSelector, url, options = {}) {

    $(document).off('submit', formSelector).on('submit', formSelector, function (e) {
        e.preventDefault();

        let form = this;
        let formData = new FormData(form);
        formData.append("_method", "PUT");

        window.dispatchEvent(new CustomEvent('modal-loading', { detail: true }));

        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,

            success: function (res) {

                showToast(res.message ?? "Berhasil diupdate");

                window.dispatchEvent(new CustomEvent('close-modal', {
                    detail: form.dataset.modal
                }));

                if (options.onSuccess) options.onSuccess(res);
            },

            error: function (xhr) {
                handleError(xhr);
            },

            complete: function () {
                window.dispatchEvent(new CustomEvent('modal-loading', { detail: false }));
            }
        });
    });
};

/* =====================
   DELETE
===================== */
window.deleteData = function (url, options = {}) {

    if (!confirm("Yakin ingin hapus data?")) return;

    $.ajax({
        url: url,
        type: "POST",
        data: { _method: "DELETE" },

        success: function (res) {

            showToast(res.message ?? "Berhasil dihapus");

            if (options.onSuccess) options.onSuccess(res);
        },

        error: function (xhr) {
            handleError(xhr);
        }
    });
};

/* =====================
   GLOBAL SETUP
===================== */
$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        }
    });

});