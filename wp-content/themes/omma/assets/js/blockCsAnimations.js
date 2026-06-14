import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { SplitText } from "gsap/SplitText";

gsap.registerPlugin(ScrollTrigger, SplitText);

// ── Helpers ───────────────────────────────────────────────────────────────────

// SplitText with its own individual ScrollTrigger (for individual scroll reveals)
function splitRevealScroll(el, start = "top 85%") {
  if (!el) return;
  const split = new SplitText(el, { type: "lines,words", linesClass: "split-line-mask" });
  gsap.set(split.lines, {
    overflow:      "hidden",
    paddingTop:    "0.1em",  marginTop:    "-0.1em",
    paddingBottom: "0.25em", marginBottom: "-0.25em",
  });
  gsap.set(split.words, { yPercent: 130 });
  gsap.set(el, { visibility: "visible" });
  gsap.to(split.words, {
    yPercent: 0,
    duration: 1.1,
    stagger:  { each: 0.04, from: "start" },
    ease:     "expo.out",
    scrollTrigger: { trigger: el, start, once: true },
  });
}

// SplitText added to an existing parent timeline (used for header sequential reveal)
function splitRevealTimeline(el, tl, position = 0) {
  if (!el) return;
  const split = new SplitText(el, { type: "lines,words", linesClass: "split-line-mask" });
  gsap.set(split.lines, {
    overflow:      "hidden",
    paddingTop:    "0.1em",  marginTop:    "-0.1em",
    paddingBottom: "0.25em", marginBottom: "-0.25em",
  });
  gsap.set(split.words, { yPercent: 130 });
  gsap.set(el, { visibility: "visible" });
  tl.to(split.words, {
    yPercent: 0,
    duration: 1.1,
    stagger:  { each: 0.04, from: "start" },
    ease:     "expo.out",
  }, position);
}

function parseCounter(text) {
  const t = text.trim();
  const m = t.match(/^([+€$£¥]?)([\d,]+\.?\d*)(.*)$/);
  if (!m) return null;
  const value = parseFloat(m[2].replace(/,/g, ""));
  if (isNaN(value)) return null;
  return { prefix: m[1], value, suffix: m[3].trim() };
}

// ── block-cs-header ───────────────────────────────────────────────────────────
// All above the fold on load → sequential timeline, fires immediately.

function initCsHeader() {
  const section = document.querySelector(".block-cs-header");
  if (!section) return;

  const title    = section.querySelector("h1");
  const excerpt  = section.querySelector(".block-cs-header__excerpt");
  const sector   = section.querySelector(".block-cs-header__sector");
  const services = section.querySelector(".block-cs-header__services");

  if (excerpt)  gsap.set(excerpt,  { autoAlpha: 0, y: 16 });
  if (sector)   gsap.set(sector,   { autoAlpha: 0, y: 16 });
  if (services) gsap.set(services, { autoAlpha: 0, y: 16 });

  const tl = gsap.timeline({
    scrollTrigger: { trigger: section, start: "top bottom", once: true },
  });

  splitRevealTimeline(title, tl, 0);
  if (excerpt)  tl.to(excerpt,  { autoAlpha: 1, y: 0, duration: 0.8, ease: "expo.out" }, "-=0.7");
  if (sector)   tl.to(sector,   { autoAlpha: 1, y: 0, duration: 0.8, ease: "expo.out" }, "-=0.5");
  if (services) tl.to(services, { autoAlpha: 1, y: 0, duration: 0.8, ease: "expo.out" }, "<");
}

// ── block-cs-details ──────────────────────────────────────────────────────────
// h2 fires when it enters view. Each row fires when that row enters view.

function initCsDetails() {
  const section = document.querySelector(".block-cs-details");
  if (!section) return;

  const title = section.querySelector("h2");
  const items = section.querySelectorAll(".block-cs-details__item");

  splitRevealScroll(title, "top 85%");

  items.forEach(item => {
    gsap.set(item, { autoAlpha: 0, y: 20 });
    gsap.to(item, {
      autoAlpha: 1,
      y:         0,
      duration:  0.7,
      ease:      "expo.out",
      scrollTrigger: { trigger: item, start: "top 88%", once: true },
    });
  });
}

// ── block-cs-extra-info ───────────────────────────────────────────────────────
// h2 fires when it enters view, desc fires when it enters view.

