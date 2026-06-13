function initBlockContact() {
  const form = document.querySelector('[data-contact-form]');
  if (!form) return;

  const feedback = form.querySelector('[data-contact-feedback]');
  const submit   = form.querySelector('[type="submit"]');

  form.addEventListener('submit', async e => {
    e.preventDefault();

    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }

    submit.disabled = true;

    let nonce = '';
    try {
      const nonceRes  = await fetch((window.ommaAjax?.resturl ?? '/wp-json') + '/omma/v1/contact-nonce');
      const nonceData = await nonceRes.json();
      nonce = nonceData.nonce ?? '';
    } catch {
      nonce = window.ommaAjax?.nonce ?? '';
    }

    if (!nonce) {
      showFeedback(feedback, 'Error de seguridad. Por favor recarga la página.', 'error');
      submit.disabled = false;
      return;
    }

    const body = new URLSearchParams({
      action:   'omma_contact_submit',
      _wpnonce: nonce,
      fname:    form.elements.fname.value.trim(),
      lname:    form.elements.lname.value.trim(),
      email:    form.elements.email.value.trim(),
      message:  form.elements.message.value.trim(),
    });

    try {
      const res  = await fetch(window.ommaAjax?.ajaxurl ?? '/wp-admin/admin-ajax.php', {
        method:  'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body,
      });
      const data = await res.json();

      showFeedback(
        feedback,
        data.data?.message ?? (data.success ? 'Message sent!' : 'Something went wrong. Please try again.'),
        data.success ? 'success' : 'error'
      );

      if (data.success) form.reset();
    } catch {
      showFeedback(feedback, 'Connection error. Please try again.', 'error');
    } finally {
      submit.disabled = false;
    }
  });
}

function showFeedback(el, message, type) {
  if (!el) return;
  el.textContent = message;
  el.className   = `block-contact__feedback block-contact__feedback--${type}`;
  el.hidden      = false;
}

document.addEventListener('DOMContentLoaded', initBlockContact);
export { initBlockContact };
