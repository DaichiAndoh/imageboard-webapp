let offset = 0;
const limit = 10;

async function getAllThreads() {
  const data = new URLSearchParams();
  data.append('offset', offset);
  data.append('limit', limit);

  const resData = await apiPost('/api/get_all_threads', data);
  return resData;
}

function insertThreadEl(threads) {
  const timeline = document.getElementById('timeline');

  for (const thread of threads) {
    const threadCard = createThreadCard(thread);
    timeline.appendChild(threadCard);
  }
}

function createThreadCard(thread) {
  // カードの作成
  const card = document.createElement('div');
  card.className = 'card my-3';
  card.style.width = '80%';
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
  img.src = `http://localhost:8000/Images/Thumbnails/${thread.imageHash}.png`;
  img.alt = 'image';
  const imgAnker = document.createElement('a');
  imgAnker.href = `http://localhost:8000/Images/Originals/${thread.imageHash}.png`;
  imgAnker.appendChild(img);
  imgAnker.target = '_blank';

  // 仕切りの作成
  const hr = document.createElement('hr');

  // 返信リンクの作成
  const link = document.createElement('button');   
  link.type = 'button';
  link.className = 'btn btn-link';
  link.textContent = 'Threadを確認する';

  const linkWrapper = document.createElement('div');
  linkWrapper.className = 'text-center';

  // 要素を組み合わせる
  linkWrapper.appendChild(link);

  cardBody.appendChild(cardTitle);
  cardBody.appendChild(cardSubtitle);
  cardBody.appendChild(content);
  if (thread.imageHash) cardBody.appendChild(imgAnker);
  cardBody.appendChild(hr);
  cardBody.appendChild(linkWrapper);

  card.appendChild(cardBody);

  return card;
}

function loadAllThreads(totalCount) {
  const timeline = document.getElementById('timeline');
  return timeline.childElementCount >= totalCount;
}

function hideMoreThreadsBtn() {
  const wrapper = document.getElementById('more-threads-btn-wrapper');
  wrapper.style.display = 'none';
}

async function loadTimeline() {
  const resData = await getAllThreads();

  if (resData.success) {
    offset += limit;
    insertThreadEl(resData.threads);
  
    if (loadAllThreads(resData.totalCount)) {
      hideMoreThreadsBtn();
    }
  } else {
    const errorAlert = document.getElementById('alert-danger');
    errorAlert.innerText = resData.error;
    errorAlert.style.display = 'block';
    hideMoreThreadsBtn();
  }
}

document.addEventListener('DOMContentLoaded', async function () {
  await loadTimeline();

  const btn = document.getElementById('more-threads-btn');
  btn.addEventListener('click', async function() {
    await loadTimeline();
  })
});
