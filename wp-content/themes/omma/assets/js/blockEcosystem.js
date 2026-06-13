import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

// ── Reveal del bloque completo ────────────────────────────────────────────────

function revealSection(section) {
  const title    = section.querySelector(".block-ecosystem__title");
  const subtitle = section.querySelector(".block-ecosystem__subtitle");
  const cards    = section.querySelectorAll(".block-ecosystem__card");
  const cta      = section.querySelector(".block-ecosystem__cta");

  const tl = gsap.timeline({
    scrollTrigger: {
      trigger: section,
      start:   "top 78%",
      once:    true,
    },
  });

  if (title) {
    tl.from(title, {
      y:         36,
      autoAlpha: 0,
      duration:  0.8,
      ease:      "expo.out",
    });
  }

  if (subtitle) {
    tl.from(subtitle, {
      y:         20,
      autoAlpha: 0,
      duration:  0.7,
      ease:      "expo.out",
    }, "-=0.55");
  }

  if (cards.length) {
    tl.from(cards, {
      y:         44,
      autoAlpha: 0,
      scale:     0.97,
      duration:  0.75,
      stagger:   { each: 0.09, ease: "power2.out" },
      ease:      "expo.out",
    }, "-=0.4");
  }

  if (cta) {
    tl.from(cta, {
      y:         16,
      autoAlpha: 0,
      duration:  0.55,
      ease:      "expo.out",
    }, "-=0.25");
  }
}

// ── Init ──────────────────────────────────────────────────────────────────────

export function initBlockEcosystem() {
  const section = document.querySelector(".block-ecosystem");
  if (!section) return;

  revealSection(section);
}

document.addEventListener("DOMContentLoaded", () => initBlockEcosystem());
