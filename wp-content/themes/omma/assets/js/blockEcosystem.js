import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { SplitText } from "gsap/SplitText";

gsap.registerPlugin(ScrollTrigger, SplitText);

function revealSection(section) {
  const title    = section.querySelector(".block-ecosystem__title");
  const subtitle = section.querySelector(".block-ecosystem__subtitle");
  const cards    = section.querySelectorAll(".block-ecosystem__card");
  const cta      = section.querySelector(".block-ecosystem__cta");

  if (subtitle)     gsap.set(subtitle, { autoAlpha: 0, y: 18, filter: "blur(6px)" });
  if (cards.length) gsap.set(cards,    { scale: 0.88, autoAlpha: 0 });
  if (cta)          gsap.set(cta,      { x: 28, autoAlpha: 0 });

  // Título: SplitText con su propio trigger
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
      yPercent: 0,
      duration: 1.1,
      stagger:  { each: 0.04, from: "start" },
      ease:     "expo.out",
      scrollTrigger: { trigger: title, start: "top 85%", once: true },
    });
  }

  // Subtítulo: su propio trigger
  if (subtitle) {
    gsap.to(subtitle, {
      autoAlpha:  1,
      y:          0,
      filter:     "blur(0px)",
      duration:   0.9,
      ease:       "expo.out",
      clearProps: "filter",
      scrollTrigger: { trigger: subtitle, start: "top 85%", once: true },
    });
  }

  // Cards: trigger individual por card
  cards.forEach(card => {
    gsap.to(card, {
      scale:     1,
      autoAlpha: 1,
      duration:  0.9,
      ease:      "expo.out",
      scrollTrigger: { trigger: card, start: "top 85%", once: true },
    });
  });

  // CTA: su propio trigger
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

export function initBlockEcosystem() {
  const section = document.querySelector(".block-ecosystem");
  if (!section) return;
  revealSection(section);
}

document.addEventListener("DOMContentLoaded", () => initBlockEcosystem());
