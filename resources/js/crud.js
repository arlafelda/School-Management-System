// ================= CREATE =================
function initCreate(form, url) {
    createData(form, url, function () {
        location.reload();
    });
}

// ================= EDIT =================
function initEdit(form, url) {
    updateData(form, url, function () {
        location.reload();
    });
}

// ================= DELETE =================
function initDelete(buttonId, urlBuilder) {
    document.getElementById(buttonId).addEventListener('click', function () {

        let id = window.deleteId;

        deleteData(urlBuilder(id), function () {
            location.reload();
        });

    });
}

// ================= SET EDIT DATA =================
function setEditData(data) {
    document.getElementById('editId').value = data.id;
    document.getElementById('editName').value = data.name;
}