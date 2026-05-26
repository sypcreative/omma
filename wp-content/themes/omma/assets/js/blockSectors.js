import { gsap } from "gsap";

function initBlockSectors() {
  const wrap = document.querySelector('[data-sectors-image]');
  if (!wrap) return;

  const items = document.querySelectorAll('[data-sector-index]');
  if (!items.length) return;

  const slides = Array.from(wrap.querySelectorAll('[data-slide-index]'));
  if (!slides.length) return;

  const stripPosMap = {};
  slides.forEach((slide, pos) => {
    stripPosMap[parseInt(slide.dataset.slideIndex)] = pos;
  });

  const activeSlide = wrap.querySelector('.is--active');
  const initItemIndex = activeSlide
    ? parseInt(activeSlide.dataset.slideIndex)
    : parseInt(slides[0].dataset.slideIndex);

  let currentItemIndex = initItemIndex;
  let currentStripPos = stripPosMap[initItemIndex] ?? 0;

  slides.forEach((slide, pos) => {
    gsap.set(slide, { yPercent: (pos - currentStripPos) * 100 });
  });

  const list = document.querySelector('[data-directional-hover]');

  if (list) {
    const moveTo = gsap.quickTo(wrap, 'y', { duration: 0.5, ease: 'power3.out' });

    list.addEventListener('mouseenter', () => gsap.set(wrap, { opacity: 1 }));
    list.addEventListener('mouseleave', () => gsap.set(wrap, { opacity: 0 }));

    list.addEventListener('mousemove', (e) => {
      const listRect = list.getBoundingClientRect();
      const relY = e.clientY - listRect.top;
      const halfH = wrap.offsetHeight / 2;
      const maxY = listRect.height - wrap.offsetHeight;
      const targetY = Math.max(0, Math.min(relY - halfH, maxY));
      moveTo(targetY);
    });
  }

  items.forEach(item => {
    item.addEventListener('mouseenter', () => {
      const targetItemIndex = parseInt(item.dataset.sectorIndex);
      if (targetItemIndex === currentItemIndex) return;

      const targetStripPos = stripPosMap[targetItemIndex];
      if (targetStripPos === undefined) return;

      slides.forEach((slide, pos) => {
        gsap.to(slide, {
          yPercent: (pos - targetStripPos) * 100,
          duration: 0.5,
          ease: 'power2.inOut',
          overwrite: true,
        });
      });

      currentItemIndex = targetItemIndex;
      currentStripPos = targetStripPos;
    });
  });
}

document.addEventListener('DOMContentLoaded', initBlockSectors);
export { initBlockSectors };
