import pkg from "gulp";
import gulpIf from "gulp-if";
import gulpSass from "gulp-sass";
import * as dartSass from "sass";
import postcss from "gulp-postcss";
import autoprefixer from "autoprefixer";
import cssnano from "cssnano";
import uglify from "gulp-uglify";
import concat from "gulp-concat";
import replace from "gulp-replace";
import { execFile } from "child_process";
import { promisify } from "util";
const execFileAsync = promisify(execFile);
import sharp from "sharp";
import path from "path";
import fs from "fs/promises";
import { existsSync } from "fs";

const { src, dest, parallel, series, watch } = pkg;
const sass = gulpSass(dartSass);
const isProd = process.env.NODE_ENV === "prod";

/**--------------------------------------------------------------------------------------------------------------
 * CONFIGURACIÓN
 *
 * Archivos copiados literal e individualmente desde vendors o módulos de Node.
 --------------------------------------------------------------------------------------------------------------*/
const filesToVendors = [];
const filesToVendorsJs = [];

/**--------------------------------------------------------------------------------------------------------------
 * PLUGINS DE POSTCSS
 *
 * Dev:  solo autoprefixer (añade vendor prefixes según browserslist en package.json)
 * Prod: autoprefixer + cssnano (minifica y optimiza el CSS resultante)
 --------------------------------------------------------------------------------------------------------------*/
const postcssPlugins = isProd
  ? [autoprefixer(), cssnano({ preset: "default" })]
  : [autoprefixer()];

/**--------------------------------------------------------------------------------------------------------------
 * VENDORS
 --------------------------------------------------------------------------------------------------------------*/
function vendorsCopy() {
  if (filesToVendors.length > 0) {
    return src(filesToVendors).pipe(dest("assets/dist/vendors/"));
  }
  return src(".", { allowEmpty: true });
}

function vendorsCopyJs() {
  if (filesToVendorsJs.length > 0) {
    return src(filesToVendorsJs).pipe(dest("assets/dist/js/"));
  }
  return src(".", { allowEmpty: true });
}

/**--------------------------------------------------------------------------------------------------------------
 * CSS PRINCIPAL
 *
 * Pipeline: SCSS → autoprefixer [→ cssnano en prod] → all.css
 --------------------------------------------------------------------------------------------------------------*/
function css() {
  return src("assets/sass/style.scss")
    .pipe(
      sass({
        includePaths: ["node_modules"],
        quietDeps: true,
        silenceDeprecations: ["import"],
      }).on("error", sass.logError)
    )
    .pipe(concat("all.css"))
    .pipe(replace("../../../../", "../"))
    .pipe(replace("../../../", "../"))
    .pipe(replace("../../", "../"))
    .pipe(postcss(postcssPlugins))
    .pipe(dest("assets/dist/css/"));
}

/**--------------------------------------------------------------------------------------------------------------
 * CSS ADMIN/EDITOR
 --------------------------------------------------------------------------------------------------------------*/
function adminCss() {
  return src("assets/sass/admin.scss")
    .pipe(
      sass({
        includePaths: ["node_modules"],
        quietDeps: true,
        silenceDeprecations: ["import"],
      }).on("error", sass.logError)
    )
    .pipe(concat("admin.css"))
    .pipe(replace("../../../../", "../"))
    .pipe(replace("../../../", "../"))
    .pipe(replace("../../", "../"))
    .pipe(postcss(postcssPlugins))
    .pipe(dest("assets/dist/css/"));
}

/**--------------------------------------------------------------------------------------------------------------
 * CSS CRÍTICO (above-the-fold, para inlinear en <head>)
 --------------------------------------------------------------------------------------------------------------*/
function criticalCss() {
  return src("assets/sass/critical.scss")
    .pipe(
      sass({
        includePaths: ["node_modules"],
        quietDeps: true,
        silenceDeprecations: ["import"],
      }).on("error", sass.logError)
    )
    .pipe(concat("critical.css"))
    .pipe(replace("../../../../", "../"))
    .pipe(replace("../../../", "../"))
    .pipe(replace("../../", "../"))
    .pipe(postcss([autoprefixer(), cssnano({ preset: "default" })]))
    .pipe(dest("assets/dist/css/"));
}

/**--------------------------------------------------------------------------------------------------------------
 * JS PRINCIPAL (esbuild bundle)
 *
 * Bundle, tree-shaking y transpilación a ES2015. Minificación en prod.
 --------------------------------------------------------------------------------------------------------------*/
