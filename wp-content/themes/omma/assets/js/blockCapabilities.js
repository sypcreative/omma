import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { SplitText } from "gsap/SplitText";

gsap.registerPlugin(ScrollTrigger, SplitText);

export function initBlockCapabilities() {
  const section = document.querySelector(".block-capabilities");
  if (!section) return;

  const title    = section.querySelector(".block-capabilities__title");
  const items    = section.querySelectorAll(".block-capabilities__item");
  const lastLine = section.querySelector(".block-capabilities__item-line--last");

  // ── Título: word-by-word masked reveal ────────────────────────────────────
  if (title) {
    const split = new SplitText(title, { type: "lines,words", linesClass: "split-line-mask" });
    gsap.set(split.lines, {
      overflow:      "hidden",
      paddingTop:    "0.1em",  marginTop:    "-0.1em",
      paddingBottom: "0.25em", marginBottom: "-0.25em",
    });
    gsap.set(split.words, { yPercent: 130 });
    gsap.set(title, { visibility: "visible" });
    gsap.to(split.words, {
      yPercent:      0,
      duration:      1.1,
      stagger:       { each: 0.05, from: "start" },
      ease:          "expo.out",
      scrollTrigger: { trigger: title, start: "top 85%", once: true },
    });
  }

  // ── Cada item: línea dibujada → cuerpo wipe ───────────────────────────────
  items.forEach((item) => {
    const line = item.querySelector(".block-capabilities__item-line");
    const body = item.querySelector(".block-capabilities__item-body");

    if (line) gsap.set(line, { scaleX: 0, transformOrigin: "left center" });
    if (body) gsap.set(body, { clipPath: "inset(0 100% 0 0)", y: 8 });

    const tl = gsap.timeline({
      scrollTrigger: { trigger: item, start: "top 85%", once: true },
    });

    if (line) {
      tl.to(line, {
        scaleX:   1,
        duration: 0.7,
        ease:     "expo.out",
      });
    }

    if (body) {
      tl.to(body, {
        clipPath: "inset(0 0% 0 0)",
        y:        0,
        duration: 0.85,
        ease:     "expo.out",
      }, "-=0.45");
    }
  });

  // ── Línea de cierre ───────────────────────────────────────────────────────
  if (lastLine) {
    gsap.set(lastLine, { scaleX: 0, transformOrigin: "left center" });
    gsap.to(lastLine, {
      scaleX:        1,
      duration:      0.7,
      ease:          "expo.out",
      scrollTrigger: { trigger: lastLine, start: "top 90%", once: true },
    });
  }
}

document.addEventListener("DOMContentLoaded", () => initBlockCapabilities());
