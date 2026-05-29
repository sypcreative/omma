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

  // ── Mobile: simple scroll-triggered reveals ───────────────────────────────
  mm.add("(max-width: 991px)", () => {
    panels.forEach(panel => {
      const label = panel.querySelector(".block-about__panel-label");
      const text  = panel.querySelector(".block-about__panel-text");
      const line  = panel.querySelector(".block-about__panel-line");
      const st    = { trigger: panel, start: "top 80%", once: true };

      if (line) {
        gsap.from(line, { scaleX: 0, transformOrigin: "left center", duration: 0.5, ease: "power2.inOut", scrollTrigger: st });
      }

      const tl = gsap.timeline({ scrollTrigger: st, delay: 0.1 });

      if (label) {
        const s = new SplitText(label, { type: "lines", linesClass: "split-line" });
        maskLines(s.lines);
        tl.from(s.lines, { yPercent: 110, duration: 0.55, stagger: 0.05, ease: "power3.out" });
      }

      if (text) {
        const s = new SplitText(text, { type: "lines", linesClass: "split-line" });
        maskLines(s.lines);
        tl.from(s.lines, { yPercent: 110, duration: 0.65, stagger: 0.04, ease: "power3.out" }, "<0.15");
      }
    });
  });

  // ── Desktop: horizontal scroll + containerAnimation ───────────────────────
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
      scrollTrigger: {
        trigger: section,
        pin: true,
        scrub: 1.5,
        start: "top top",
        end: () => `+=${totalMove}`,
        anticipatePin: 1,
        refreshPriority: 1,
        onUpdate: self => {
          if (!counter) return;
          const idx = Math.min(
            panels.length - 1,
            Math.floor(self.progress * panels.length + 0.01)
          );
          counter.textContent = String(idx + 1).padStart(2, "0");
        },
      },
    });

    const mainST = mainTween.scrollTrigger;

    // ── Text reveal per panel ─────────────────────────────────────────────────
    panels.forEach((panel, i) => {
      const label = panel.querySelector(".block-about__panel-label");
      const text  = panel.querySelector(".block-about__panel-text");
      const line  = panel.querySelector(".block-about__panel-line");
      const index = panel.querySelector(".block-about__index");

      if (i === 0) {
        // Panel 0: animate in when the section enters the viewport
        const st0 = { trigger: section, start: "top 70%", once: true };

        if (index) {
          gsap.from(index, { autoAlpha: 0, y: 30, duration: 0.8, ease: "power3.out", scrollTrigger: st0 });
        }

        if (line) {
          gsap.from(line, {
            scaleX: 0,
            transformOrigin: "left center",
            duration: 0.5,
            ease: "power2.inOut",
            delay: 0.1,
            scrollTrigger: st0,
          });
        }

        const tl0 = gsap.timeline({ scrollTrigger: st0, delay: 0.15 });

        if (label) {
          const s = new SplitText(label, { type: "lines", linesClass: "split-line" });
          maskLines(s.lines);
          tl0.from(s.lines, { yPercent: 110, duration: 0.55, stagger: 0.05, ease: "power3.out" });
        }

        if (text) {
          const s = new SplitText(text, { type: "lines", linesClass: "split-line" });
          maskLines(s.lines);
          tl0.from(s.lines, { yPercent: 110, duration: 0.65, stagger: 0.04, ease: "power3.out" }, "<0.15");
        }

        return;
      }

      // Panels 1+: reveal via containerAnimation
      const stLine = { trigger: panel, containerAnimation: mainST, start: "left 65%", toggleActions: "play none none reverse" };
      const stText = { trigger: panel, containerAnimation: mainST, start: "left 75%", toggleActions: "play none none reverse" };

      if (index) {
        gsap.from(index, {
          autoAlpha: 0,
          y: 40,
          duration: 0.9,
          ease: "power3.out",
          scrollTrigger: stLine,
        });
      }

      if (line) {
        gsap.from(line, {
          scaleX: 0,
          transformOrigin: "left center",
          duration: 0.5,
          ease: "power2.inOut",
          scrollTrigger: stLine,
        });
      }

      const labelSplit = label ? new SplitText(label, { type: "lines", linesClass: "split-line" }) : null;
      if (labelSplit) maskLines(labelSplit.lines);

      const textSplit = text ? new SplitText(text, { type: "lines", linesClass: "split-line" }) : null;
      if (textSplit) maskLines(textSplit.lines);

      if (!labelSplit && !textSplit) return;

      const tl = gsap.timeline({ scrollTrigger: stText });

      if (labelSplit) {
        tl.from(labelSplit.lines, { yPercent: 110, duration: 0.55, stagger: 0.05, ease: "power3.out" });
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
      mainTween.scrollTrigger?.kill();
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
  });
});
export { initBlockAbout };
