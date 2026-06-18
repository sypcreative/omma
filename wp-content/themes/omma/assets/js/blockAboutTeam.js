import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { SplitText } from "gsap/SplitText";

gsap.registerPlugin(ScrollTrigger, SplitText);

function initSection(section) {
  const title = section.querySelector("h2");
  const cards = section.querySelectorAll(".block-about-team__card");

  if (cards.length) gsap.set(cards, { scale: 0.88, autoAlpha: 0 });

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

  cards.forEach(card => {
    gsap.to(card, {
      scale:     1,
      autoAlpha: 1,
      duration:  0.9,
      ease:      "expo.out",
      scrollTrigger: { trigger: card, start: "top 88%", once: true },
    });
  });
}

export function initBlockAboutTeam() {
  const sections = document.querySelectorAll(".block-about-team");
  sections.forEach(initSection);
}

document.addEventListener("DOMContentLoaded", () => initBlockAboutTeam());
