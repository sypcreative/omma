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
 * Rasteriza las áreas de tierra del topojson sobre un canvas 2000×1000
 * en proyección equirectangular. Retorna ImageData.
 *
 * Usa países individuales (outer ring only) + fill nonzero para evitar
 * agujeros causados por evenodd en polígonos simplificados que se solapan.
 */
function buildLandMask() {
  const W = 2000, H = 1000;
  const c   = Object.assign(document.createElement("canvas"), { width: W, height: H });
  const ctx = c.getContext("2d");

  const countriesGeo = feature(worldData, worldData.objects.countries);
  ctx.fillStyle = "white";

  countriesGeo.features.forEach((f) => {
    const geom = f.geometry;
    if (!geom) return;
    const polys = geom.type === "Polygon" ? [geom.coordinates] : geom.coordinates;
    polys.forEach((poly) => {
      ctx.beginPath();
      poly[0].forEach(([lng, lat], i) => {
        const x = ((lng + 180) / 360) * W;
        const y = ((90 - lat) / 180) * H;
        if (i === 0) ctx.moveTo(x, y);
        else         ctx.lineTo(x, y);
      });
      ctx.closePath();
      ctx.fill(); // nonzero — sin agujeros por solapamientos
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

// ── Mapa 2D ───────────────────────────────────────────────────────────────────

function buildDots2D(mask, W, H, cols, rows) {
  const dots = [];
  for (let r = 0; r < rows; r++) {
    for (let c = 0; c < cols; c++) {
      const lng = ((c + 0.5) / cols) * 360 - 180;
      const lat = 90 - ((r + 0.5) / rows) * 180;
      if (!isLand(mask, lat, lng)) continue;
      dots.push({
        x: ((c + 0.5) / cols) * W,
        y: ((r + 0.5) / rows) * H,
      });
    }
  }
  // Mezcla aleatoria para animación
  for (let i = dots.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [dots[i], dots[j]] = [dots[j], dots[i]];
  }
  return dots;
}

export function initBlockMap2D() {
  const canvas = document.querySelector('.block-map__map-2d');
  if (!canvas) return;

  const markers = JSON.parse(canvas.dataset.markers || '[]');
  const wrap    = canvas.closest('.block-map__globe-wrap');
  const section = canvas.closest('.block-map');
  const W = canvas.offsetWidth;
  const H = canvas.offsetHeight;
  if (!W || !H) return;

  const dpr = Math.min(devicePixelRatio, 2);
  canvas.width  = W * dpr;
  canvas.height = H * dpr;
  const ctx = canvas.getContext('2d');
  ctx.scale(dpr, dpr);

  // Celdas cuadradas: ajusta ROWS al aspect ratio del canvas
  const COLS   = 260;
  const ROWS   = Math.round(COLS * H / W);
  const cellW  = W / COLS;
  const radius = cellW * 0.44;
  const mask   = buildLandMask();
  const dots   = buildDots2D(mask, W, H, COLS, ROWS);

  // Ordena izquierda→derecha para el scan
  const sorted = [...dots].sort((a, b) => a.x - b.x);

  const markerDots = markers.map(({ location, name }) => {
    const [lat, lng] = location;
    return { x: (lng + 180) / 360 * W, y: (90 - lat) / 180 * H, name };
  });

  // Tooltip (igual que modo 3D)
  const tip = document.createElement('div');
  tip.className = 'block-map__tooltip';
  wrap.appendChild(tip);

  canvas.addEventListener('mousemove', e => {
    const rect = canvas.getBoundingClientRect();
    const mx = e.clientX - rect.left;
    const my = e.clientY - rect.top;
    let hit = null;
    for (const m of markerDots) {
      if (Math.hypot(mx - m.x, my - m.y) < 14) { hit = m; break; }
    }
    if (hit) {
      tip.textContent = hit.name;
      tip.style.left  = hit.x + 'px';
      tip.style.top   = hit.y + 'px';
      tip.classList.add('is-visible');
    } else {
      tip.classList.remove('is-visible');
    }
  });
  canvas.addEventListener('mouseleave', () => tip.classList.remove('is-visible'));

  // Render
  let revealedIdx = 0;
  let markerAlpha = 0;

  function drawAll(xThreshold) {
    while (revealedIdx < sorted.length && sorted[revealedIdx].x <= xThreshold) revealedIdx++;

    ctx.clearRect(0, 0, W, H);

    ctx.globalAlpha = 0.6;
    ctx.fillStyle = '#7e7e76';
    ctx.beginPath();
    for (let i = 0; i < revealedIdx; i++) {
      const d = sorted[i];
      ctx.moveTo(d.x + radius, d.y);
      ctx.arc(d.x, d.y, radius, 0, Math.PI * 2);
    }
    ctx.fill();

    if (markerAlpha > 0) {
      markerDots.forEach(m => {
        ctx.globalAlpha = markerAlpha * 0.2;
        ctx.fillStyle = '#e02157';
        ctx.beginPath();
        ctx.arc(m.x, m.y, 11, 0, Math.PI * 2);
        ctx.fill();
        ctx.globalAlpha = markerAlpha;
        ctx.beginPath();
        ctx.arc(m.x, m.y, 5, 0, Math.PI * 2);
        ctx.fill();
      });
    }
  }

  const SCAN_MS   = 2400;
  const MARKER_MS = 700;
  let t0 = null, scanDone = false, markerT0 = null;

  function tick(now) {
    if (!t0) t0 = now;

    if (!scanDone) {
      const t = Math.min(1, (now - t0) / SCAN_MS);
      // ease in-out
      const p = t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t;
      drawAll(p * W);
      if (t < 1) {
        requestAnimationFrame(tick);
      } else {
        scanDone = true;
        markerT0 = now;
        requestAnimationFrame(tick);
      }
    } else {
      markerAlpha = Math.min(1, (now - markerT0) / MARKER_MS);
      drawAll(W);
      if (markerAlpha < 1) requestAnimationFrame(tick);
    }
  }

  gsap.set(wrap, { autoAlpha: 0, scale: 0.95 });
  ScrollTrigger.create({
    trigger: section,
    start:   'top 85%',
    once:    true,
    onEnter() {
      gsap.to(wrap, { autoAlpha: 1, scale: 1, duration: 1.2, ease: 'expo.out' });
      requestAnimationFrame(tick);
    },
  });
}

// ── Arcos animados entre oficinas ─────────────────────────────────────────────

function makeArcPoints(v1, v2, segments = 80) {
  const n1 = v1.clone().normalize();
  const n2 = v2.clone().normalize();
  const angle = n1.angleTo(n2);
  if (angle < 0.01) return null; // puntos coincidentes

  // Elevación proporcional a la distancia angular entre ciudades
  const maxElev = 1.015 + angle * 0.04;

  const points = [];
  for (let i = 0; i <= segments; i++) {
    const t = i / segments;
    // Slerp aproximado (nlerp normalizado) para la dirección
    const dir = n1.clone().lerp(n2, t).normalize();
    // Perfil senoidal: sin elevación en extremos, máximo en el centro
    const r = 1.015 + (maxElev - 1.015) * Math.sin(t * Math.PI);
    points.push(dir.multiplyScalar(r));
  }
  return points;
}

function initArcs(markers, globe, startDelay = 0) {
  if (markers.length < 2) return;

  const arcs = [];
  for (let i = 0; i < markers.length; i++) {
    for (let j = i + 1; j < markers.length; j++) {
      const [lat1, lng1] = markers[i].location;
      const [lat2, lng2] = markers[j].location;
      const v1 = geoTo3D([lng1, lat1], 1.015);
      const v2 = geoTo3D([lng2, lat2], 1.015);
      const points = makeArcPoints(v1, v2, 80);
      if (!points) continue;

      const geo = new THREE.BufferGeometry().setFromPoints(points);
      geo.setDrawRange(0, 0);
      const mat = new THREE.LineBasicMaterial({
        color: 0xe02157, transparent: true, opacity: 0.5,
        depthWrite: false, blending: THREE.AdditiveBlending,
      });
      globe.add(new THREE.Line(geo, mat));
      arcs.push({ geo, total: points.length });
    }
  }

  arcs.forEach((arc, i) => {
    gsap.to(arc.geo.drawRange, {
      count: arc.total,
      duration: 1.4,
      delay: startDelay + i * 0.35,
      ease: "power2.inOut",
      onUpdate() { arc.geo.drawRange.count = Math.round(arc.geo.drawRange.count); },
    });
  });
}

// ── Init ──────────────────────────────────────────────────────────────────────

export function initBlockMap() {
  const wrap = document.querySelector(".block-map__globe-wrap");
  if (!wrap) return;

  const canvas = wrap.querySelector(".block-map__globe");
  if (!canvas) return;
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

  const TARGET_ROT_Y = -Math.PI * 0.3;

  const globe = new THREE.Group();
  globe.rotation.x = 0;
  globe.rotation.y = TARGET_ROT_Y - Math.PI * 2;
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

  // ── Marcadores — mismo estilo que los puntos del mapa, rosas y más grandes ─
  let markerGeo    = null;
  let markerPoints = null;

  if (markers.length > 0) {
    const markerPos = new Float32Array(markers.flatMap(({ location }) => {
      const [lat, lng] = location;
      const v = geoTo3D([lng, lat], 1.013);
      return [v.x, v.y, v.z];
    }));
    markerGeo = new THREE.BufferGeometry();
    markerGeo.setAttribute("position", new THREE.BufferAttribute(markerPos, 3));

    // Dot fijo — mismo sprite, color rosa, tamaño algo mayor que los del mapa
    markerPoints = new THREE.Points(markerGeo, new THREE.PointsMaterial({
      map:         sprite,
      color:       0xe02157,
      size:        0.028,
      sizeAttenuation: true,
      transparent: true,
      depthWrite:  false,
      blending:    THREE.AdditiveBlending,
    }));
    globe.add(markerPoints);

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
  const rotState = { y: TARGET_ROT_Y - Math.PI * 2, x: 0 };
  const vel      = { x: 0, y: 0 };
  let isDragging = false, lastX = 0, lastY = 0;

  canvas.addEventListener("mousedown", (e) => {
    gsap.killTweensOf(rotState);
    isDragging = true; lastX = e.clientX; lastY = e.clientY;
    canvas.style.cursor = "grabbing";
  });
  window.addEventListener("mousemove", (e) => {
    if (!isDragging) return;
    vel.y = (e.clientX - lastX) * 0.005; lastX = e.clientX;
    vel.x = (e.clientY - lastY) * 0.003; lastY = e.clientY;
  });
  window.addEventListener("mouseup", () => {
    isDragging = false; canvas.style.cursor = "grab";
  });
  canvas.addEventListener("touchstart", (e) => {
    gsap.killTweensOf(rotState);
    isDragging = true;
    lastX = e.touches[0].clientX; lastY = e.touches[0].clientY;
  }, { passive: true });
  window.addEventListener("touchmove", (e) => {
    if (!isDragging) return;
    vel.y = (e.touches[0].clientX - lastX) * 0.005; lastX = e.touches[0].clientX;
    vel.x = (e.touches[0].clientY - lastY) * 0.003; lastY = e.touches[0].clientY;
  }, { passive: true });
  window.addEventListener("touchend", () => { isDragging = false; });

  // ── Render loop ───────────────────────────────────────────────────────────
  let rafId;
  function animate() {
    rafId = requestAnimationFrame(animate);
    rotState.y += vel.y;
    rotState.x  = Math.max(-0.5, Math.min(0.5, rotState.x + vel.x));
    vel.x *= 0.92;
    vel.y *= 0.92;
    globe.rotation.y = rotState.y;
    globe.rotation.x = rotState.x;
    renderer.render(scene, camera);
  }
  animate();

  // ── Tooltip hover ─────────────────────────────────────────────────────────
  const tooltip      = wrap.querySelector(".block-map__tooltip");
  const raycaster    = new THREE.Raycaster();
  raycaster.params.Points = { threshold: 0.045 };
  const markerNames  = markers.map(m => m.name || '');

  wrap.addEventListener("mousemove", (e) => {
    if (!tooltip || !markerPoints) return;
    const rect = wrap.getBoundingClientRect();
    const mx   = ((e.clientX - rect.left) / rect.width)  *  2 - 1;
    const my   = ((e.clientY - rect.top)  / rect.width)  * -2 + 1; // canvas cuadrado
    raycaster.setFromCamera({ x: mx, y: my }, camera);
    const hits = raycaster.intersectObject(markerPoints);
    if (hits.length) {
      const name = markerNames[hits[0].index];
      if (name) {
        const pos = new THREE.Vector3();
        pos.fromBufferAttribute(markerGeo.attributes.position, hits[0].index);
        pos.applyMatrix4(globe.matrixWorld);
        pos.project(camera);
        tooltip.textContent  = name;
        tooltip.style.left   = ((pos.x *  0.5 + 0.5) * rect.width)  + 'px';
        tooltip.style.top    = ((pos.y * -0.5 + 0.5) * rect.width)  + 'px';
        tooltip.classList.add('is-visible');
        return;
      }
    }
    tooltip.classList.remove('is-visible');
  });
  wrap.addEventListener("mouseleave", () => tooltip?.classList.remove('is-visible'));

  // ── Entrada + arcos (scroll trigger) ─────────────────────────────────────
  const section = canvas.closest(".block-map");
  gsap.set(wrap, { autoAlpha: 0, scale: 0.9, filter: "blur(16px)" });

  ScrollTrigger.create({
    trigger: section,
    start:   "top 85%",
    once:    true,
    onEnter() {
      gsap.to(wrap, {
        autoAlpha: 1, scale: 1, filter: "blur(0px)",
        duration: 2.0, ease: "expo.out", clearProps: "filter",
      });
      gsap.to(rotState, {
        y: TARGET_ROT_Y, duration: 3.2, ease: "power3.out",
      });
      initArcs(markers, globe, 2.4);
    },
  });

  return () => { cancelAnimationFrame(rafId); renderer.dispose(); };
}

document.addEventListener("DOMContentLoaded", () => { initBlockMap(); initBlockMap2D(); });
