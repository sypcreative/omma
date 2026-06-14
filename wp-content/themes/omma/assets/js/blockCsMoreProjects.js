export function initBlockCsMoreProjects() {
  const section = document.querySelector('[data-more-projects]');
  if ( !section ) return;

  const previewImg = section.querySelector('[data-preview-img]');
  const items      = section.querySelectorAll('[data-cs-thumb]');

  if ( !previewImg || !items.length ) return;

  items.forEach( item => {
    item.addEventListener( 'mouseenter', () => {
      const thumb = item.dataset.csThumb;
      if ( thumb ) previewImg.src = thumb;
    } );
  } );
}

document.addEventListener( 'DOMContentLoaded', () => initBlockCsMoreProjects() );