function initCsExtraInfo() {
  const section = document.querySelector(".block-cs-extra-info");
  if (!section) return;

  const title = section.querySelector("h2");
  const desc  = section.querySelector(".block-cs-extra-info__desc");

  splitRevealScroll(title, "top 85%");

  if (desc) {
    gsap.set(desc, { autoAlpha: 0, y: 20 });
    gsap.to(desc, {
      autoAlpha: 1,
      y:         0,
      duration:  0.9,
      ease:      "expo.out",
      scrollTrigger: { trigger: desc, start: "top 88%", once: true },
    });
  }
}

// ── block-cs-data ─────────────────────────────────────────────────────────────
// Each element has its own trigger. Numbers count up when they enter view.

function initCsData() {
  const section = document.querySelector(".block-cs-data");
  if (!section) return;

  const titulo  = section.querySelector(".block-cs-data__titulo");
  const numeros = section.querySelectorAll(".block-cs-data__numero");
  const textos  = section.querySelectorAll(".block-cs-data__texto");

  if (titulo) {
    gsap.set(titulo, { autoAlpha: 0, y: 14 });
    gsap.to(titulo, {
      autoAlpha: 1,
      y:         0,
      duration:  0.7,
      ease:      "expo.out",
      scrollTrigger: { trigger: titulo, start: "top 88%", once: true },
    });
  }

  numeros.forEach(el => {
    const original = el.textContent.trim();
    const parsed   = parseCounter(original);

    gsap.set(el, { autoAlpha: 0 });

    ScrollTrigger.create({
      trigger: el,
      start:   "top 88%",
      once:    true,
      onEnter() {
        gsap.to(el, { autoAlpha: 1, duration: 0.5, ease: "expo.out" });
        if (parsed) {
          const obj     = { val: 0 };
          const isLarge = parsed.value > 10;
          gsap.to(obj, {
            val:      parsed.value,
            duration: 2.2,
            ease:     "power3.out",
            snap:     { val: isLarge ? 1 : 0.1 },
            onUpdate() {
              const num = isLarge
                ? Math.round(obj.val).toLocaleString()
                : obj.val.toFixed(1);
              el.textContent = parsed.prefix + num + parsed.suffix;
            },
            onComplete() { el.textContent = original; },
          });
        }
      },
    });
  });

  textos.forEach(el => {
    gsap.set(el, { autoAlpha: 0, y: 14 });
    gsap.to(el, {
      autoAlpha: 1,
      y:         0,
      duration:  0.7,
      ease:      "expo.out",
      scrollTrigger: { trigger: el, start: "top 90%", once: true },
    });
  });
}

// ── block-cs-images ───────────────────────────────────────────────────────────
// Each image fires when it enters view.

function initCsImages() {
  const section = document.querySelector(".block-cs-images");
  if (!section) return;

  const items = section.querySelectorAll(".block-cs-images__item");
  items.forEach(item => {
    gsap.set(item, { autoAlpha: 0, y: 24, scale: 0.97 });
    gsap.to(item, {
      autoAlpha: 1,
      y:         0,
      scale:     1,
      duration:  1.0,
      ease:      "expo.out",
      scrollTrigger: { trigger: item, start: "top 88%", once: true },
    });
  });
}

// ── block-cs-more-projects ────────────────────────────────────────────────────
// Label fires when it enters view. Each project row fires when it enters view.

function initCsMoreProjects() {
  const section = document.querySelector(".block-cs-more-projects");
  if (!section) return;

  const label = section.querySelector(".block-cs-more-projects__section-label");
  const items = section.querySelectorAll(".block-cs-more-projects__item");

  if (label) {
    gsap.set(label, { autoAlpha: 0, y: 14 });
    gsap.to(label, {
      autoAlpha: 1,
      y:         0,
      duration:  0.7,
      ease:      "expo.out",
      scrollTrigger: { trigger: label, start: "top 88%", once: true },
    });
  }

  items.forEach(item => {
    gsap.set(item, { autoAlpha: 0, y: 20 });
    gsap.to(item, {
      autoAlpha: 1,
      y:         0,
      duration:  0.7,
      ease:      "expo.out",
      scrollTrigger: { trigger: item, start: "top 88%", once: true },
    });
  });
}

// ── Init ──────────────────────────────────────────────────────────────────────

export function initBlockCsAnimations() {
  initCsHeader();
  initCsDetails();
  initCsExtraInfo();
  initCsData();
  initCsImages();
  initCsMoreProjects();
}

document.addEventListener("DOMContentLoaded", () => initBlockCsAnimations());
