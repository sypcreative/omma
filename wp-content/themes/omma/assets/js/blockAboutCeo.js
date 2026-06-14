import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { SplitText } from "gsap/SplitText";

gsap.registerPlugin(ScrollTrigger, SplitText);

export function initBlockAboutCeo() {
  const section = document.querySelector(".block-about-ceo");
  if (!section) return;

  const title   = section.querySelector("h2");
  const eyebrow = section.querySelector(".block-about-ceo__eyebrow");
  const body    = section.querySelector(".block-about-ceo__body");
  const figure  = section.querySelector(".block-about-ceo__figure");

  if (eyebrow) gsap.set(eyebrow, { autoAlpha: 0, y: 14 });
  if (figure)  gsap.set(figure,  { autoAlpha: 0, scale: 0.95 });

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

  // Body: trigger individual por párrafo
  if (body) {
    const paras   = body.querySelectorAll("p");
    const targets = paras.length ? Array.from(paras) : [body];
    targets.forEach(el => {
      gsap.set(el, { autoAlpha: 0, y: 20, filter: "blur(6px)" });
      gsap.to(el, {
        autoAlpha:  1,
        y:          0,
        filter:     "blur(0px)",
        duration:   0.9,
        ease:       "expo.out",
        clearProps: "filter",
        scrollTrigger: { trigger: el, start: "top 88%", once: true },
      });
    });
  }

  // Figure: su propio trigger
  if (figure) {
    gsap.to(figure, {
      autoAlpha: 1,
      scale:     1,
      duration:  1.2,
      ease:      "expo.out",
      scrollTrigger: { trigger: figure, start: "top 85%", once: true },
    });
  }
}

document.addEventListener("DOMContentLoaded", () => initBlockAboutCeo());
