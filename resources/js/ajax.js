import $ from 'jquery';

/* =====================
   TOAST
===================== */
function showToast(message, type = "success") {
    const bg = type === "success"
        ? "bg-green-500"
        : "bg-red-500";

    const toast = $(`
        <div class="
            fixed top-5 right-5
            ${bg}
            text-white
            px-4 py-3
            rounded-lg
            shadow-lg
            z-[9999]
            whitespace-pre-line
        ">
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
   LOADING
===================== */
function setLoading(status) {
    window.dispatchEvent(
        new CustomEvent("modal-loading", {
            detail: status
        })
    );
}


/* =====================
   ERROR HANDLER
===================== */
function handleError(xhr) {

    if (xhr.status === 422) {
        let errors = xhr.responseJSON?.errors;
        let message = "";

        if (errors) {
            Object.values(errors).forEach(err => {
                message += `${err[0]}\n`;
            });
        } else {
            message =
                xhr.responseJSON?.message
                || "Validasi gagal";
        }

        return showToast(message, "error");
    }

    if (xhr.status === 401)
        return showToast("Session login habis", "error");

    if (xhr.status === 403)
        return showToast("Akses ditolak", "error");

    if (xhr.status === 404)
        return showToast("Data tidak ditemukan", "error");

    if (xhr.status === 500)
        return showToast("Internal server error", "error");

    showToast("Terjadi kesalahan", "error");
}


/* =====================
   CREATE
===================== */
window.createData = function (
    formSelector,
    url,
    options = {}
) {
    $(document)
        .off("submit", formSelector)
        .on("submit", formSelector, function (e) {

            e.preventDefault();

            const form = this;
            const submitBtn =
                $(form).find("button[type=submit]");
            const formData =
                new FormData(form);

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,

                beforeSend() {
                    submitBtn.prop(
                        "disabled",
                        true
                    );
                    setLoading(true);
                },

                success(res) {
                    showToast(
                        res.message ||
                        "Data berhasil ditambahkan"
                    );

                    form.reset();

                    if (options.onSuccess) {
                        options.onSuccess(res);
                    }
                },

                error: handleError,

                complete() {
                    submitBtn.prop(
                        "disabled",
                        false
                    );
                    setLoading(false);
                }
            });
        });
};


/* =====================
   UPDATE
===================== */
window.updateData = function (
    formSelector,
    url,
    options = {}
) {
    $(document)
        .off("submit", formSelector)
        .on("submit", formSelector, function (e) {

            e.preventDefault();

            const form = this;
            const submitBtn =
                $(form).find("button[type=submit]");
            const formData =
                new FormData(form);

            // wajib untuk Laravel PUT
            formData.append(
                "_method",
                "PUT"
            );

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,

                beforeSend() {
                    submitBtn.prop(
                        "disabled",
                        true
                    );
                    setLoading(true);
                },

                success(res) {
                    showToast(
                        res.message ||
                        "Data berhasil diupdate"
                    );

                    if (options.onSuccess) {
                        options.onSuccess(res);
                    }
                },

                error(xhr) {
                    handleError(xhr);

                    if (options.onError) {
                        options.onError(
                            xhr.responseJSON
                        );
                    }
                },

                complete() {
                    submitBtn.prop(
                        "disabled",
                        false
                    );
                    setLoading(false);
                }
            });
        });
};


/* =====================
   DELETE
===================== */
window.deleteData = function (
    url,
    message = "Yakin ingin menghapus data?",
    options = {}
) {
    if (!confirm(message)) return;

    $.ajax({
        url: url,
        type: "POST",
        data: {
            _method: "DELETE"
        },

        beforeSend() {
            setLoading(true);
        },

        success(res) {
            showToast(
                res.message || "Berhasil"
            );

            if (options.onSuccess) {
                options.onSuccess(res);
            }
        },

        error: handleError,

        complete() {
            setLoading(false);
        }
    });
};


/* =====================
   RESTORE
===================== */
window.restoreData = function (
    url,
    message = "Yakin ingin restore data?",
    options = {}
) {
    if (!confirm(message)) return;

    $.ajax({
        url: url,
        type: "POST",
        data: {
            _method: "PUT"
        },

        beforeSend() {
            setLoading(true);
        },

        success(res) {
            showToast(
                res.message ||
                "Berhasil direstore"
            );

            if (options.onSuccess) {
                options.onSuccess(res);
            }
        },

        error: handleError,

        complete() {
            setLoading(false);
        }
    });
};


/* =====================
   GLOBAL AJAX
===================== */
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN":
                $('meta[name="csrf-token"]')
                    .attr("content"),

            "Accept":
                "application/json"
        }
    });
});