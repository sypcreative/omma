import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { SplitText } from "gsap/SplitText";

gsap.registerPlugin(ScrollTrigger, SplitText);

export function initBlockHeroHome() {
  const hero = document.querySelector(".block-hero-home");
  if (!hero) return;

  const bg       = hero.querySelector(".block-hero-home__bg");
  const title    = hero.querySelector(".block-hero-home__title");
  const subtitle = hero.querySelector(".block-hero-home__subtitle");
  const actions  = hero.querySelector(".block-hero-home__actions");

  // ── Estado inicial explícito (antes de cualquier tween) ───────────────────
  if (subtitle) gsap.set(subtitle, { autoAlpha: 0, y: 24, filter: "blur(8px)" });
  if (actions)  gsap.set(Array.from(actions.children), { autoAlpha: 0, y: 20 });

  // ── Title: word-by-word masked reveal ─────────────────────────────────────
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
      stagger:  { each: 0.05, from: "start" },
      ease:     "expo.out",
      delay:    0.1,
    });
  }

  // ── Subtitle: blur + fade up ──────────────────────────────────────────────
  if (subtitle) {
    gsap.to(subtitle, {
      autoAlpha:  1,
      y:          0,
      filter:     "blur(0px)",
      duration:   1.0,
      ease:       "expo.out",
      delay:      0.55,
      clearProps: "filter",
    });
  }

  // ── Buttons: stagger up ───────────────────────────────────────────────────
  if (actions) {
    gsap.to(Array.from(actions.children), {
      autoAlpha: 1,
      y:         0,
      duration:  0.8,
      stagger:   0.12,
      ease:      "expo.out",
      delay:     0.8,
    });
  }

  // ── Scroll: background parallax ───────────────────────────────────────────
  if (bg) {
    gsap.fromTo(bg,
      { scale: 1 },
      {
        scale:         1.1,
        ease:          "none",
        scrollTrigger: { trigger: hero, start: "top top", end: "bottom top", scrub: true },
      }
    );
  }
}

document.addEventListener("DOMContentLoaded", () => initBlockHeroHome());
