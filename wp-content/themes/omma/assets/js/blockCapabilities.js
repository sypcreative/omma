import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

// ── Init ──────────────────────────────────────────────────────────────────────

export function initBlockCapabilities() {
  const section = document.querySelector(".block-capabilities");
  if (!section) return;

  const titleWrap = section.querySelector(".block-capabilities__title-wrap");
  const items     = section.querySelectorAll(".block-capabilities__item");
  const lastLine  = section.querySelector(".block-capabilities__item-line--last");

  // Título: entra desde abajo
  if (titleWrap) {
    gsap.from(titleWrap, {
      y:         48,
      autoAlpha: 0,
      duration:  1,
      ease:      "expo.out",
      scrollTrigger: { trigger: section, start: "top 78%", once: true },
    });
  }

  // Cada item: línea se dibuja → cuerpo se revela con wipe de izquierda a derecha
  items.forEach((item) => {
    const line = item.querySelector(".block-capabilities__item-line");
    const body = item.querySelector(".block-capabilities__item-body");

    const tl = gsap.timeline({
      scrollTrigger: { trigger: item, start: "top 86%", once: true },
    });

    // 1. Línea se dibuja
    if (line) {
      tl.from(line, {
        scaleX:          0,
        transformOrigin: "left center",
        duration:        0.7,
        ease:            "expo.out",
      });
    }

    // 2. Texto entra con clip-path wipe (cortina de izq a der) + leve flotación
    if (body) {
      tl.fromTo(
        body,
        { clipPath: "inset(0 100% 0 0)", y: 10 },
        { clipPath: "inset(0 0% 0 0)",   y: 0, duration: 0.85, ease: "expo.out" },
        "-=0.45",
      );
    }
  });

  // Línea de cierre
  if (lastLine) {
    gsap.from(lastLine, {
      scaleX:          0,
      transformOrigin: "left center",
      duration:        0.7,
      ease:            "expo.out",
      scrollTrigger: { trigger: lastLine, start: "top 92%", once: true },
    });
  }
}

document.addEventListener("DOMContentLoaded", () => initBlockCapabilities());
