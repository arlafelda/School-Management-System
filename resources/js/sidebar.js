window.toggleSidebar = function () {
    const sidebar = document.getElementById('sidebar');
    if (!sidebar) return;

    sidebar.classList.toggle('-translate-x-full');
}

window.toggleMenu = function (menuId, arrowId) {

    const menus = ['userMenu','masterMenu','akademikMenu','systemMenu'];
    const arrows = ['arrowUser','arrowMaster','arrowAkademik','arrowSystem'];

    // ✅ CLOSE MENU LAIN (SAFE CHECK)
    menus.forEach(id => {
        const el = document.getElementById(id);
        if (id !== menuId && el) {
            el.classList.add('hidden');
        }
    });

    // ✅ RESET ARROW LAIN (SAFE CHECK)
    arrows.forEach(id => {
        const el = document.getElementById(id);
        if (id !== arrowId && el) {
            el.innerHTML = '▼';
        }
    });

    // ✅ TOGGLE TARGET MENU
    const menu = document.getElementById(menuId);
    const arrow = document.getElementById(arrowId);

    if (!menu || !arrow) return;

    menu.classList.toggle('hidden');
    arrow.innerHTML = menu.classList.contains('hidden') ? '▼' : '▲';
}

window.toggleProfile = function () {
    const profile = document.getElementById('profileMenu');
    if (!profile) return;

    profile.classList.toggle('hidden');
}

document.addEventListener("click", function(e) {
    const profile = document.getElementById("profileMenu");

    if (!profile) return;

    if (!e.target.closest("#profileMenu") && !e.target.closest("button")) {
        profile.classList.add("hidden");
    }
});