function toggleFiltres() {
  document.querySelector("#filtres")?.classList.toggle("active");
  document
    .querySelector("#layer-background-filtres")
    ?.classList.toggle("active");
}
window.toggleFiltres = toggleFiltres;
