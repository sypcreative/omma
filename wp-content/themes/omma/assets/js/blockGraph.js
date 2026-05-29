import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

function initBlockGraph() {
  const section = document.querySelector("[data-graph-section]");
  if (!section) return;

  const nodes      = gsap.utils.toArray("[data-graph-node]",      section);
  const connectors = gsap.utils.toArray("[data-graph-connector]", section);

  if (!nodes.length) return;

  const mm = gsap.matchMedia();

  // ── Mobile: vertical, scaleY shafts ──────────────────────────────────────────
  mm.add("(max-width: 991px)", () => {
    gsap.set(nodes, { autoAlpha: 0, y: 20 });
    connectors.forEach(c => {
      gsap.set(c.querySelectorAll(".block-graph__shaft"), { scaleY: 0 });
      gsap.set(c.querySelectorAll(".block-graph__tip"),   { autoAlpha: 0 });
    });

    const tl = gsap.timeline({
      scrollTrigger: { trigger: section, start: "top 75%", once: true },
    });

    tl.to(nodes, { autoAlpha: 1, y: 0, duration: 0.6, stagger: 0.12, ease: "power3.out" });

    connectors.forEach((connector, i) => {
      const fwdShaft = connector.querySelector(".block-graph__arrow--fwd .block-graph__shaft");
      const fwdTip   = connector.querySelector(".block-graph__arrow--fwd .block-graph__tip");
      const bwdShaft = connector.querySelector(".block-graph__arrow--bwd .block-graph__shaft");
      const bwdTip   = connector.querySelector(".block-graph__arrow--bwd .block-graph__tip");
      const start    = i === 0 ? ">-=0.2" : "<+=0.08";

      tl
        .to(fwdShaft, { scaleY: 1, duration: 0.35, ease: "power2.inOut" }, start)
        .to(fwdTip,   { autoAlpha: 1, duration: 0.15 }, ">")
        .to(bwdShaft, { scaleY: 1, duration: 0.35, ease: "power2.inOut" }, "<-=0.05")
        .to(bwdTip,   { autoAlpha: 1, duration: 0.15 }, ">");
    });
  });

  // ── Desktop: horizontal, scaleX shafts ───────────────────────────────────────
  mm.add("(min-width: 992px)", () => {
    gsap.set(nodes, { autoAlpha: 0, y: 28 });
    connectors.forEach(c => {
      gsap.set(c.querySelectorAll(".block-graph__shaft"), { scaleX: 0 });
      gsap.set(c.querySelectorAll(".block-graph__tip"),   { autoAlpha: 0 });
      gsap.set(c.querySelectorAll("[data-graph-label]"),  { autoAlpha: 0, y: 6 });
    });

    const tl = gsap.timeline({
      scrollTrigger: { trigger: section, start: "top 75%", once: true },
    });

    tl.to(nodes, { autoAlpha: 1, y: 0, duration: 0.7, stagger: 0.14, ease: "power3.out" });

    connectors.forEach((connector, i) => {
      const fwdShaft = connector.querySelector(".block-graph__arrow--fwd .block-graph__shaft");
      const fwdTip   = connector.querySelector(".block-graph__arrow--fwd .block-graph__tip");
      const bwdShaft = connector.querySelector(".block-graph__arrow--bwd .block-graph__shaft");
      const bwdTip   = connector.querySelector(".block-graph__arrow--bwd .block-graph__tip");
      const fwdLabel = connector.querySelector(".block-graph__conn-label--fwd");
      const bwdLabel = connector.querySelector(".block-graph__conn-label--bwd");
      const start    = i === 0 ? ">-=0.25" : "<+=0.08";

      tl
        .to(fwdShaft, { scaleX: 1, duration: 0.4,  ease: "power2.inOut" }, start)
        .to(fwdTip,   { autoAlpha: 1, duration: 0.15 }, ">")
        .to(fwdLabel, { autoAlpha: 1, y: 0, duration: 0.3, ease: "power2.out" }, "<")
        .to(bwdShaft, { scaleX: 1, duration: 0.4,  ease: "power2.inOut" }, "<-=0.05")
        .to(bwdTip,   { autoAlpha: 1, duration: 0.15 }, ">")
        .to(bwdLabel, { autoAlpha: 1, y: 0, duration: 0.3, ease: "power2.out" }, "<");
    });
  });
}

document.addEventListener("DOMContentLoaded", initBlockGraph);
export { initBlockGraph };
