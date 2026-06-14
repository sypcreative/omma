import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { SplitText } from "gsap/SplitText";

gsap.registerPlugin(ScrollTrigger, SplitText);

export function initBlockAboutIntro() {
  const section = document.querySelector(".block-about-intro");
  if (!section) return;

  const title   = section.querySelector("h2");
  const eyebrow = section.querySelector(".block-about-intro__eyebrow");
  const logo    = section.querySelector(".block-about-intro__logo-card");
  const body    = section.querySelector(".block-about-intro__body");

  if (eyebrow) gsap.set(eyebrow, { autoAlpha: 0, y: 14 });
  if (logo)    gsap.set(logo,    { scale: 0.88, autoAlpha: 0 });

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

  // Eyebrow: su propio trigger
  if (eyebrow) {
    gsap.to(eyebrow, {
      autoAlpha: 1,
      y:         0,
      duration:  0.8,
      ease:      "expo.out",
      scrollTrigger: { trigger: eyebrow, start: "top 88%", once: true },
    });
  }

  // Logo card: su propio trigger
  if (logo) {
    gsap.to(logo, {
      scale:     1,
      autoAlpha: 1,
      duration:  0.9,
      ease:      "expo.out",
      scrollTrigger: { trigger: logo, start: "top 88%", once: true },
    });
  }

  // Body: trigger individual por párrafo
  if (body) {
    const paras   = body.querySelectorAll("p");
    const targets = paras.length ? Array.from(paras) : [body];
    targets.forEach(el => {
      gsap.set(el, { autoAlpha: 0, y: 20 });
      gsap.to(el, {
        autoAlpha: 1,
        y:         0,
        duration:  0.9,
        ease:      "expo.out",
        scrollTrigger: { trigger: el, start: "top 88%", once: true },
      });
    });
  }
}

document.addEventListener("DOMContentLoaded", () => initBlockAboutIntro());
