async function createThread(formData) {
  const resData = await apiPost('/api/create_thread', formData);
  return resData;
}

document.addEventListener('DOMContentLoaded', async function () {
  const form = document.getElementById('create-thread-form');
  form.addEventListener('submit', async function (event) {
    event.preventDefault();
    const formData = new FormData(form);
    const resData = await createThread(formData);
  });
});
