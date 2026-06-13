import * as THREE from "three";
import { feature, mesh } from "topojson-client";
import worldData from "world-atlas/countries-110m.json";
import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

// ── Glow sprite texture ───────────────────────────────────────────────────────

/** Canvas con degradado radial blanco→transparente, para usar como sprite. */
function makeGlowSprite() {
  const S   = 64;
  const c   = Object.assign(document.createElement("canvas"), { width: S, height: S });
  const ctx = c.getContext("2d");
  const g   = ctx.createRadialGradient(S / 2, S / 2, 0, S / 2, S / 2, S / 2);
  g.addColorStop(0,    "rgba(255,255,255,1)");
  g.addColorStop(0.35, "rgba(255,255,255,0.5)");
  g.addColorStop(1,    "rgba(255,255,255,0)");
  ctx.fillStyle = g;
  ctx.fillRect(0, 0, S, S);
  return new THREE.CanvasTexture(c);
}

// ── Geo helpers ───────────────────────────────────────────────────────────────

/** GeoJSON [lng, lat] → THREE.Vector3 en esfera de radio r. */
function geoTo3D([lng, lat], r = 1) {
  const phi   = (90 - lat) * (Math.PI / 180);
  const theta = (lng + 180) * (Math.PI / 180);
  return new THREE.Vector3(
    -r * Math.sin(phi) * Math.cos(theta),
     r * Math.cos(phi),
     r * Math.sin(phi) * Math.sin(theta),
  );
}

// ── Land mask desde topojson ──────────────────────────────────────────────────

/**
 * Rasteriza las áreas de tierra del topojson sobre un canvas 1000×500
 * en proyección equirectangular. Retorna ImageData.
 */
function buildLandMask() {
  const W = 1000, H = 500;
  const c   = Object.assign(document.createElement("canvas"), { width: W, height: H });
  const ctx = c.getContext("2d");

  const landGeo = feature(worldData, worldData.objects.land);
  const polys   = landGeo.type === "Feature"
    ? [landGeo.geometry]
    : landGeo.features.map((f) => f.geometry);

  ctx.fillStyle = "white";

  polys.forEach((geom) => {
    const rings = geom.type === "Polygon"
      ? [geom.coordinates]
      : geom.coordinates; // MultiPolygon

    rings.forEach((poly) => {
      ctx.beginPath();
      poly.forEach((ring) => {
        ring.forEach(([lng, lat], i) => {
          const x = ((lng + 180) / 360) * W;
          const y = ((90 - lat) / 180) * H;
          if (i === 0) ctx.moveTo(x, y);
          else         ctx.lineTo(x, y);
        });
        ctx.closePath();
      });
      ctx.fill("evenodd");
    });
  });

  return { data: ctx.getImageData(0, 0, W, H), W, H };
}

/** Comprueba si (lat, lng) es tierra consultando la mask. */
function isLand({ data, W, H }, lat, lng) {
  const x   = Math.round(((lng + 180) / 360) * (W - 1));
  const y   = Math.round(((90 - lat) / 180) * (H - 1));
  const idx = (Math.max(0, Math.min(H - 1, y)) * W + Math.max(0, Math.min(W - 1, x))) * 4;
  return data.data[idx + 3] > 10;
}

/** Genera posiciones Fibonacci en la esfera, solo en tierra. */
function buildDots(mask, count, r) {
  const positions = [];
  const golden    = Math.PI * (3 - Math.sqrt(5));

  for (let i = 0; i < count; i++) {
    const yn = 1 - (2 * i) / (count - 1);
    const rn = Math.sqrt(Math.max(0, 1 - yn * yn));
    const tf = golden * i; // ángulo Fibonacci

    const xd = rn * Math.cos(tf);
    const zd = rn * Math.sin(tf);

    // Invertir geoTo3D: theta_geo = atan2(z, -x), lng = theta_geo*180/π - 180
    const lat = Math.asin(Math.max(-1, Math.min(1, yn))) * (180 / Math.PI);
    let   tg  = Math.atan2(zd, -xd);
    if (tg < 0) tg += 2 * Math.PI;
    const lng = tg * (180 / Math.PI) - 180;

    if (!isLand(mask, lat, lng)) continue;

    positions.push(xd * r, yn * r, zd * r);
  }
  return new Float32Array(positions);
}

/** Añade líneas GeoJSON MultiLineString al grupo. */
function addLines(multiLine, r, mat, group) {
  multiLine.coordinates.forEach((line) => {
    const pts = line.map((c) => geoTo3D(c, r));
    group.add(new THREE.Line(
      new THREE.BufferGeometry().setFromPoints(pts),
      mat,
    ));
  });
}

// ── Init ──────────────────────────────────────────────────────────────────────

