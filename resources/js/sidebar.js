// resources/js/sidebar.js

window.toggleSidebar = function () {
    document.getElementById('sidebar').classList.toggle('-translate-x-full');
}

window.toggleMenu = function (menuId, arrowId) {

    const menus = ['userMenu','masterMenu','akademikMenu','systemMenu'];
    const arrows = ['arrowUser','arrowMaster','arrowAkademik','arrowSystem'];

    menus.forEach(id => {
        if (id !== menuId) document.getElementById(id).classList.add('hidden');
    });

    arrows.forEach(id => {
        if (id !== arrowId) document.getElementById(id).innerHTML = '▼';
    });

    const menu = document.getElementById(menuId);
    const arrow = document.getElementById(arrowId);

    menu.classList.toggle('hidden');
    arrow.innerHTML = menu.classList.contains('hidden') ? '▼' : '▲';
}

window.toggleProfile = function () {
    document.getElementById('profileMenu').classList.toggle('hidden');
}

document.addEventListener("click", function(e) {
    const profile = document.getElementById("profileMenu");

    if (!e.target.closest("#profileMenu") && !e.target.closest("button")) {
        profile.classList.add("hidden");
    }
});