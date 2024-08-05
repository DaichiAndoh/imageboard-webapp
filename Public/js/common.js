async function apiPost(url, body) {
  try {
    const res = await fetch(url, { method: 'POST', body: body });
    const resData = await res.json();
    return resData;
  } catch (error) {
    console.error('Error:', error);
    alert('An error occurred. Please try again.');
    return null;
  }
}
