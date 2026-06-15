import barba from "@barba/core";
import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { CustomEase } from "gsap/CustomEase";

import { getLenis } from "./initLenis.js";

// Block inits — re-called after each Barba transition
import { initBlockHeroHome }       from "./blockHeroHome.js";
import { initBlockData }           from "./blockData.js";
import { initBlockEcosystem }      from "./blockEcosystem.js";
import { initBlockLogos }          from "./blockLogos.js";
import { initBlockCapabilities }   from "./blockCapabilities.js";
import { initBlockMap, initBlockMap2D } from "./blockMap.js";
import { initBlockContact, initContactReveal } from "./blockContact.js";
import { initBlockAboutIntro }     from "./blockAboutIntro.js";
import { initBlockAboutCeo }       from "./blockAboutCeo.js";
import { initBlockAboutTeam }      from "./blockAboutTeam.js";
import { initBlockServicesList }   from "./blockServicesList.js";
import { initScrollProgress }      from "./scrollProgress.js";
import { initBlockSectors }        from "./blockSectors.js";
import { initBlockAbout }          from "./blockAbout.js";
import { initBlockGraph }          from "./blockGraph.js";
import { initBlockCsAnimations }   from "./blockCsAnimations.js";
import { initBlockCsMoreProjects } from "./blockCsMoreProjects.js";
import { initTextAnimations }      from "./textAnimations.js";
import { initGlobalParallax }      from "./parallax.js";
import { initStackingCards }       from "./stackingCards.js";
import { initDirectionalListHover } from "./directionalHover.js";
import { initProgressNavigation }      from "./progressNav.js";
import { initBlockServicesAnimations } from "./blockServicesAnimations.js";
import { initBlockListingAnimations }  from "./blockListingAnimations.js";

gsap.registerPlugin(ScrollTrigger, CustomEase);

history.scrollRestoration = "manual";

CustomEase.create("parallax", "0.7, 0.05, 0.13, 1");

const rmMQ = window.matchMedia("(prefers-reduced-motion: reduce)");
let reducedMotion = rmMQ.matches;
rmMQ.addEventListener?.("change", e => (reducedMotion = e.matches));

// Skip re-init on first load — DOMContentLoaded already handled it
let isFirstEnter = true;

// ── Block re-initialization (subsequent loads) ────────────────────────────────

function initAllBlocks() {
  // Home
  initBlockHeroHome();
  initBlockData();
  initBlockEcosystem();
  initBlockLogos();
  initBlockCapabilities();
  initBlockMap();
  initBlockMap2D();
  initBlockContact();
  initContactReveal();
  // About
  initBlockAboutIntro();
  initBlockAboutCeo();
  initBlockAboutTeam();
  // Services
  initBlockServicesList();
  initBlockServicesAnimations();
  // Listing pages (CS + news)
  initBlockListingAnimations();
  // Case Studies
  initBlockCsAnimations();
  initBlockCsMoreProjects();
  // Landing
  initBlockSectors();
  initBlockAbout();
  initBlockGraph();
  initProgressNavigation();
  // Global
  initTextAnimations();
  initGlobalParallax();
  initStackingCards();
  initDirectionalListHover();
  initScrollProgress();
}

// ── Nav active state ──────────────────────────────────────────────────────────

function updateNavActive(nextUrl) {
  const norm = p => p.replace(/\/$/, '') || '/';
  const currentPath = norm(nextUrl || window.location.pathname);
  document.querySelectorAll('.site-nav__item').forEach(li => {
    const link = li.querySelector('a');
    if (!link) return;
    const linkPath = norm(new URL(link.href, window.location.origin).pathname);
    li.classList.toggle('current-menu-item', linkPath === currentPath);
    li.classList.toggle('current-menu-ancestor', false);
  });
}

// ── Helpers ───────────────────────────────────────────────────────────────────

function resetPage(container) {
  window.scrollTo(0, 0);
  gsap.set(container, { clearProps: "position,top,left,right,zIndex" });
  const lenis = getLenis();
  if (lenis) {
    lenis.resize();
    lenis.start();
  }
}

// ── Leave: dark overlay + current page slides up ─────────────────────────────

function runPageLeaveAnimation(current) {
  const transitionWrap = document.querySelector("[data-transition-wrap]");
  const transitionDark = transitionWrap?.querySelector("[data-transition-dark]");

  const tl = gsap.timeline({ onComplete: () => current.remove() });

  if (reducedMotion) return tl.set(current, { autoAlpha: 0 });

  tl.set(transitionWrap, { zIndex: 2 });

  tl.fromTo(transitionDark,
    { autoAlpha: 0 },
    { autoAlpha: 0.8, duration: 1.2, ease: "parallax" },
    0
  );

  tl.fromTo(current,
    { y: "0vh" },
    { y: "-25vh", duration: 1.2, ease: "parallax" },
    0
  );

  tl.set(transitionDark, { autoAlpha: 0 });

  return tl;
}

// ── Enter: new page slides up from below ─────────────────────────────────────

function runPageEnterAnimation(next) {
  const tl = gsap.timeline();

  if (reducedMotion) {
    tl.set(next, { autoAlpha: 1 });
    tl.add("pageReady");
    tl.call(resetPage, [next], "pageReady");
    return new Promise(resolve => tl.call(resolve, null, "pageReady"));
  }

  tl.add("startEnter", 0);
  tl.set(next, { zIndex: 3 });

  tl.fromTo(next,
    { y: "100vh" },
    { y: "0vh", duration: 1.2, clearProps: "all", ease: "parallax" },
    "startEnter"
  );

  tl.add("pageReady");
  tl.call(resetPage, [next], "pageReady");

  return new Promise(resolve => tl.call(resolve, null, "pageReady"));
}

// ── Barba init ────────────────────────────────────────────────────────────────

export function initPageTransitions() {
  barba.hooks.beforeEnter(data => {
    gsap.set(data.next.container, {
      position: "fixed",
      top: 0, left: 0, right: 0,
    });
    const lenis = getLenis();
    if (lenis) lenis.stop();
  });

  barba.hooks.afterLeave(() => {
    ScrollTrigger.getAll().forEach(t => t.kill());
  });

  barba.hooks.afterEnter(data => {
    if (!isFirstEnter) {
      initAllBlocks();
      updateNavActive(new URL(data.next.url.href).pathname);
    }
    isFirstEnter = false;

    const lenis = getLenis();
    if (lenis) {
      lenis.resize();
      lenis.start();
    }
    ScrollTrigger.refresh();
  });

  barba.init({
    debug: false,
    timeout: 7000,
    preventRunning: true,
    transitions: [{
      name: "default",
      sync: true,

      async once(data) {
        resetPage(data.next.container);
      },

      async leave(data) {
        return runPageLeaveAnimation(data.current.container);
      },

      async enter(data) {
        return runPageEnterAnimation(data.next.container);
      },
    }],
  });
}
