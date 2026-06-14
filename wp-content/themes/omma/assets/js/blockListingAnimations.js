import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { SplitText } from "gsap/SplitText";

gsap.registerPlugin(ScrollTrigger, SplitText);

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

function initPageHeader(sectionClass, descClass) {
  const section = document.querySelector(sectionClass);
  if (!section) return;

  const title = section.querySelector("h1");
  const desc  = descClass ? section.querySelector(descClass) : null;

  const tl = gsap.timeline({
    scrollTrigger: { trigger: section, start: "top bottom", once: true },
  });

  splitRevealTimeline(title, tl, 0);

  if (desc) {
    gsap.set(desc, { autoAlpha: 0, y: 16 });
    tl.to(desc, { autoAlpha: 1, y: 0, duration: 0.8, ease: "expo.out" }, "-=0.6");
  }
}

function initGrid(cardSelector) {
  const cards = document.querySelectorAll(cardSelector);
  if (!cards.length) return;

  cards.forEach((card, i) => {
    gsap.set(card, { autoAlpha: 0, y: 24 });
    gsap.to(card, {
      autoAlpha: 1, y: 0, duration: 0.8, ease: "expo.out",
      delay: (i % 2) * 0.12,
      scrollTrigger: { trigger: card, start: "top 88%", once: true },
    });
  });
}

// ── Init ──────────────────────────────────────────────────────────────────────

export function initBlockListingAnimations() {
  initPageHeader(".block-cs-page-header",   ".block-cs-page-header__desc");
  initGrid(".block-cs-grid__card");

  initPageHeader(".block-news-page-header", ".block-news-page-header__desc");
  initGrid(".block-news-grid__card");
}

document.addEventListener("DOMContentLoaded", () => initBlockListingAnimations());
