let offset = 0;
const limit = 10;
const MAX_LOAD_THREAD_NUM = 100;

async function loadTimeline() {
  const resData = await getAllThreads();

  if (resData === null) return;

  if (resData.success) {
    insertThreadEls(resData.threads);
    offset += limit;

    if (loadAllThreads(resData.totalCount)) {
      changeMoreThreadsBtnDisplay('none');
    } else {
      changeMoreThreadsBtnDisplay('block')
    }
  } else {
    console.error(resData.error);
    localStorage.setItem('e', 'タイムラインンデータの取得に失敗しました。');
    window.location.href = '/';
  }
}

async function getAllThreads() {
  const data = new URLSearchParams();
  data.append('offset', offset);
  data.append('limit', limit);

  const resData = await apiPost('/api/get_all_threads', data);
  return resData;
}

function insertThreadEls(threads) {
  const timeline = document.getElementById('timeline');

  for (const thread of threads) {
    const threadCard = createThreadCard(thread);
    timeline.appendChild(threadCard);
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
  content.textContent = thread.content;

  // 画像
  const img = document.createElement('img');
  img.src = `http://localhost:8000/Images/Thumbnails/${thread.imageHash}`;
  img.alt = 'image';
  const imgLink = document.createElement('a');
  imgLink.href = `http://localhost:8000/Images/Originals/${thread.imageHash}`;
  imgLink.target = '_blank';
  imgLink.rel = "noopener noreferrer";
  imgLink.appendChild(img);

  // 仕切り
  const hr = document.createElement('hr');

  // 詳細ページへのリンク
  const toThreadPageLink = document.createElement('a');
  toThreadPageLink.href = `http://localhost:8000/thread/${thread.postId}`;
  toThreadPageLink.innerHTML = 'スレッドを確認する <i class="fa-solid fa-chevron-right"></i>';
  toThreadPageLink.style.textDecoration = 'none';
  toThreadPageLink.style.color = 'black';
  const toThreadPageLinkWrapper = document.createElement('div');
  toThreadPageLinkWrapper.className = 'text-end';
  toThreadPageLinkWrapper.appendChild(toThreadPageLink);

  // 要素を組み合わせる
  cardBody.appendChild(cardTitle);
  cardBody.appendChild(cardSubtitle);
  cardBody.appendChild(content);
  if (thread.imageHash) cardBody.appendChild(imgLink);
  cardBody.appendChild(hr);
  cardBody.appendChild(toThreadPageLinkWrapper);

  card.appendChild(cardBody);

  return card;
}

function loadAllThreads(totalCount) {
  const timeline = document.getElementById('timeline');
  return timeline.childElementCount >= MAX_LOAD_THREAD_NUM || timeline.childElementCount >= totalCount;
}

function changeMoreThreadsBtnDisplay(value) {
  const wrapper = document.getElementById('more-threads-btn-wrapper');
  wrapper.style.display = value;
}

document.addEventListener('DOMContentLoaded', async function () {
  await loadTimeline();

  const btn = document.getElementById('more-threads-btn');
  btn.addEventListener('click', async function() {
    await loadTimeline();
  })
});
