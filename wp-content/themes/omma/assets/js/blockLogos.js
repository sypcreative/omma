import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

// ── Config ────────────────────────────────────────────────────────────────────

const INTERVAL   = 2800;  // ms entre cambios
const STAGGER_MS = 700;   // desfase entre slots
const DUR_OUT    = 0.5;   // s salida
const DUR_IN     = 0.6;   // s entrada

// ── Ciclo de logos dentro de una card ────────────────────────────────────────

function initSlot(card, slotDelay) {
  const logos = Array.from(card.querySelectorAll(".block-logos__logo"));
  if (!logos.length) return;

  // Estado inicial: todos ocultos, primero visible
  gsap.set(logos, { autoAlpha: 0, y: 0 });
  gsap.set(logos[0], { autoAlpha: 1 });

  let idx = 0;

  function cycle() {
    if (logos.length < 2) return;

    const prev = logos[idx];
    idx = (idx + 1) % logos.length;
    const next = logos[idx];

    const tl = gsap.timeline();

    // Salida: fade out con leve subida
    tl.to(prev, {
      autoAlpha: 0,
      y:         -8,
      duration:  DUR_OUT,
      ease:      "power2.inOut",
    });

    // Entrada: fade in desde leve posición inferior
    tl.fromTo(
      next,
      { autoAlpha: 0, y: 8 },
      { autoAlpha: 1, y: 0, duration: DUR_IN, ease: "power2.out" },
      "-=0.25",
    );

    // Reset posición del logo que salió
    tl.set(prev, { y: 0 });
  }

  // Primer ciclo con delay de slot; luego intervalo regular
  const tid = setTimeout(() => {
    cycle();
    card._intervalId = setInterval(cycle, INTERVAL);
  }, slotDelay);

  card._timeoutId = tid;
}

// ── Animación de entrada del bloque ──────────────────────────────────────────

function revealSection(section) {
  const cards = section.querySelectorAll("[data-logos-slot]");
  const cta   = section.querySelector(".block-logos__cta");

  // Cards: se revelan con clip + slide desde abajo, en cascada
  gsap.from(cards, {
    y:           48,
    autoAlpha:   0,
    scale:       0.96,
    duration:    0.8,
    stagger:     { each: 0.14, ease: "power2.out" },
    ease:        "expo.out",
    scrollTrigger: { trigger: section, start: "top 80%", once: true },
  });

  // CTA: entra desde la derecha con ligero delay
  if (cta) {
    gsap.from(cta, {
      x:         20,
      autoAlpha: 0,
      duration:  0.6,
      ease:      "expo.out",
      delay:     0.4,
      scrollTrigger: { trigger: section, start: "top 80%", once: true },
    });
  }
}

// ── Init ──────────────────────────────────────────────────────────────────────

export function initBlockLogos() {
  const section = document.querySelector(".block-logos");
  if (!section) return;

  revealSection(section);

  section.querySelectorAll("[data-logos-slot]").forEach((card, i) => {
    initSlot(card, i * STAGGER_MS);
  });

  return () => {
    section.querySelectorAll("[data-logos-slot]").forEach((card) => {
      clearTimeout(card._timeoutId);
      clearInterval(card._intervalId);
    });
  };
}

document.addEventListener("DOMContentLoaded", () => initBlockLogos());
