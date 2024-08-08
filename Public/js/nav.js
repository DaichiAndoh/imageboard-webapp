function checkAlert() {
  const successMessage = localStorage.getItem('s');
  if (successMessage) {
    const alertMessage = document.getElementById('alert-success-message');
    alertMessage.innerText = successMessage;
    const alert = document.getElementById('alert-success');
    alert.style.display = 'block';
    localStorage.removeItem('s');
  }

  const errorMessage = localStorage.getItem('e');
  if (errorMessage) {
    const alertMessage = document.getElementById('alert-danger-message');
    alertMessage.innerText = errorMessage;
    const alert = document.getElementById('alert-danger');
    alert.style.display = 'block';
    localStorage.removeItem('e');
  }
}

async function resetFormValidation() {
  const invalidInputs = document.querySelectorAll('input.is-invalid, textarea.is-invalid');
  invalidInputs.forEach(function(input) {
    input.classList.remove('is-invalid');
  });
}

async function createThread(formData) {
  const resData = await apiPost('/api/create_thread', formData);
  return resData;
}

document.addEventListener('DOMContentLoaded', async function () {
  checkAlert();

  const form = document.getElementById('create-thread-form');
  form.addEventListener('submit', async function(event) {
    event.preventDefault();
    resetFormValidation();
    const formData = new FormData(form);
    const resData = await createThread(formData);

    if (resData === null) return;

    if (resData.success) {
      localStorage.setItem('s', 'スレッドを作成しました。');
      window.location.href = '/';
    } else if (resData.field) {
      const field = document.getElementById(`thread-${resData.field}`);
      field.classList.add('is-invalid');
      const errorMsg = document.getElementById(`thread-${resData.field}-error-msg`);
      errorMsg.innerText = resData.message;
    } else {
      console.error(resData.error);
      localStorage.setItem('e', 'スレッドの作成に失敗しました。再度作成してください。');
      window.location.href = '/';
    }
  });
});
