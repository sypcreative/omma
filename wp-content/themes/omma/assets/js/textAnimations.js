import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { SplitText } from "gsap/SplitText";

gsap.registerPlugin(ScrollTrigger, SplitText);

// ── Defaults ──────────────────────────────────────────────────────────────────

const DEFAULTS = {
  duration: 0.9,
  stagger:  0.06,
  ease:     "power3.out",
  start:    "top 88%",
  delay:    0,
};

// ── Helpers ───────────────────────────────────────────────────────────────────

function opts(el) {
  return {
    duration: parseFloat(el.dataset.animDuration ?? DEFAULTS.duration),
    stagger:  parseFloat(el.dataset.animStagger  ?? DEFAULTS.stagger),
    ease:              el.dataset.animEase        ?? DEFAULTS.ease,
    start:             el.dataset.animStart       ?? DEFAULTS.start,
    delay:    parseFloat(el.dataset.animDelay     ?? DEFAULTS.delay),
  };
}

function scrollTrigger(el, start) {
  return { trigger: el, start, once: true };
}

// Wraps each SplitText line in an overflow:hidden span so lines slide up
// from behind a mask instead of fading in from open space.
function maskLines(lines) {
  lines.forEach(line => {
    const mask = document.createElement("span");
    mask.style.cssText = "display:block; overflow:hidden; padding-top:0.2em; margin-top:-0.2em; padding-bottom:0.1em;";
    line.parentNode.insertBefore(mask, line);
    mask.appendChild(line);
  });
}

// ── Animation types ───────────────────────────────────────────────────────────

// data-anim="lines"
// Each line slides up from behind an overflow mask.
function animLines(el, o) {
  const split = new SplitText(el, { type: "lines", linesClass: "split-line" });
  maskLines(split.lines);

  gsap.from(split.lines, {
    yPercent:      110,
    duration:      o.duration,
    stagger:       o.stagger,
    ease:          o.ease,
    delay:         o.delay,
    scrollTrigger: scrollTrigger(el, o.start),
  });
}

// data-anim="words"
// Each word fades and slides up with stagger.
function animWords(el, o) {
  const split = new SplitText(el, { type: "words", wordsClass: "split-word" });

  gsap.from(split.words, {
    y:             24,
    autoAlpha:     0,
    duration:      o.duration,
    stagger:       o.stagger,
    ease:          o.ease,
    delay:         o.delay,
    scrollTrigger: scrollTrigger(el, o.start),
  });
}

// data-anim="chars"
// Each character fades and slides up with a tight stagger.
function animChars(el, o) {
  const split = new SplitText(el, { type: "chars", charsClass: "split-char" });

  gsap.from(split.chars, {
    y:             16,
    autoAlpha:     0,
    duration:      o.duration * 0.7,
    stagger:       o.stagger * 0.4,
    ease:          o.ease,
    delay:         o.delay,
    scrollTrigger: scrollTrigger(el, o.start),
  });
}

// data-anim="fade-up"
// No split — whole element fades and slides up.
function animFadeUp(el, o) {
  gsap.from(el, {
    y:             40,
    autoAlpha:     0,
    duration:      o.duration,
    ease:          o.ease,
    delay:         o.delay,
    scrollTrigger: scrollTrigger(el, o.start),
  });
}

// data-anim="fade"
// No split — whole element fades in.
function animFade(el, o) {
  gsap.from(el, {
    autoAlpha:     0,
    duration:      o.duration,
    ease:          o.ease,
    delay:         o.delay,
    scrollTrigger: scrollTrigger(el, o.start),
  });
}

// ── Registry ──────────────────────────────────────────────────────────────────

const ANIM = {
  lines:    animLines,
  words:    animWords,
  chars:    animChars,
  "fade-up": animFadeUp,
  fade:     animFade,
};

// ── Init ──────────────────────────────────────────────────────────────────────

function initTextAnimations() {
  document.querySelectorAll("[data-anim]").forEach(el => {
    const fn = ANIM[el.dataset.anim];
    if (fn) fn(el, opts(el));
  });
}

document.addEventListener("DOMContentLoaded", () => {
  document.fonts.ready.then(() => {
    initTextAnimations();
    ScrollTrigger.refresh();
  });
});
export { initTextAnimations };
