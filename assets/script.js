(function(){
  const list = document.getElementById('challenge-list');
  if (!list) return;

  const catSel = document.getElementById('filter-category');
  const lvlSel = document.getElementById('filter-difficulty');
  const search = document.getElementById('search');

  function applyFilters(){
    const cat = (catSel?.value || "").toLowerCase();
    const lvl = (lvlSel?.value || "").toLowerCase();
    const q   = (search?.value || "").trim().toLowerCase();

    [...list.querySelectorAll('.card')].forEach(card=>{
      const c = (card.dataset.category||"").toLowerCase();
      const d = (card.dataset.difficulty||"").toLowerCase();
      const t = (card.dataset.title||"").toLowerCase();
      const ok =
        (!cat || c===cat) &&
        (!lvl || d===lvl) &&
        (!q   || t.includes(q));
      card.style.display = ok ? "" : "none";
    });
  }

  catSel?.addEventListener('change', applyFilters);
  lvlSel?.addEventListener('change', applyFilters);
  search?.addEventListener('input', applyFilters);
})();
