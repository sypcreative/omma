import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { SplitText } from "gsap/SplitText";

gsap.registerPlugin(ScrollTrigger, SplitText);

function splitRevealScroll(el, start = "top 85%") {
  if (!el) return;
  const split = new SplitText(el, { type: "lines,words", linesClass: "split-line-mask" });
  gsap.set(split.lines, {
    overflow: "hidden",
    paddingTop: "0.1em",  marginTop:    "-0.1em",
    paddingBottom: "0.25em", marginBottom: "-0.25em",
  });
  gsap.set(split.words, { yPercent: 130 });
  gsap.set(el, { visibility: "visible" });
  gsap.to(split.words, {
    yPercent: 0, duration: 1.1,
    stagger: { each: 0.04, from: "start" },
    ease: "expo.out",
    scrollTrigger: { trigger: el, start, once: true },
  });
}

function splitRevealTimeline(el, tl, position = 0) {
  if (!el) return;
  const split = new SplitText(el, { type: "lines,words", linesClass: "split-line-mask" });
  gsap.set(split.lines, {
    overflow: "hidden",
    paddingTop: "0.1em",  marginTop:    "-0.1em",
    paddingBottom: "0.25em", marginBottom: "-0.25em",
  });
  gsap.set(split.words, { yPercent: 130 });
  gsap.set(el, { visibility: "visible" });
  tl.to(split.words, {
    yPercent: 0, duration: 1.1,
    stagger: { each: 0.04, from: "start" },
    ease: "expo.out",
  }, position);
}

// ── block-services-intro ──────────────────────────────────────────────────────

function initServicesIntro() {
  const section = document.querySelector(".block-services-intro");
  if (!section) return;

  const title = section.querySelector(".block-services-intro__title");
  const desc  = section.querySelector(".block-services-intro__desc");

  const tl = gsap.timeline({
    scrollTrigger: { trigger: section, start: "top bottom", once: true },
  });

  splitRevealTimeline(title, tl, 0);

  if (desc) {
    gsap.set(desc, { autoAlpha: 0, y: 16 });
    tl.to(desc, { autoAlpha: 1, y: 0, duration: 0.8, ease: "expo.out" }, "-=0.6");
  }
}

// ── block-services-list ───────────────────────────────────────────────────────

function initServicesList() {
  const items = document.querySelectorAll(".block-services-list__item");
  if (!items.length) return;

  items.forEach(item => {
    const line    = item.querySelector(".block-services-list__line");
    const title   = item.querySelector("h2");
    const desc    = item.querySelector(".block-services-list__desc");
    const icon    = item.querySelector(".block-services-list__icon");
    const buttons = item.querySelector(".block-services-list__buttons");

    const tl = gsap.timeline({
      scrollTrigger: { trigger: item, start: "top 85%", once: true },
    });

    // 1. Línea se expande de izquierda a derecha
    if (line) {
      tl.to(line, { scaleX: 1, duration: 0.7, ease: "expo.out" }, 0);
    }

    // 2. Título aparece justo después del inicio de la línea
    splitRevealTimeline(title, tl, line ? 0.1 : 0);

    // 3. Desc e icono entran juntos
    if (desc) {
      gsap.set(desc, { autoAlpha: 0, y: 16 });
      tl.to(desc, { autoAlpha: 1, y: 0, duration: 0.8, ease: "expo.out" }, "-=0.75");
    }
    if (icon) {
      gsap.set(icon, { autoAlpha: 0, x: 24 });
      tl.to(icon, { autoAlpha: 1, x: 0, duration: 0.9, ease: "expo.out" }, "<");
    }

    // 4. Botones entran al final
    if (buttons) {
      gsap.set(buttons, { autoAlpha: 0, y: 12 });
      tl.to(buttons, { autoAlpha: 1, y: 0, duration: 0.7, ease: "expo.out" }, "-=0.4");
    }
  });
}

// ── Init ──────────────────────────────────────────────────────────────────────

export function initBlockServicesAnimations() {
  initServicesIntro();
  initServicesList();
}

document.addEventListener("DOMContentLoaded", () => initBlockServicesAnimations());
