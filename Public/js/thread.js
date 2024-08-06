let offset = 0;
const limit = 10;

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
  // カードの作成
  const card = document.createElement('div');
  card.className = 'card my-3';
  card.style.width = '60%';
  card.style.minWidth = '400px';
  card.style.margin = '0 auto';

  // カードボディの作成
  const cardBody = document.createElement('div');
  cardBody.className = 'card-body';

  // タイトルの作成
  const cardTitle = document.createElement('h5');
  cardTitle.className = 'card-title';
  cardTitle.textContent = thread.subject;

  // 日付の作成
  const cardSubtitle = document.createElement('h6');
  cardSubtitle.className = 'card-subtitle mb-2 text-muted';
  cardSubtitle.textContent = thread.createdAt;

  // コンテンツの作成
  const content = document.createElement('p');
  content.textContent = thread.content;

  // 画像の作成
  const img = document.createElement('img');
  img.src = `http://localhost:8000/Images/Thumbnails/${thread.imageHash}`;
  img.alt = 'image';
  const imgAnker = document.createElement('a');
  imgAnker.href = `http://localhost:8000/Images/Originals/${thread.imageHash}`;
  imgAnker.appendChild(img);
  imgAnker.target = '_blank';

  // 要素を組み合わせる
  cardBody.appendChild(cardTitle);
  cardBody.appendChild(cardSubtitle);
  cardBody.appendChild(content);
  if (thread.imageHash) cardBody.appendChild(imgAnker);

  card.appendChild(cardBody);

  return card;
}

function createReplyCard(reply) {
  // カードの作成
  const card = document.createElement('div');
  card.className = 'card';
  card.style.width = '60%';
  card.style.minWidth = '400px';
  card.style.margin = '0 auto';

  // アイコンの作成
  const iconWrapper = document.createElement('div');
  iconWrapper.className = 'pt-2 ps-2 text-start';
  const icon = document.createElement('i');
  icon.className = 'fa-solid fa-reply';
  icon.style.transform = 'rotate(180deg)';
  icon.style.color = '#999';
  iconWrapper.appendChild(icon);

  // カードボディの作成
  const cardBody = document.createElement('div');
  cardBody.className = 'card-body';

  // 日付の作成
  const cardSubtitle = document.createElement('h6');
  cardSubtitle.className = 'card-subtitle mb-2 text-muted';
  cardSubtitle.textContent = reply.createdAt;

  // コンテンツの作成
  const content = document.createElement('p');
  content.textContent = reply.content;

  // 画像の作成
  const img = document.createElement('img');
  img.src = `http://localhost:8000/Images/Thumbnails/${reply.imageHash}`;
  img.alt = 'image';
  const imgAnker = document.createElement('a');
  imgAnker.href = `http://localhost:8000/Images/Originals/${reply.imageHash}`;
  imgAnker.appendChild(img);
  imgAnker.target = '_blank';

  // 要素を組み合わせる
  cardBody.appendChild(cardSubtitle);
  cardBody.appendChild(content);
  if (reply.imageHash) cardBody.appendChild(imgAnker);

  card.appendChild(iconWrapper);
  card.appendChild(cardBody);

  return card;
}

function loadAllReplies(totalCount = 4) {
  const thread = document.getElementById('thread');
  return thread.childElementCount - 1 >= totalCount;
}

function hideMoreRepliesBtn() {
  const wrapper = document.getElementById('more-replies-btn-wrapper');
  wrapper.style.display = 'none';
}

async function loadThread() {
  const resData = await getThread();

  if (resData.success) {
    insertThreadEl(resData.thread);
    return true;
  } else {
    localStorage.setItem('e', resData.error);
    window.location.href = '/';
    hideMoreRepliesBtn();
    return false;
  }
}

async function loadReplies() {
  const resData = await getReplies();

  if (resData.success) {
    offset += limit;
    insertReplyEls(resData.replies);

    if (loadAllReplies(resData.totalCount)) {
      hideMoreRepliesBtn();
    }

    return true;
  } else {
    localStorage.setItem('e', resData.error);
    window.location.href = '/';
    hideMoreRepliesBtn();

    return false;
  }
}

async function loadThreadAndReplies() {
  await loadThread();
  await loadReplies();
}

document.addEventListener('DOMContentLoaded', async function () {
  await loadThreadAndReplies();

  const btn = document.getElementById('more-replies-btn');
  btn.addEventListener('click', async function() {
    await loadReplies();
  })
});
