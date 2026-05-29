import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { ScrollToPlugin } from "gsap/ScrollToPlugin";
import { getLenis } from "./initLenis.js";

gsap.registerPlugin(ScrollTrigger, ScrollToPlugin);

function initProgressNavigation() {
  let navProgress = document.querySelector('[data-progress-nav-list]');
  if (!navProgress) return;

  // Intercept anchor clicks — use GSAP (aware of pin spacers) instead of
  // native browser scroll, which conflicts with ScrollTrigger snap
  document.querySelectorAll('[data-progress-nav-target][href]').forEach(link => {
    link.addEventListener('click', e => {
      e.preventDefault();
      const target = document.querySelector(link.getAttribute('href'));
      if (!target) return;
      const lenis = getLenis();
      if (lenis) {
        lenis.scrollTo(target, { duration: 1.2 });
      } else {
        gsap.to(window, { scrollTo: { y: target }, duration: 1, ease: 'power2.inOut' });
      }
    });
  });

  // Create or select the moving indicator
  let indicator = navProgress.querySelector('.progress-nav__indicator');
  if (!indicator) {
    indicator = document.createElement('div');
    indicator.className = 'progress-nav__indicator';
    navProgress.appendChild(indicator);
  }

  function updateIndicator(activeLink) {
    let parentWidth  = navProgress.offsetWidth;
    let parentHeight = navProgress.offsetHeight;
    let parentRect   = navProgress.getBoundingClientRect();
    let linkRect     = activeLink.getBoundingClientRect();
    let linkPos = {
      left: linkRect.left - parentRect.left,
      top:  linkRect.top  - parentRect.top,
    };
    indicator.style.left   = (linkPos.left          / parentWidth)  * 100 + '%';
    indicator.style.top    = (linkPos.top           / parentHeight) * 100 + '%';
    indicator.style.width  = (activeLink.offsetWidth  / parentWidth)  * 100 + '%';
    indicator.style.height = (activeLink.offsetHeight / parentHeight) * 100 + '%';
  }

  let progressAnchors = gsap.utils.toArray('[data-progress-nav-anchor]');

  progressAnchors.forEach((progressAnchor) => {
    let anchorID = progressAnchor.getAttribute('id');

    ScrollTrigger.create({
      trigger: progressAnchor,
      start: '0% 50%',
      end: '100% 50%',
      onEnter: () => {
        let activeLink = navProgress.querySelector('[data-progress-nav-target="#' + anchorID + '"]');
        if (!activeLink) return;
        activeLink.classList.add('is--active');
        navProgress.querySelectorAll('[data-progress-nav-target]').forEach(sib => {
          if (sib !== activeLink) sib.classList.remove('is--active');
        });
        updateIndicator(activeLink);
      },
      onEnterBack: () => {
        let activeLink = navProgress.querySelector('[data-progress-nav-target="#' + anchorID + '"]');
        if (!activeLink) return;
        activeLink.classList.add('is--active');
        navProgress.querySelectorAll('[data-progress-nav-target]').forEach(sib => {
          if (sib !== activeLink) sib.classList.remove('is--active');
        });
        updateIndicator(activeLink);
      },
    });
  });
}

document.addEventListener('DOMContentLoaded', () => {
  initProgressNavigation();
});

export { initProgressNavigation };