export function initBlockMap() {
  const wrap = document.querySelector(".block-map__globe-wrap");
  if (!wrap) return;

  const canvas  = wrap.querySelector(".block-map__globe");
  const markers = JSON.parse(canvas.dataset.markers || "[]");
  const size    = wrap.offsetWidth || 700;

  // ── Renderer ──────────────────────────────────────────────────────────────
  const renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true });
  renderer.setPixelRatio(Math.min(devicePixelRatio, 2));
  renderer.setSize(size, size);
  renderer.setClearColor(0x000000, 0);

  // ── Scene / Camera ────────────────────────────────────────────────────────
  const scene  = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera(48, 1, 0.1, 100);
  camera.position.z = 2.5;

  const globe = new THREE.Group();
  globe.rotation.x = 0.08;
  globe.rotation.y = Math.PI * 0.95;
  scene.add(globe);

  // ── Esfera base (océano oscuro) ───────────────────────────────────────────
  globe.add(new THREE.Mesh(
    new THREE.SphereGeometry(0.997, 64, 64),
    new THREE.MeshBasicMaterial({ color: 0x0c0c0b }),
  ));

  const sprite = makeGlowSprite();

  // ── Puntos de tierra (land dots) ──────────────────────────────────────────
  const mask    = buildLandMask();
  const dotPos  = buildDots(mask, 150000, 1);
  const dotsGeo = new THREE.BufferGeometry();
  dotsGeo.setAttribute("position", new THREE.BufferAttribute(dotPos, 3));

  globe.add(new THREE.Points(dotsGeo, new THREE.PointsMaterial({
    map:             sprite,
    color:           0xb8b8ae,
    size:            0.006,
    sizeAttenuation: true,
    transparent:     true,
    depthWrite:      false,
    blending:        THREE.AdditiveBlending,
  })));

  // ── Líneas de costa ───────────────────────────────────────────────────────
  addLines(
    mesh(worldData, worldData.objects.land),
    1.002,
    new THREE.LineBasicMaterial({
      color:      0xc8c8be,
      blending:   THREE.AdditiveBlending,
      depthWrite: false,
      transparent: true,
    }),
    globe,
  );

  // ── Fronteras interiores ──────────────────────────────────────────────────
  addLines(
    mesh(worldData, worldData.objects.countries, (a, b) => a !== b),
    1.002,
    new THREE.LineBasicMaterial({
      color:       0x686860,
      transparent: true,
      opacity:     0.9,
      blending:    THREE.AdditiveBlending,
      depthWrite:  false,
    }),
    globe,
  );

  // ── Halo atmosférico ──────────────────────────────────────────────────────
  globe.add(new THREE.Mesh(
    new THREE.SphereGeometry(1.09, 64, 64),
    new THREE.MeshBasicMaterial({
      color: 0x2a2a26, transparent: true, opacity: 0.22, side: THREE.BackSide,
    }),
  ));

  // ── Marcadores — mismo estilo que los puntos del mapa, rosas y más grandes ─
  if (markers.length > 0) {
    const markerPos = new Float32Array(markers.flatMap(({ location }) => {
      const [lat, lng] = location;
      const v = geoTo3D([lng, lat], 1.013);
      return [v.x, v.y, v.z];
    }));
    const markerGeo = new THREE.BufferGeometry();
    markerGeo.setAttribute("position", new THREE.BufferAttribute(markerPos, 3));

    // Dot fijo — mismo sprite, color rosa, tamaño algo mayor que los del mapa
    globe.add(new THREE.Points(markerGeo, new THREE.PointsMaterial({
      map:         sprite,
      color:       0xe02157,
      size:        0.028,
      sizeAttenuation: true,
      transparent: true,
      depthWrite:  false,
      blending:    THREE.AdditiveBlending,
    })));

    // Halo pulsante — capa más grande y tenue que respira
    const haloMat = new THREE.PointsMaterial({
      map:         sprite,
      color:       0xe02157,
      size:        0.055,
      sizeAttenuation: true,
      transparent: true,
      opacity:     0.25,
      depthWrite:  false,
      blending:    THREE.AdditiveBlending,
    });
    globe.add(new THREE.Points(markerGeo, haloMat));

    gsap.fromTo(haloMat,
      { opacity: 0.12 },
      { opacity: 0.4, duration: 1.6, repeat: -1, yoyo: true, ease: "sine.inOut" },
    );
  }

  // ── Drag / Touch ──────────────────────────────────────────────────────────
  let rotY = globe.rotation.y;
  let isDragging = false, lastX = 0, velocity = 0;

  canvas.addEventListener("mousedown", (e) => {
    isDragging = true; lastX = e.clientX;
    canvas.style.cursor = "grabbing";
  });
  window.addEventListener("mousemove", (e) => {
    if (!isDragging) return;
    velocity = (e.clientX - lastX) * 0.005; lastX = e.clientX;
  });
  window.addEventListener("mouseup", () => {
    isDragging = false; canvas.style.cursor = "grab";
  });
  canvas.addEventListener("touchstart", (e) => {
    isDragging = true; lastX = e.touches[0].clientX;
  }, { passive: true });
  window.addEventListener("touchmove", (e) => {
    if (!isDragging) return;
    velocity = (e.touches[0].clientX - lastX) * 0.005; lastX = e.touches[0].clientX;
  }, { passive: true });
  window.addEventListener("touchend", () => { isDragging = false; });

  // ── Render loop ───────────────────────────────────────────────────────────
  let rafId;
  function animate() {
    rafId = requestAnimationFrame(animate);
    if (!isDragging) rotY += 0.0022;
    rotY += velocity;
    velocity *= 0.92;
    globe.rotation.y = rotY;
    renderer.render(scene, camera);
  }
  animate();

  // ── Scroll reveal ─────────────────────────────────────────────────────────
  const section = canvas.closest(".block-map");
  gsap.from(wrap, {
    autoAlpha: 0, scale: 0.92, duration: 1.6, ease: "power3.out",
    scrollTrigger: { trigger: section, start: "top 85%", once: true },
  });

  return () => { cancelAnimationFrame(rafId); renderer.dispose(); };
}

document.addEventListener("DOMContentLoaded", () => initBlockMap());
