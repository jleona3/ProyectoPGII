//(document.querySelectorAll("[toast-list]")||document.querySelectorAll("[data-choices]")||document.querySelectorAll("[data-provider]"))&&(document.writeln("<script type='text/javascript' src='https://cdn.jsdelivr.net/npm/toastify-js'><\/script>"),document.writeln("<script type='text/javascript' src='assets/libs/choices.js/public/assets/scripts/choices.min.js'><\/script>"),document.writeln("<script type='text/javascript' src='assets/libs/flatpickr/flatpickr.min.js'><\/script>"));

// plugins.js - versión segura (sin document.write)

// Cargar dinámicamente plugins sólo si son necesarios
document.addEventListener("DOMContentLoaded", function () {

    // === Toastify ===
    if (document.querySelector("[toast-list]")) {
        let toastifyScript = document.createElement("script");
        toastifyScript.src = "https://cdn.jsdelivr.net/npm/toastify-js";
        toastifyScript.async = true;
        document.head.appendChild(toastifyScript);

        let toastifyCss = document.createElement("link");
        toastifyCss.rel = "stylesheet";
        toastifyCss.href = "https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css";
        document.head.appendChild(toastifyCss);
    }

    // === Choices.js ===
    if (document.querySelector("[data-choices]")) {
        let choicesScript = document.createElement("script");
        choicesScript.src = "../../assets/libs/choices.js/public/assets/scripts/choices.min.js";
        choicesScript.async = true;
        document.head.appendChild(choicesScript);
    }

    // === Flatpickr ===
    if (document.querySelector("[data-provider]")) {
        let flatpickrScript = document.createElement("script");
        flatpickrScript.src = "../../assets/libs/flatpickr/flatpickr.min.js";
        flatpickrScript.async = true;
        document.head.appendChild(flatpickrScript);
    }

    //console.log("Plugins cargados dinámicamente");
});
