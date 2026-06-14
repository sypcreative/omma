import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { SplitText } from "gsap/SplitText";

gsap.registerPlugin(ScrollTrigger, SplitText);

function parseCounter(text) {
  const t = text.trim();
  const m = t.match(/^([+€$£¥]?)([\d,]+\.?\d*)(.*)$/);
  if (!m) return null;
  const value = parseFloat(m[2].replace(/,/g, ""));
  if (isNaN(value)) return null;
  return { prefix: m[1], value, suffix: m[3].trim() };
}

export function initBlockData() {
  const section = document.querySelector(".block-data");
  if (!section) return;

  const subtitle = section.querySelector(".block-data__subtitle");
  const counters = section.querySelectorAll(".block-data__counter");
  const labels   = section.querySelectorAll(".block-data__label");

  // Subtítulo: SplitText con su propio trigger
  if (subtitle) {
    const split = new SplitText(subtitle, { type: "lines,words", linesClass: "split-line-mask" });
    gsap.set(split.lines, {
      overflow:      "hidden",
      paddingTop:    "0.1em",  marginTop:    "-0.1em",
      paddingBottom: "0.25em", marginBottom: "-0.25em",
    });
    gsap.set(split.words, { yPercent: 130 });
    gsap.set(subtitle, { visibility: "visible" });
    gsap.to(split.words, {
      yPercent: 0,
      duration: 1.1,
      stagger:  { each: 0.04, from: "start" },
      ease:     "expo.out",
      scrollTrigger: { trigger: subtitle, start: "top 85%", once: true },
    });
  }

  // Contadores: count-up por elemento
  counters.forEach((el) => {
    const original = el.textContent.trim();
    const parsed   = parseCounter(original);

    if (parsed) {
      const obj     = { val: 0 };
      const isLarge = parsed.value > 10;
      ScrollTrigger.create({
        trigger: el,
        start:   "top 85%",
        once:    true,
        onEnter() {
          gsap.to(obj, {
            val:      parsed.value,
            duration: 2.2,
            ease:     "power3.out",
            delay:    0.3,
            snap:     { val: isLarge ? 1 : 0.1 },
            onUpdate() {
              const num = isLarge
                ? Math.round(obj.val).toLocaleString()
                : obj.val.toFixed(1);
              el.textContent = parsed.prefix + num + parsed.suffix;
            },
            onComplete() { el.textContent = original; },
          });
        },
      });
    } else {
      gsap.set(el, { yPercent: 40, autoAlpha: 0 });
      gsap.to(el, {
        yPercent:  0,
        autoAlpha: 1,
        duration:  1.0,
        ease:      "expo.out",
        scrollTrigger: { trigger: el, start: "top 85%", once: true },
      });
    }
  });

  // Labels: trigger individual por label
  labels.forEach(el => {
    gsap.set(el, { autoAlpha: 0, y: 14 });
    gsap.to(el, {
      autoAlpha: 1,
      y:         0,
      duration:  0.7,
      ease:      "expo.out",
      scrollTrigger: { trigger: el, start: "top 88%", once: true },
    });
  });
}

document.addEventListener("DOMContentLoaded", () => initBlockData());
