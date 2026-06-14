import { gsap } from "gsap";

const LABEL_CLOSED = 'More Information ↓';
const LABEL_OPEN   = 'Less Information ↑';

export function initBlockServicesList() {
  const items = document.querySelectorAll(".block-services-list__item");
  if ( !items.length ) return;

  items.forEach( (item) => {
    const toggle = item.querySelector(".block-services-list__toggle");
    const body   = item.querySelector(".block-services-list__body");
    if ( !toggle || !body ) return;

    gsap.set(body, { height: 0, overflow: "hidden" });

    toggle.addEventListener("click", () => {
      const isOpen = item.classList.contains("is-open");
      const textEls = toggle.querySelectorAll(".button-020__default-text, .button-020__hover-text");

      if (isOpen) {
        gsap.to(body, {
          height:   0,
          duration: 0.65,
          ease:     "expo.inOut",
          onComplete() { body.setAttribute("aria-hidden", "true"); },
        });
        item.classList.remove("is-open");
        toggle.setAttribute("aria-expanded", "false");
        textEls.forEach(el => { el.textContent = LABEL_CLOSED; });
      } else {
        body.setAttribute("aria-hidden", "false");
        gsap.to(body, {
          height:   "auto",
          duration: 0.65,
          ease:     "expo.inOut",
        });
        item.classList.add("is-open");
        toggle.setAttribute("aria-expanded", "true");
        textEls.forEach(el => { el.textContent = LABEL_OPEN; });
      }
    });
  });
}

document.addEventListener("DOMContentLoaded", () => initBlockServicesList());
