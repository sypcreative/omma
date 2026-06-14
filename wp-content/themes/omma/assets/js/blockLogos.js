import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

// ── Config ────────────────────────────────────────────────────────────────────

const INTERVAL   = 2800;
const STAGGER_MS = 700;
const DUR_OUT    = 0.5;
const DUR_IN     = 0.6;

// ── Ciclo de logos dentro de una card ────────────────────────────────────────

function initSlot(card, slotDelay) {
  const logos = Array.from(card.querySelectorAll(".block-logos__logo"));
  if (!logos.length) return;

  gsap.set(logos, { autoAlpha: 0, y: 0 });
  gsap.set(logos[0], { autoAlpha: 1 });

  let idx = 0;

  function cycle() {
    if (logos.length < 2) return;

    const prev = logos[idx];
    idx = (idx + 1) % logos.length;
    const next = logos[idx];

    const tl = gsap.timeline();

    tl.to(prev, {
      autoAlpha: 0,
      y:         -8,
      duration:  DUR_OUT,
      ease:      "power2.inOut",
    });

    tl.fromTo(
      next,
      { autoAlpha: 0, y: 8 },
      { autoAlpha: 1, y: 0, duration: DUR_IN, ease: "power2.out" },
      "-=0.25",
    );

    tl.set(prev, { y: 0 });
  }

  const tid = setTimeout(() => {
    cycle();
    card._intervalId = setInterval(cycle, INTERVAL);
  }, slotDelay);

  card._timeoutId = tid;
}

// ── Animación de entrada: trigger individual por elemento ─────────────────────

function revealSection(section) {
  const cards = section.querySelectorAll("[data-logos-slot]");
  const cta   = section.querySelector(".block-logos__cta");

  if (cards.length) gsap.set(cards, { scale: 0.88, autoAlpha: 0 });
  if (cta)          gsap.set(cta,   { x: 24, autoAlpha: 0 });

  cards.forEach(card => {
    gsap.to(card, {
      scale:     1,
      autoAlpha: 1,
      duration:  0.9,
      ease:      "expo.out",
      scrollTrigger: { trigger: card, start: "top 85%", once: true },
    });
  });

  if (cta) {
    gsap.to(cta, {
      x:         0,
      autoAlpha: 1,
      duration:  0.8,
      ease:      "expo.out",
      scrollTrigger: { trigger: cta, start: "top 88%", once: true },
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