async function js() {
  const esbinPath = new URL("./node_modules/.bin/esbuild", import.meta.url).pathname;
  const args = [
    "assets/js/main.js",
    "--bundle",
    "--outfile=assets/dist/js/bundle.js",
    "--target=es2015",
  ];
  if (isProd) args.push("--minify");
  await execFileAsync(esbinPath, args);
}

/**--------------------------------------------------------------------------------------------------------------
 * JS PARTIALS (archivos individuales sin bundlear)
 *
 * Para scripts que no se importan desde main.js.
 * Minifica en producción con uglify.
 --------------------------------------------------------------------------------------------------------------*/
function minJs() {
  return src("assets/js/partials/**.js")
    .pipe(gulpIf(isProd, uglify()))
    .pipe(dest("assets/dist/js/"));
}

/**--------------------------------------------------------------------------------------------------------------
 * IMÁGENES — sharp
 *
 * Dev:  copia los archivos tal cual a assets/dist/images/.
 * Prod: comprime JPG y PNG, genera variante .webp para cada imagen raster,
 *       copia el resto de formatos (SVG, GIF, etc.) sin procesar.
 --------------------------------------------------------------------------------------------------------------*/
async function img() {
  const srcDir = "assets/img";
  const dstDir = "assets/dist/images";

  // No falla si aún no existe la carpeta de origen
  try {
    await fs.access(srcDir);
  } catch {
    return;
  }

  // Recorre el directorio de forma recursiva
  async function* walk(dir) {
    for (const entry of await fs.readdir(dir, { withFileTypes: true })) {
      const full = path.join(dir, entry.name);
      if (entry.isDirectory()) yield* walk(full);
      else yield full;
    }
  }

  const raster = new Set([".jpg", ".jpeg", ".png"]);

  const process = async (file) => {
    const rel    = path.relative(srcDir, file);
    const ext    = path.extname(file).toLowerCase();
    const name   = path.basename(file, ext);
    const outDir = path.join(dstDir, path.dirname(rel));

    await fs.mkdir(outDir, { recursive: true });

    if (!isProd || !raster.has(ext)) {
      // Dev: copia literal. Prod: copia los formatos no raster (SVG, GIF…)
      await fs.copyFile(file, path.join(outDir, path.basename(file)));
      return;
    }

    // Prod — compresión + WebP
    if (ext === ".png") {
      await sharp(file)
        .png({ compressionLevel: 8, adaptiveFiltering: true })
        .toFile(path.join(outDir, `${name}.png`));
    } else {
      await sharp(file)
        .jpeg({ quality: 80, mozjpeg: true })
        .toFile(path.join(outDir, `${name}${ext}`));
    }

    await sharp(file)
      .webp({ quality: 80 })
      .toFile(path.join(outDir, `${name}.webp`));
  };

  const tasks = [];
  for await (const file of walk(srcDir)) {
    tasks.push(process(file));
  }
  await Promise.all(tasks);
}

/**--------------------------------------------------------------------------------------------------------------
 * MODELOS 3D + FUENTES (copia literal)
 --------------------------------------------------------------------------------------------------------------*/
function models() {
  if (!existsSync("assets/models")) return src(".", { allowEmpty: true });
  return src("assets/models/**/*").pipe(dest("assets/dist/models"));
}

function fonts() {
  if (!existsSync("assets/fonts")) return src(".", { allowEmpty: true });
  return src("assets/fonts/**/*.{woff,woff2,ttf,otf}", { encoding: false })
    .pipe(dest("assets/dist/fonts/", { encoding: false }));
}

/**--------------------------------------------------------------------------------------------------------------
 * WATCH — solo en modo dev
 --------------------------------------------------------------------------------------------------------------*/
function watchFiles() {
  watch("assets/**/*.scss", parallel(css, adminCss, criticalCss));
  watch("assets/js/*.js", series(js));
  watch("assets/js/partials/*.js", series(minJs));
  watch("assets/img/**/*.*", series(img));
}

/**--------------------------------------------------------------------------------------------------------------
 * EXPORTS
 *
 * npm run dev   → cross-env NODE_ENV=dev gulp dev    (sin minificar, watch activo)
 * npm run build → cross-env NODE_ENV=prod gulp build (minificado, sin watch)
 --------------------------------------------------------------------------------------------------------------*/
export { vendorsCopy, vendorsCopyJs, css, adminCss, criticalCss, js, minJs, img, models, fonts, watchFiles };

export const dev = series(
  parallel(vendorsCopy, vendorsCopyJs, css, adminCss, criticalCss, js, img, models, fonts),
  watchFiles
);

export const build = parallel(
  vendorsCopy,
  vendorsCopyJs,
  css,
  adminCss,
  criticalCss,
  js,
  img,
  models,
  fonts
);

export default build;
