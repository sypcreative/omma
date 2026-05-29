import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { SplitText } from "gsap/SplitText";

gsap.registerPlugin(ScrollTrigger, SplitText);

function maskLines(lines) {
  lines.forEach(line => {
    const mask = document.createElement("span");
    mask.style.cssText = "display:block; overflow:hidden; padding-top:0.2em; margin-top:-0.2em; padding-bottom:0.1em;";
    line.parentNode.insertBefore(mask, line);
    mask.appendChild(line);
  });
}

function initBlockAbout() {
  const section = document.querySelector("[data-about-section]");
  if (!section) return;

  const track   = section.querySelector("[data-about-track]");
  const panels  = gsap.utils.toArray("[data-about-panel]", section);
  const counter = section.querySelector("[data-about-progress-current]");

  if (!track || panels.length < 2) return;

  const mm = gsap.matchMedia();

  mm.add("(min-width: 992px)", () => {
    const containerW = track.parentElement.offsetWidth;
    const totalMove  = containerW * (panels.length - 1);

    track.style.width = `${containerW * panels.length}px`;
    panels.forEach(p => {
      p.style.width      = `${containerW}px`;
      p.style.flexShrink = "0";
    });

    // ── Main horizontal tween ─────────────────────────────────────────────────
    const mainTween = gsap.to(track, {
      x: -totalMove,
      ease: "none",
      paused: true,
    });

    const mainST = ScrollTrigger.create({
      trigger: section,
      pin: true,
      scrub: 1.5,
      start: "top top",
      end: () => `+=${totalMove}`,
      anticipatePin: 1,
      refreshPriority: 1,
      animation: mainTween,
      onUpdate: self => {
        if (!counter) return;
        const idx = Math.min(
          panels.length - 1,
          Math.floor(self.progress * panels.length + 0.01)
        );
        counter.textContent = String(idx + 1).padStart(2, "0");
      },
    });

    // ── Text reveal per panel ─────────────────────────────────────────────────
    panels.forEach((panel, i) => {
      const label = panel.querySelector(".block-about__panel-label");
      const text  = panel.querySelector(".block-about__panel-text");
      const line  = panel.querySelector(".block-about__panel-line");

      // First panel: already visible, just split and set in place — no ScrollTrigger
      if (i === 0) {
        if (label) { const s = new SplitText(label, { type: "lines", linesClass: "split-line" }); maskLines(s.lines); }
        if (text)  { const s = new SplitText(text,  { type: "lines", linesClass: "split-line" }); maskLines(s.lines); }
        return;
      }

      // Panels 1+: reveal via containerAnimation
      if (line) {
        gsap.from(line, {
          scaleX: 0,
          transformOrigin: "left center",
          duration: 0.5,
          ease: "power2.inOut",
          scrollTrigger: {
            trigger: panel,
            containerAnimation: mainST,
            start: "left 65%",
            toggleActions: "play none none reverse",
          },
        });
      }

      const labelSplit = label ? new SplitText(label, { type: "lines", linesClass: "split-line" }) : null;
      if (labelSplit) maskLines(labelSplit.lines);

      const textSplit = text ? new SplitText(text, { type: "lines", linesClass: "split-line" }) : null;
      if (textSplit) maskLines(textSplit.lines);

      if (!labelSplit && !textSplit) return;

      const tl = gsap.timeline({
        scrollTrigger: {
          trigger: panel,
          containerAnimation: mainST,
          start: "left 75%",
          toggleActions: "play none none reverse",
        },
      });

      if (labelSplit) {
        tl.from(labelSplit.lines, {
          yPercent: 110,
          duration: 0.55,
          stagger: 0.05,
          ease: "power3.out",
        });
      }

      if (textSplit) {
        tl.from(textSplit.lines, {
          yPercent: 110,
          duration: 0.65,
          stagger: 0.04,
          ease: "power3.out",
        }, labelSplit ? "<0.15" : ">");
      }
    });

    return () => {
      mainST.kill();
      track.style.width = "";
      panels.forEach(p => {
        p.style.width      = "";
        p.style.flexShrink = "";
      });
    };
  });
}

document.addEventListener("DOMContentLoaded", () => {
  document.fonts.ready.then(() => {
    initBlockAbout();
    ScrollTrigger.refresh();
  });
});
export { initBlockAbout };
