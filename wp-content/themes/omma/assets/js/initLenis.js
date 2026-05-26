import Lenis from "@studio-freight/lenis";
import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

let _lenis;
let _tickerCallback;
let _refreshListener;

export function initLenis(options = {}) {
  if (_lenis) return _lenis;

  // No inicializar Lenis en la landing — el progress nav usa scroll nativo
  if (document.querySelector("[data-progress-nav-list]")) {
    ScrollTrigger.refresh();
    return null;
  }

  const isMobile = window.matchMedia("(max-width: 768px)").matches;

  if (isMobile) {
    ScrollTrigger.refresh();
    return null;
  }

  const lenis = new Lenis({
    lerp: 0.08, // solo lerp, sin duration
    smoothWheel: true,
    smoothTouch: false,
    ...options,
  });

  // Sincroniza ScrollTrigger con la posición real de Lenis
  lenis.on("scroll", ({ scroll }) => {
    ScrollTrigger.update();
  });

  _tickerCallback = (time) => {
    lenis.raf(time * 1000);
  };
  gsap.ticker.add(_tickerCallback);
  gsap.ticker.lagSmoothing(0);

  // Sin scrollerProxy — ScrollTrigger lee el scroll de Lenis
  // a través del evento scroll + update
  _refreshListener = () => lenis.resize();
  ScrollTrigger.addEventListener("refresh", _refreshListener);
  ScrollTrigger.refresh();

  _lenis = lenis;
  return lenis;
}

export function destroyLenis() {
  if (!_lenis) return;

  if (_tickerCallback) {
    gsap.ticker.remove(_tickerCallback);
    _tickerCallback = null;
  }

  if (_refreshListener) {
    ScrollTrigger.removeEventListener("refresh", _refreshListener);
    _refreshListener = null;
  }

  _lenis.destroy();
  _lenis = undefined;
}
