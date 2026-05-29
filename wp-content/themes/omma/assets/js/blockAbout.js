import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

function initBlockAbout() {
  const section = document.querySelector("[data-about-section]");
  if (!section) return;

  const track      = section.querySelector("[data-about-track]");
  const panels     = gsap.utils.toArray("[data-about-panel]", section);
  const counter    = section.querySelector("[data-about-progress-current]");

  if (!track || panels.length < 2) return;

  const mm = gsap.matchMedia();

  mm.add("(min-width: 992px)", () => {
    const containerW = track.parentElement.offsetWidth;
    const totalMove  = containerW * (panels.length - 1);

    // Set explicit widths so each panel = full container width
    track.style.width = `${containerW * panels.length}px`;
    panels.forEach(p => {
      p.style.width     = `${containerW}px`;
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

    // ── Content reveal per panel ──────────────────────────────────────────────
    panels.forEach((panel, i) => {
      const content = panel.querySelector("[data-about-panel-content]");
      const line    = panel.querySelector(".block-about__panel-line");
      if (!content) return;

      // First panel starts visible
      if (i === 0) {
        gsap.set(content, { autoAlpha: 1, x: 0 });
        return;
      }

      gsap.set(content, { autoAlpha: 0, x: 50 });

      gsap.to(content, {
        autoAlpha: 1,
        x: 0,
        duration: 0.7,
        ease: "power3.out",
        scrollTrigger: {
          trigger: panel,
          containerAnimation: mainST,
          start: "left 75%",
          toggleActions: "play none none reverse",
        },
      });

      // Line draws in slightly after
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

document.addEventListener("DOMContentLoaded", initBlockAbout);
export { initBlockAbout };
