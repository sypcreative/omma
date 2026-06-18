import { gsap } from "gsap";

export function initBlockNewsGrid() {
  const filter = document.querySelector(".block-news-filter");
  if (!filter) return;

  const buttons = filter.querySelectorAll(".block-news-filter__btn");
  const items   = document.querySelectorAll(".block-news-grid__item[data-filter-type]");

  function applyFilter(type) {
    buttons.forEach(btn => {
      const isActive = btn.dataset.filter === type;
      btn.setAttribute("aria-selected", isActive ? "true" : "false");
      btn.classList.toggle("btn-omma-light",       isActive);
      btn.classList.toggle("btn-omma-outline-dark", !isActive);
    });

    const outgoing = [...items].filter(el => el.style.display !== "none" && el.dataset.filterType !== type);
    const incoming = [...items].filter(el => el.dataset.filterType === type);

    gsap.to(outgoing, {
      autoAlpha: 0,
      y: -8,
      duration: 0.2,
      ease: "power2.in",
      onComplete: () => {
        outgoing.forEach(el => { el.style.display = "none"; });
        gsap.set(incoming, { display: "", autoAlpha: 0, y: 12 });
        gsap.to(incoming, {
          autoAlpha: 1,
          y: 0,
          duration: 0.35,
          ease: "power2.out",
          stagger: 0.05,
        });
      },
    });
  }

  // Estado inicial sin animación — tipo por defecto viene del PHP
  const grid        = document.querySelector("[data-news-default-type]");
  const defaultType = grid?.dataset.newsDefaultType ?? "news";
  items.forEach(el => {
    el.style.display = el.dataset.filterType === defaultType ? "" : "none";
  });

  buttons.forEach(btn => {
    btn.addEventListener("click", () => applyFilter(btn.dataset.filter));
  });
}

document.addEventListener("DOMContentLoaded", () => initBlockNewsGrid());
