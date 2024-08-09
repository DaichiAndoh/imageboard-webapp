let offset = 0;
const limit = 5;
const MAX_LOAD_REPLY_NUM = 100;
const URL = `${window.location.protocol}//${window.location.host}`;

function setThreadIdInputValue() {
  const path = window.location.pathname;
  const pathArray = path.split('/');
  const postId = pathArray[pathArray.length - 1];
  const idInput = document.getElementById('thread-id');
  idInput.value = postId;
}

async function loadThreadAndReplies() {
  await loadThread();
  await loadReplies();
}

async function loadThread() {
  const resData = await getThread();

  if (resData === null) return;

  if (resData.success) {
    insertThreadEl(resData.thread);
  } else {
    console.error(resData.error);
    localStorage.setItem('e', 'スレッドデータの取得に失敗しました。');
    window.location.href = '/';
  }
}

async function loadReplies() {
  const resData = await getReplies();

  if (resData === null) return;

  if (resData.success) {
    insertReplyEls(resData.replies);
    changeReplyBtnDisplay('block');
    offset += limit;

    if (loadAllReplies(resData.totalCount)) {
      changeMoreRepliesBtnDisplay('none');
    } else {
      changeMoreRepliesBtnDisplay('block');
    }
  } else {
    console.error(resData.error);
    localStorage.setItem('e', 'リプライデータの取得に失敗しました。');
    window.location.href = '/';
  }
}

async function getThread() {
  const path = window.location.pathname;
  const pathArray = path.split('/');
  const postId = pathArray[pathArray.length - 1];

  const resData = await apiPost('/api/get_thread/' + postId);
  return resData;
}

async function getReplies() {
  const path = window.location.pathname;
  const pathArray = path.split('/');
  const postId = pathArray[pathArray.length - 1];

  const data = new URLSearchParams();
  data.append('offset', offset);
  data.append('limit', limit);

  const resData = await apiPost('/api/get_replies/' + postId, data);
  return resData;
}

function insertThreadEl(thread) {
  const threadEl = document.getElementById('thread');
  const threadCard = createThreadCard(thread);
  threadEl.appendChild(threadCard);
}

function insertReplyEls(replies) {
  const threadEl = document.getElementById('thread');

  for (const reply of replies) {
    const replyCard = createReplyCard(reply);
    threadEl.appendChild(replyCard);
  }
}

function createThreadCard(thread) {
  // カード
  const card = document.createElement('div');
  card.className = 'card my-3';

  // カードボディ
  const cardBody = document.createElement('div');
  cardBody.className = 'card-body';

  // タイトル
  const cardTitle = document.createElement('h5');
  cardTitle.className = 'card-title';
  cardTitle.textContent = thread.subject;

  // 日付
  const cardSubtitle = document.createElement('h6');
  cardSubtitle.className = 'card-subtitle mb-2 text-muted';
  cardSubtitle.textContent = thread.createdAt;

  // コンテンツ
  const content = document.createElement('p');
  content.innerText = thread.content;

  // 画像
  const img = document.createElement('img');
  img.src = `${URL}/images/thumbnails/${thread.imageHash}`;
  img.alt = 'image';
  const imgLink = document.createElement('a');
  imgLink.href = `${URL}/images/originals/${thread.imageHash}`;
  imgLink.target = '_blank';
  imgLink.rel = "noopener noreferrer";
  imgLink.appendChild(img);

  // 要素を組み合わせる
  cardBody.appendChild(cardTitle);
  cardBody.appendChild(cardSubtitle);
  cardBody.appendChild(content);
  if (thread.imageHash) cardBody.appendChild(imgLink);

  card.appendChild(cardBody);

  return card;
}

function createReplyCard(reply) {
  // カード
  const card = document.createElement('div');
  card.className = 'card';

  // カードボディ
  const cardBody = document.createElement('div');
  cardBody.className = 'card-body';

  // アイコン
  const iconWrapper = document.createElement('div');
  iconWrapper.className = 'mb-2 text-start';
  const icon = document.createElement('i');
  icon.className = 'fa-solid fa-reply';
  icon.style.transform = 'rotate(180deg)';
  icon.style.color = '#999';
  iconWrapper.appendChild(icon);

  // 日付
  const cardSubtitle = document.createElement('h6');
  cardSubtitle.className = 'card-subtitle mb-2 text-muted';
  cardSubtitle.textContent = reply.createdAt;

  // コンテンツ
  const content = document.createElement('p');
  content.innerText = reply.content;

  // 画像
  const img = document.createElement('img');
  img.src = `${URL}/images/thumbnails/${reply.imageHash}`;
  img.alt = 'image';
  const imgLink = document.createElement('a');
  imgLink.href = `${URL}/images/originals/${reply.imageHash}`;
  imgLink.target = '_blank';
  imgLink.rel = "noopener noreferrer";
  imgLink.appendChild(img);

  // 要素を組み合わせる
  cardBody.appendChild(iconWrapper);
  cardBody.appendChild(cardSubtitle);
  cardBody.appendChild(content);
  if (reply.imageHash) cardBody.appendChild(imgLink);

  card.appendChild(cardBody);

  return card;
}

function loadAllReplies(totalCount = 4) {
  const thread = document.getElementById('thread');
  return thread.childElementCount - 1 >= MAX_LOAD_REPLY_NUM || thread.childElementCount - 1 >= totalCount;
}

function changeMoreRepliesBtnDisplay(value) {
  const wrapper = document.getElementById('more-replies-btn-wrapper');
  wrapper.style.display = value;
}

function changeReplyBtnDisplay(value) {
  const wrapper = document.getElementById('reply-btn-wrapper');
  wrapper.style.display = value;
}

async function resetFormValidation() {
  const invalidInputs = document.querySelectorAll('input.is-invalid, textarea.is-invalid');
  invalidInputs.forEach(function(input) {
    input.classList.remove('is-invalid');
  });
}

async function createReply(formData) {
  const resData = await apiPost('/api/create_reply', formData);
  return resData;
}

document.addEventListener('DOMContentLoaded', async function () {
  setThreadIdInputValue();
  await loadThreadAndReplies();

  const btn = document.getElementById('more-replies-btn');
  btn.addEventListener('click', async function() {
    await loadReplies();
  });

  const form = document.getElementById('create-reply-form');
  form.addEventListener('submit', async function(event) {
    event.preventDefault();
    resetFormValidation();
    const formData = new FormData(form);
    const resData = await createReply(formData);

    if (resData === null) return;

    if (resData.success) {
      localStorage.setItem('s', 'リプライを作成しました。');
      window.location.reload();
    } else if (resData.field) {
      const field = document.getElementById(`reply-${resData.field}`);
      field.classList.add('is-invalid');
      const errorMsg = document.getElementById(`reply-${resData.field}-error-msg`);
      errorMsg.innerText = resData.message;
    } else {
      console.error(resData.error);
      localStorage.setItem('e', 'リプライの作成に失敗しました。再度作成してください。');
      window.location.reload();
    }
  });
});
