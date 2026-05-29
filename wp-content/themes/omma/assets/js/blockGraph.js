import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

function initBlockGraph() {
  const section = document.querySelector("[data-graph-section]");
  if (!section) return;

  const nodes      = gsap.utils.toArray("[data-graph-node]", section);
  const connectors = gsap.utils.toArray("[data-graph-connector]", section);

  if (!nodes.length) return;

  // ── Initial states ────────────────────────────────────────────────────────────
  gsap.set(nodes, { autoAlpha: 0, y: 28 });

  connectors.forEach(c => {
    gsap.set(c.querySelectorAll(".block-graph__shaft"), { scaleX: 0 });
    gsap.set(c.querySelectorAll(".block-graph__tip"),   { autoAlpha: 0 });
    gsap.set(c.querySelectorAll("[data-graph-label]"),  { autoAlpha: 0, y: 6 });
  });

  // ── Scroll-triggered timeline ─────────────────────────────────────────────────
  const tl = gsap.timeline({
    scrollTrigger: {
      trigger: section,
      start: "top 75%",
      once: true,
    },
  });

  // Nodes stagger in left → right
  tl.to(nodes, {
    autoAlpha: 1,
    y: 0,
    duration: 0.7,
    stagger: 0.14,
    ease: "power3.out",
  });

  // Each connector: forward shaft draws, tip appears, then backward shaft + tip
  connectors.forEach((connector, i) => {
    const fwdShaft = connector.querySelector(".block-graph__arrow--fwd .block-graph__shaft");
    const fwdTip   = connector.querySelector(".block-graph__arrow--fwd .block-graph__tip");
    const bwdShaft = connector.querySelector(".block-graph__arrow--bwd .block-graph__shaft");
    const bwdTip   = connector.querySelector(".block-graph__arrow--bwd .block-graph__tip");

    // Overlap with the tail of the nodes animation on first connector
    const startPos = i === 0 ? ">-=0.25" : "<+=0.08";

    const fwdLabel = connector.querySelector(".block-graph__conn-label--fwd");
    const bwdLabel = connector.querySelector(".block-graph__conn-label--bwd");

    tl
      .to(fwdShaft, { scaleX: 1, duration: 0.4, ease: "power2.inOut" }, startPos)
      .to(fwdTip,   { autoAlpha: 1, duration: 0.15 }, ">")
      .to(fwdLabel, { autoAlpha: 1, y: 0, duration: 0.3, ease: "power2.out" }, "<")
      .to(bwdShaft, { scaleX: 1, duration: 0.4, ease: "power2.inOut" }, "<-=0.05")
      .to(bwdTip,   { autoAlpha: 1, duration: 0.15 }, ">")
      .to(bwdLabel, { autoAlpha: 1, y: 0, duration: 0.3, ease: "power2.out" }, "<");
  });
}

document.addEventListener("DOMContentLoaded", initBlockGraph);
export { initBlockGraph };
