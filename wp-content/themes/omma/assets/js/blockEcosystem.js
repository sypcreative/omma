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

  if (subtitle) {
    gsap.to(subtitle, {
      autoAlpha: 1, y: 0, filter: "blur(0px)",
      duration: 0.9, ease: "expo.out", clearProps: "filter",
      scrollTrigger: { trigger: subtitle, start: "top 85%", once: true },
    });
  }

  cards.forEach(card => {
    gsap.to(card, {
      scale: 1, autoAlpha: 1,
      duration: 0.9, ease: "expo.out",
      scrollTrigger: { trigger: card, start: "top 85%", once: true },
    });
  });

  if (cta) {
    gsap.to(cta, {
      x: 0, autoAlpha: 1,
      duration: 0.8, ease: "expo.out",
      scrollTrigger: { trigger: cta, start: "top 88%", once: true },
    });
  }
}

function initEcosystemStack(section) {
  const stackEl = section.querySelector("[data-ecosystem-stack]");
  if (!stackEl) return;

  const wrap  = stackEl.querySelector("[data-ecosystem-stack-wrap]");
  const cards = gsap.utils.toArray("[data-ecosystem-card]", stackEl);
  if (!wrap || cards.length < 2) return;

  const ctx = gsap.context(() => {
    const cardH = cards[0].offsetHeight;

    gsap.set(wrap,  { position: "relative", height: cardH });
    gsap.set(cards, { position: "absolute", top: 0, left: 0, right: 0 });
    gsap.set(cards.slice(1), { yPercent: 110 });

    // Pinear la sección entera: título + subtítulo quedan fijos arriba
    // start "top 5%" da un pequeño margen para que el contenido no quede
    // pegado al borde superior del viewport al pinear
    const tl = gsap.timeline({
      scrollTrigger: {
        trigger:             section,
        start:               "top 10%",
        end:                 () => `+=${cardH * (cards.length - 1)}`,
        pin:                 true,
        pinSpacing:          true,
        scrub:               1,
        anticipatePin:       1,
        invalidateOnRefresh: true,
      },
    });

    cards.forEach((card, i) => {
      if (i === 0) return;
      const pos = i - 1;

      tl.fromTo(
        card,
        { yPercent: 110 },
        { yPercent: 0, duration: 1, ease: "power2.inOut" },
        pos,
      );

      for (let j = 0; j < i; j++) {
        const prevDepth = i - 1 - j;
        const nextDepth = i - j;
        tl.fromTo(
          cards[j],
          { scale: 1 - prevDepth * 0.04, y: -(prevDepth * 16) },
          { scale: 1 - nextDepth * 0.04, y: -(nextDepth * 16), duration: 1, ease: "power2.inOut" },
          pos,
        );
      }
    });
  }, section);

  return () => ctx.revert();
}

export function initBlockEcosystem() {
  const section = document.querySelector(".block-ecosystem");
  if (!section) return;

  const mm = gsap.matchMedia();

  mm.add("(min-width: 768px)", () => {
    revealSection(section);
  });

  mm.add("(max-width: 767px)", () => {
    // Revelar título y subtítulo sin animación en mobile
    const title    = section.querySelector(".block-ecosystem__title");
    const subtitle = section.querySelector(".block-ecosystem__subtitle");
    const cta      = section.querySelector(".block-ecosystem__cta");
    if (title)    gsap.set(title,    { visibility: "visible", autoAlpha: 1 });
    if (subtitle) gsap.set(subtitle, { autoAlpha: 1 });
    if (cta)      gsap.set(cta,      { autoAlpha: 1 });

    return initEcosystemStack(section);
  });
}

document.addEventListener("DOMContentLoaded", () => initBlockEcosystem());
