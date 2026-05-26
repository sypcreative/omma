import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

function initStackingCards() {
  const section = document.querySelector("[data-stack-section]");
  if (!section) return;

  const wrap = section.querySelector("[data-stack-wrap]");
  const cards = gsap.utils.toArray("[data-stack-card]", section);
  if (cards.length < 2) return;

  const mm = gsap.matchMedia();

  mm.add("(min-width: 992px)", () => {
    const ctx = gsap.context(() => {

      const maxH = Math.max(...cards.map((c) => c.offsetHeight));

      gsap.set(wrap, { position: "relative", height: maxH });
      gsap.set(cards, { position: "absolute", top: 0, left: 0, right: 0 });

      gsap.set(section, { overflow: "hidden" });

      gsap.set(cards.slice(1), { yPercent: 110 });

      const scrollPerCard = window.innerHeight * 0.9;

      const tl = gsap.timeline({
        scrollTrigger: {
          trigger: section,
          start: "top top",
          end: `+=${(cards.length - 1) * scrollPerCard}`,
          pin: true,
          scrub: 1,
          anticipatePin: 1,
        },
      });

      cards.forEach((card, i) => {
        if (i === 0) return;

        const pos = i - 1;

        tl.fromTo(
          card,
          { yPercent: 110 },
          { yPercent: 0, duration: 1, ease: "power2.inOut" },
          pos
        );

        for (let j = 0; j < i; j++) {
          const prevDepth = i - 1 - j;
          const nextDepth = i - j;
          tl.fromTo(
            cards[j],
            {
              scale: 1 - prevDepth * 0.04,
              y: -(prevDepth * 18),
            },
            {
              scale: 1 - nextDepth * 0.04,
              y: -(nextDepth * 18),
              duration: 1,
              ease: "power2.inOut",
            },
            pos
          );
        }
      });

    }, section);

    return () => ctx.revert();
  });
}

document.addEventListener("DOMContentLoaded", initStackingCards);
export { initStackingCards };
