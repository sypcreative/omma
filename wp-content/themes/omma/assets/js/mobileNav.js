export function initMobileNav() {
  const nav = document.querySelector(".mobile-nav[data-navigation-status]");
  if (!nav) return;

  // Stagger delay en los items del menú
  nav.querySelectorAll(".mobile-nav__ul li").forEach((li, i) => {
    li.style.transitionDelay = `${i * 0.05}s`;
  });

  const getStatus = () => nav.getAttribute("data-navigation-status");
  const setStatus = (val) => {
    nav.setAttribute("data-navigation-status", val);
    nav.querySelectorAll("[data-navigation-toggle='toggle']").forEach(btn => {
      btn.setAttribute("aria-expanded", val === "active" ? "true" : "false");
    });
    document.body.style.overflow = val === "active" ? "hidden" : "";
  };

  // Toggle
  nav.querySelectorAll("[data-navigation-toggle='toggle']").forEach(btn => {
    btn.addEventListener("click", () => {
      setStatus(getStatus() === "active" ? "not-active" : "active");
    });
  });

  // Close (overlay click)
  nav.querySelectorAll("[data-navigation-toggle='close']").forEach(btn => {
    btn.addEventListener("click", () => setStatus("not-active"));
  });

  // Cerrar al navegar
  nav.querySelectorAll(".mobile-nav__ul a, .mobile-nav__banner").forEach(link => {
    link.addEventListener("click", () => setStatus("not-active"));
  });

  // ESC
  document.addEventListener("keydown", e => {
    if (e.key === "Escape" && getStatus() === "active") {
      setStatus("not-active");
    }
  });
}

document.addEventListener("DOMContentLoaded", () => initMobileNav());
